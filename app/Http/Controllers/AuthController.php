<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\User;
use App\Services\CloudflareService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function __construct(protected CloudflareService $cfService) {}

    public function register()
    {
        return view('pages.auth.register');
    }

    public function register_post(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string',
            'username' => 'required|unique:users,username',
            'password' => 'required|min:3',
        ]);

        try {
            $user = User::create([
                'name' => $validated['nama_lengkap'],
                'username' => $validated['username'],
                'password' => Hash::make($validated['password']),
                'status' => "active"
            ]);

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
                'user_id' => $user->id,
                'web_token' => $hash_token, // Cth: "X9K2mPQL1a" -> Ini yg dimasukin user ke aplikasi Python
                'cf_token' => $cfTunnel['tunnel_token'],
                'cf_tunnel_id' => $cfTunnel['tunnel_id'],
                'tunnel_url' => $fullUrl,
                'port' => 8765,
            ]);


            Auth::login($user);
            return $this->toWithAlert('user.devices', null, 'success', 'Pendaftaran akun berhasil');
        } catch (\Throwable $th) {
            return $this->backWithAlert('error', "Terjadi kesalahan sistem!, Silahkan ulangi proses pendaftaran.");
        }
    }

    public function login()
    {
        return view('pages.auth.login');
    }

    public function login_post(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|exists:users,username',
            'password' => 'required|min:3',
        ]);

        try {
            $user = User::where('username', $validated['username'])->first();

            if (!$user) {
                return $this->backWithAlert('error', 'Data akun tidak ditemukan!');
            }

            if (!Hash::check($validated['password'], $user->password)) {
                return $this->backWithAlert('error', 'Password salah!');
            }

            Auth::login($user);
            if ($user->role === 'user') {
                return $this->toWithAlert('user.devices', null, 'success', "Login lerhasil");
            }
            return $this->toWithAlert('admin.home.index', null, 'success', "Login lerhasil");
        } catch (\Throwable $th) {
            throw $th;
            return $this->backWithAlert('error', 'Terjadi kesalahan sistem!');
        }
    }

    public function logout()
    {
        Auth::logout();

        return $this->toWithAlert('login', null, 'success', 'Logout berhasil!');
    }
}
