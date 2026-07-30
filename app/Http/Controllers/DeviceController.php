<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\User;
use App\Services\CloudflareService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeviceController extends Controller
{
    protected CloudflareService $cfService;

    public function __construct(CloudflareService $cfService)
    {
        $this->cfService = $cfService;
    }

    public function index()
    {
        $devices = Device::with('user')->latest()->get();
        return view('pages.devices.index', compact('devices'));
    }

    public function create()
    {
        $users = User::all(); // Untuk dropdown pilihan user
        return view('pages.devices.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'device_name' => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            // 1. Generate Subdomain Unik 4 Huruf
            $subdomain = $this->cfService->getUniqueSubdomain();
            $tunnelName = "tunnel-{$subdomain}";
            $domain = env('CLOUDFLARE_DOMAIN');
            $fullUrl = "https://{$subdomain}.{$domain}";

            // 2. Create Tunnel di CF
            $cfTunnel = $this->cfService->createTunnel($tunnelName);
            $tunnelId = $cfTunnel['tunnel_id'];

            // 3. Konfigurasi Routing
            $this->cfService->configureTunnelRoute($tunnelId, $subdomain);

            // 4. Create DNS CNAME
            $this->cfService->createDnsRecord($subdomain, $tunnelId);

            $hash_token = hash('sha1', $cfTunnel['tunnel_token']);

            // 5. Simpan ke Database
            Device::create([
                'user_id' => $request->user_id,
                'web_token' => $hash_token,
                'cf_token' => $cfTunnel['tunnel_token'],
                'cf_tunnel_id' => $cfTunnel['tunnel_id'],
                'tunnel_url' => $fullUrl,
                'port' => 8765,
            ]);

            DB::commit();
            return redirect()->route('admin.devices.index')->with('success', "Device & Tunnel ($fullUrl) berhasil dibuat!");
        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Device Store Exception: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan sistem, Silahkan coba lagi');
        }
    }

    public function edit(Device $device)
    {
        $users = User::all();
        return view('pages.devices.edit', compact('device', 'users'));
    }

    public function update(Request $request, Device $device)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'device_name' => 'required|string|max:255',
        ]);

        // Cukup update DB lokal. Tunnel url dan token tidak perlu diubah.
        $device->update([
            'user_id' => $request->user_id,
            'device_name' => $request->device_name,
        ]);

        return redirect()->route('admin.devices.index')->with('success', 'Device berhasil diperbarui!');
    }

    public function destroy(Device $device)
    {
        try {
            // Ambil hostname dari tunnel url
            $hostname = parse_url($device->tunnel_url, PHP_URL_HOST);

            // Decode tunnel_token untuk mendapatkan id tunnel
            $tokenPayload = json_decode(base64_decode($device->tunnel_token), true);
            $tunnelId = $tokenPayload['t'] ?? null;

            if ($tunnelId && $hostname) {
                // Eksekusi hapus di Cloudflare
                $this->cfService->deleteTunnelAndDns($tunnelId, $hostname);
            }

            // Hapus dari DB
            $device->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Device dan Tunnel berhasil dihapus permanen!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus device: ' . $e->getMessage()
            ], 500);
        }
    }
}
