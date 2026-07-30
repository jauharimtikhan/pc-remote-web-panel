<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log; // <-- Wajib import ini
use Illuminate\Support\Str;

class CloudflareService
{
    protected $baseUrl = 'https://api.cloudflare.com/client/v4';
    protected string $accountId;
    protected string $zoneId;
    protected string $domain;
    protected array $headers;

    public function __construct()
    {
        $this->accountId = config('services.cloudflare.account_id');
        $this->zoneId = config('services.cloudflare.zone_id');
        $this->domain = config('services.cloudflare.domain');

        $this->headers = [
            'Authorization' => 'Bearer ' . config('services.cloudflare.api_token'),
            'Content-Type' => 'application/json',
        ];
    }

    // 1. Generate 4 Karakter Random & Cek Ketersediaan DNS
    public function getUniqueSubdomain()
    {
        do {
            $subdomain = strtolower(Str::random(4));
            $fullDomain = "{$subdomain}.{$this->domain}";
            $exists = $this->checkDnsExists($fullDomain);
        } while ($exists);

        return $subdomain;
    }

    // 2. Cek apakah DNS CNAME sudah ada
    private function checkDnsExists(string $fullDomain)
    {
        Log::info("CF API: Mengecek ketersediaan DNS untuk {$fullDomain}");

        $response = Http::withHeaders($this->headers)
            ->get("{$this->baseUrl}/zones/{$this->zoneId}/dns_records", [
                'name' => $fullDomain,
                'type' => 'CNAME'
            ]);

        if (!$response->successful()) {
            Log::error("CF API Error [checkDnsExists]: " . $response->body());
            return false; // Anggap belum ada kalau error, biar looping gak infinite
        }

        $data = $response->json();
        return count($data['result'] ?? []) > 0;
    }

    // 3. Create Tunnel (Akan mereturn Tunnel ID & Token)
    public function createTunnel(string $tunnelName)
    {
        Log::info("CF API: Memulai pembuatan Tunnel {$tunnelName}");
        $tunnelSecret = base64_encode(random_bytes(32));

        $response = Http::withHeaders($this->headers)
            ->post("{$this->baseUrl}/accounts/{$this->accountId}/cfd_tunnel", [
                'name' => $tunnelName,
                'tunnel_secret' => $tunnelSecret
            ]);

        if (!$response->successful()) {
            Log::error("CF API Error [createTunnel]: " . $response->body());
            throw new \Exception('Gagal membuat Cloudflare Tunnel. Cek laravel.log untuk detail.');
        }

        $tunnelId = $response->json()['result']['id'];
        Log::info("CF API: Tunnel {$tunnelName} berhasil dibuat dengan ID: {$tunnelId}");

        $tokenPayload = json_encode([
            'a' => $this->accountId,
            't' => $tunnelId,
            's' => $tunnelSecret
        ]);
        $tunnelToken = base64_encode($tokenPayload);

        return [
            'tunnel_id' => $tunnelId,
            'tunnel_token' => $tunnelToken
        ];
    }

    // 4. Konfigurasi Ingress Rules ke http (default)
    public function configureTunnelRoute(string $tunnelId, string $subdomain, string $port = '8765')
    {
        $hostname = "{$subdomain}.{$this->domain}";
        Log::info("CF API: Konfigurasi Ingress Routing untuk {$hostname} (Tunnel ID: {$tunnelId})");

        $response = Http::withHeaders($this->headers)
            ->put("{$this->baseUrl}/accounts/{$this->accountId}/cfd_tunnel/{$tunnelId}/configurations", [
                'config' => [
                    'ingress' => [
                        ['hostname' => $hostname, 'service' => "http://localhost:{$port}"],
                        ['service' => 'http_status:404']
                    ]
                ]
            ]);

        if (!$response->successful()) {
            Log::error("CF API Error [configureTunnelRoute]: " . $response->body());
            throw new \Exception('Gagal mengatur Ingress Route. Cek laravel.log untuk detail.');
        }

        return true;
    }

    // 5. Buat DNS Record CNAME mengarah ke Argo Tunnel
    public function createDnsRecord(string $subdomain, string $tunnelId)
    {
        $hostname = "{$subdomain}.{$this->domain}";
        $target = "{$tunnelId}.cfargotunnel.com";

        Log::info("CF API: Membuat DNS CNAME {$hostname} -> {$target}");

        $response = Http::withHeaders($this->headers)
            ->post("{$this->baseUrl}/zones/{$this->zoneId}/dns_records", [
                'type' => 'CNAME',
                'name' => $hostname,
                'content' => $target,
                'proxied' => true,
                'comment' => 'Created via Laravel App'
            ]);

        if (!$response->successful()) {
            Log::error("CF API Error [createDnsRecord]: " . $response->body());
            throw new \Exception('Gagal membuat DNS Record. Cek laravel.log untuk detail.');
        }

        return $response->json()['result']['id'] ?? null;
    }

    // 6. Delete Data dari Cloudflare (Cleanup)
    public function deleteTunnelAndDns(string $tunnelId, string $hostname)
    {
        Log::info("CF API: Memulai penghapusan Tunnel & DNS untuk {$hostname}");

        // Cari & Hapus DNS
        $dnsRes = Http::withHeaders($this->headers)->get("{$this->baseUrl}/zones/{$this->zoneId}/dns_records", ['name' => $hostname]);
        if ($dnsRes->successful()) {
            $dnsRecords = $dnsRes->json()['result'] ?? [];
            if (!empty($dnsRecords)) {
                $dnsId = $dnsRecords[0]['id'];
                $delDns = Http::withHeaders($this->headers)->delete("{$this->baseUrl}/zones/{$this->zoneId}/dns_records/{$dnsId}");
                if (!$delDns->successful()) Log::error("CF API Error [deleteDns]: " . $delDns->body());
            }
        } else {
            Log::error("CF API Error [findDnsForDelete]: " . $dnsRes->body());
        }

        // Hapus Tunnel
        $delTunnel = Http::withHeaders($this->headers)->delete("{$this->baseUrl}/accounts/{$this->accountId}/cfd_tunnel/{$tunnelId}");
        if (!$delTunnel->successful()) {
            Log::error("CF API Error [deleteTunnel]: " . $delTunnel->body());
        }

        Log::info("CF API: Proses penghapusan {$hostname} selesai.");
    }

    public function updateTunnelRoute(string $domain, string $tunnelId, string $port)
    {
        $response = Http::withHeaders($this->headers)
            ->put("{$this->baseUrl}/accounts/{$this->accountId}/cfd_tunnel/{$tunnelId}/configurations", [
                'config' => [
                    'ingress' => [
                        ['hostname' => $domain, 'service' => "http://localhost:{$port}"],
                        ['service' => 'http_status:404']
                    ]
                ]
            ]);

        if (!$response->successful()) {
            Log::error("CF API Error [configureTunnelRoute]: " . $response->body());
            throw new \Exception('Gagal mengatur Ingress Route. Cek laravel.log untuk detail.');
        }

        return true;
    }
}
