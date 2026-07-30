<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\User;
use App\Services\CloudflareService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UserDeviceController extends Controller
{

    public function __construct(protected CloudflareService $cfService)
    {
        //
    }

    // Halaman Dashboard User
    public function index()
    {
        /**
         * @var User $user
         */
        $user = Auth::user();
        $device = $user->device()->first();

        return view('pages.user.devices.index', compact('device'));
    }

    // Fungsi Simpan Pengaturan
    public function updateConfig(Request $request, $deviceId)
    {
        /**
         * @var User $user
         */
        $user = Auth::user();
        $device = $user->config()->where('device_id', $deviceId)->firstOrFail();

        $request->validate([
            'port' => 'nullable|integer',
            'mode' => 'nullable|string|max:255',
            'security_pin' => 'nullable|string|max:255',
        ]);
        $subdomain = Str::replace('.' . env('CLOUDFLARE_DOMAIN'), '', $device->tunnel_url);
        $this->cfService->configureTunnelRoute($device->tunnel_id, $subdomain, $request->port ?? 8765);


        // Update atau Create data config
        $device->updateOrCreate(
            ['user_id' => Auth::id()], // Kondisi pencarian
            [
                'device_id' => $request->device_id,
                'port' => $request->port,
                'mode' => $request->mode,
                'security_pin' => $request->security_pin,
            ] // Data yang diupdate/dibuat
        );

        return back()->with('success', 'Pengaturan device berhasil disimpan!');
    }

    public function api_heartbeat(Request $request)
    {
        $device = Device::where('device_id', $request->input('device_id'))
            ->where('web_token', $request->input('web_token'))
            ->first();

        if ($device) {
            $device->update([
                'mode' => $request->input('mode'),
                'port' => $request->input('port'),
                'local_ip' => $request->input('local_ip'),
                'active_clients' => $request->input('active_clients'),
                'is_online' => true,
                'last_seen_at' => Carbon::now()
            ]);
        }

        return response()->json(['status' => 'ok']);
    }

    public function api_sync_settings(Request $request)
    {
        $device = Device::where('device_id', $request->input('device_id'))
            ->where('web_token', $request->input('web_token'))
            ->first();

        if (!$device) {
            return response()->json(['success' => false, 'message' => 'Device tidak ditemukan']);
        }

        $newPort = $request->input('port');

        // JIKA user mengubah port di desktop app, kita harus lapor ke Cloudflare API
        if ($device->port != $newPort && $device->cf_tunnel_id) {
            try {

                $this->cfService->updateTunnelRoute($device->tunnel_url, $device->cf_tunnel_id, $newPort);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal update routing di Cloudflare'
                ]);
            }
        }

        // Update data di database lokal
        $device->update([
            'port' => $newPort,
            'mode' => $request->input('mode'),
            'local_ip' => $request->input('local_ip'),
            'last_seen_at' => Carbon::now()
        ]);

        return response()->json(['success' => true]);
    }

    public function api_validated_token(Request $request)
    {
        $token = $request->input('token');
        $deviceId = $request->input('device_id');

        // Cari token web yang di-generate user di Web Panel
        $device = Device::where('web_token', $token)->first();

        if (!$device) {
            return response()->json([
                'success' => false,
                'message' => 'Token web panel tidak valid atau tidak ditemukan!'
            ]);
        }

        // Generate PIN 6 digit baru setiap kali validasi berhasil
        $newPin = sprintf("%06d", mt_rand(1, 999999));

        // Update data device
        $device->update([
            'device_id' => $deviceId,
            'port' => $request->input('port', 8765),
            'security_pin' => $newPin,
            'is_online' => true,
            'last_seen_at' => Carbon::now()
        ]);

        // Return respons persis sesuai ekspektasi Python App
        return response()->json([
            'success' => true,
            'valid' => true,
            'cf_token' => $device->cf_token, // Pastikan token Cloudflare udah lo masukin ke database via web panel
            'pin' => $newPin,
            'message' => 'Token Valid!',
            'cf_tunnel' => $device->tunnel_url
        ]);
    }

    public function getConnectionInfo($pin)
    {
        $device = Device::where('security_pin', $pin)->first();

        if (!$device) {
            return response()->json(['success' => false, 'message' => 'PIN salah!'], 404);
        }

        $isReallyOnline = $device->is_online && $device->last_seen_at > Carbon::now()->subMinutes(4);
        if (!$isReallyOnline) {
            return response()->json(['success' => false, 'message' => 'PC sedang offline!'], 400);
        }

        // Ubah domain Cloudflare menjadi protokol WSS (WebSocket Secure)
        $cfOnlineUrl = preg_replace('#^https?://#', '', $device->tunnel_url);

        return response()->json([
            'success' => true,
            'mode' => $device->mode,
            'local_url' => $device->local_ip,
            'online_url' => $cfOnlineUrl
        ]);
    }
}
