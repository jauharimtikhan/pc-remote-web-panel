@extends('layout.fullwidth')

@section('content')
    <div class="container mt-4 mb-5">

        <!-- Bumper Welcome -->
        <div class="row mb-4">
            <div class="col-12">
                <div
                    class="card-style bg-success text-white p-4 d-flex justify-content-between align-items-center flex-wrap gap-3 rounded-3">
                    <div>
                        <h2 class="text-white mb-2">Halo, {{ explode(' ', Auth::user()->name)[0] }}!</h2>
                        <p class="text-white opacity-75 text-medium">Ini adalah pusat kontrol utama untuk Device dan Tunnel
                            Anda.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alert Notifications -->
        @if (session('success'))
            <div class="alert-box success-alert alert-dismissible fade show mb-4" role="alert">
                <div class="alert">
                    <p class="text-medium"><i class="lni lni-checkmark-circle"></i> {{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if (!$device)
            <!-- Tampilan Jika User Belum Dibuatkan Device -->
            <div class="row">
                <div class="col-12">
                    <div class="card-style bg-white p-5 text-center shadow-sm rounded-3">
                        <img src="https://cdni.iconscout.com/illustration/premium/thumb/empty-state-2130362-1800926.png"
                            alt="Empty" style="width: 250px; opacity: 0.8;" class="mb-3">
                        <h3 class="text-bold mb-2">Belum Ada Device Terhubung</h3>
                        <p class="text-gray text-medium mb-4">Akun Anda hanya dapat menampung maksimal 1 device, dan saat
                            ini belum dialokasikan.</p>
                        <a href="https://wa.me/6285877134872" target="_blank"
                            class="main-btn success-btn btn-hover rounded-full">
                            <i class="lni lni-whatsapp mr-5"></i> Hubungi Admin
                        </a>
                    </div>
                </div>
            </div>
        @else
            <!-- Tampilan Jika Device Ada -->
            <div class="row">

                <!-- KOLOM KIRI: INFO DEVICE -->
                <div class="col-lg-5 mb-30">
                    <div class="card-style h-100 shadow-sm border-0 position-relative">
                        <div class="title d-flex justify-content-between align-items-center mb-3">
                            <h4 class="text-bold">Informasi Device</h4>
                            @if ($device->is_online)
                                <span class="badge bg-success rounded-pill px-3 py-2 text-sm"><i
                                        class="lni lni-checkmark-circle"></i> Online</span>
                            @else
                                <span class="badge bg-danger rounded-pill px-3 py-2 text-sm"><i class="lni lni-close"></i>
                                    Offline</span>
                            @endif
                        </div>

                        <div class="text-center mb-4 mt-3">
                            <div class="bg-light text-primary d-inline-flex align-items-center justify-content-center rounded-circle mb-3"
                                style="width: 80px; height: 80px;">
                                <svg width="80" height="80" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg" transform="rotate(0 0 0)">
                                    <path
                                        d="M2 5.70117C2 4.45853 3.00736 3.45117 4.25 3.45117H19.75C20.9926 3.45117 22 4.45853 22 5.70117V11.9512H2V5.70117Z"
                                        fill="#343C54" />
                                    <path
                                        d="M2 13.4512V15.2868C2 16.5294 3.00736 17.5368 4.25 17.5368H9.75003V19.049H8.74997C8.33576 19.049 7.99997 19.3847 7.99997 19.799C7.99997 20.2132 8.33576 20.549 8.74997 20.549H15.25C15.6642 20.549 16 20.2132 16 19.799C16 19.3847 15.6642 19.049 15.25 19.049H14.25V17.5368H19.75C20.9926 17.5368 22 16.5294 22 15.2868V13.4512H2Z"
                                        fill="#343C54" />
                                </svg>
                            </div>
                            <h3 class="text-bold">{{ $device->device_name }}</h3>
                            <p class="text-sm text-gray mt-1">Terakhir aktif:
                                {{ $device->last_seen_at ? \Carbon\Carbon::parse($device->last_seen_at)->diffForHumans() : 'Belum pernah' }}
                            </p>
                        </div>

                        <hr>

                        <div class="mb-4 mt-4">
                            <label class="text-sm text-bold mb-2 d-block text-gray">URL Tunnel (Akses Publik)</label>
                            <div class="p-3 bg-light rounded text-center border">
                                <a href="{{ $device->tunnel_url }}" target="_blank" class="text-primary text-bold"
                                    style="word-break: break-all;">
                                    {{ $device->tunnel_url }}
                                </a>
                            </div>
                        </div>

                        <div class="mb-2">
                            <label class="text-sm text-bold mb-2 d-block text-gray">Pairing Token (Client Agent)</label>
                            <div class="input-group">
                                <input type="text" class="form-control bg-light text-muted"
                                    value="{{ $device->web_token }}" readonly id="pairing-token">
                                <button class="btn btn-outline-primary" onclick="copyToken('pairing-token')">
                                    <i class="lni lni-clipboard"></i> Salin
                                </button>
                            </div>
                            <small class="text-gray mt-2 d-block">Gunakan token ini pada aplikasi remote server di perangkat
                                Anda.</small>
                        </div>
                    </div>
                </div>

                <!-- KOLOM KANAN: TAB PENGATURAN & QR CODE -->
                <div class="col-lg-7 mb-30">
                    <div class="card-style h-100 shadow-sm border-0">

                        <!-- Nav Tabs (Bootstrap) -->
                        <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active text-bold" id="setting-tab" data-bs-toggle="tab"
                                    data-bs-target="#setting" type="button" role="tab" style="color: #333;">
                                    <i class="lni lni-cog"></i> Pengaturan Konfigurasi
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link text-bold" id="qr-tab" data-bs-toggle="tab"
                                    data-bs-target="#qrcode" type="button" role="tab" style="color: #333;">
                                    <i class="lni lni-frame-expand"></i> QR Code Klien
                                </button>
                            </li>
                        </ul>

                        <!-- Tab Contents -->
                        <div class="tab-content" id="myTabContent">

                            <!-- TAB 1: PENGATURAN -->
                            <div class="tab-pane fade show active" id="setting" role="tabpanel">
                                <form action="{{ route('user.devices.config.update', $device->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="input-style-1">
                                                <label>Port Lokal</label>
                                                <input type="number" name="port" placeholder="Contoh: 8080"
                                                    value="{{ old('port', $device->config->port ?? ($device->port ?? '8765')) }}" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="select-style-1">
                                                <label>Mode Tunnel</label>
                                                <div class="select-position">
                                                    <select name="mode">
                                                        <option value="">-- Pilih Mode --</option>
                                                        <option value="Lokal (LAN)"
                                                            {{ old('mode', $device->config->mode ?? ($device->mode ?? '')) == 'Lokal (LAN)' ? 'selected' : '' }}>
                                                            Lokal (LAN)</option>
                                                        <option value="Online (Cloudflare Tunnel)"
                                                            {{ old('mode', $device->config->mode ?? ($device->mode ?? '')) == 'Online (Cloudflare Tunnel)' ? 'selected' : '' }}>
                                                            Online</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="input-style-1">
                                        <label>Security PIN</label>
                                        <input type="password" name="security_pin" placeholder="PIN Keamanan 4-6 Digit"
                                            value="{{ old('security_pin', $device->config->security_pin ?? ($device->security_pin ?? '')) }}" />
                                        <small class="text-gray">Biarkan kosong jika tidak ingin menggunakan PIN.</small>
                                    </div>

                                    <div class="d-flex justify-content-end mt-4">
                                        <button type="submit" class="main-btn primary-btn btn-hover rounded-3">
                                            <i class="lni lni-save mr-5"></i> Simpan Pengaturan
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- TAB 2: QR CODE SCANNER -->
                            <div class="tab-pane fade" id="qrcode" role="tabpanel">
                                <div class="text-center p-4">
                                    <h5 class="mb-3 text-bold">Scan untuk Menghubungkan Android</h5>
                                    <p class="text-gray text-sm mb-4">
                                        Gunakan fitur <b>Scan QR</b> di aplikasi Android Pramaxx Remote untuk terhubung
                                        otomatis tanpa perlu mengetik manual.
                                    </p>

                                    <!-- Wadah Gambar QR Code -->
                                    <div class="d-inline-block bg-white p-3 rounded shadow-sm border mb-3"
                                        id="qrcode-display"></div>

                                    <!-- Info Data QR -->
                                    <div class="bg-light p-3 rounded-3 mt-2 text-start d-inline-block"
                                        style="max-width: 300px; width: 100%;">
                                        <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                                            <span class="text-sm text-gray">Target:</span>
                                            <span class="text-sm text-bold" id="qr-target-text"></span>
                                        </div>
                                        <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                                            <span class="text-sm text-gray">Port:</span>
                                            <span
                                                class="text-sm text-bold">{{ $device->config->port ?? ($device->port ?? '8765') }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span class="text-sm text-gray">PIN:</span>
                                            <span
                                                class="text-sm text-bold text-success">{{ $device->config->security_pin ?? ($device->security_pin ?? 'Tidak Ada') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <!-- Library QR Code Generator -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Tentukan Payload Data untuk QR Code
            let rawTunnelUrl = "{{ $device->tunnel_url ?? '' }}";
            let localIp = "{{ $device->local_ip ?? ($device->config->local_ip ?? '192.168.1.xxx') }}";
            let mode = "{{ $device->config->mode ?? ($device->mode ?? 'Online (Cloudflare Tunnel)') }}";
            let port = "{{ $device->config->port ?? ($device->port ?? '8765') }}";
            let pin = "{{ $device->config->security_pin ?? ($device->security_pin ?? '') }}";

            // Bersihkan format http/https dari tunnel url
            let cleanTunnelUrl = rawTunnelUrl.replace(/^https?:\/\//, '');

            // Logika: Kalo Online, IP nya jadi URL Cloudflare, Kalo LAN, balikin IP Lokal
            let ipDomain = (mode === 'Online (Cloudflare Tunnel)') ? cleanTunnelUrl : localIp;

            // Update teks info target di UI
            document.getElementById('qr-target-text').innerText = ipDomain;

            // Format JSON persis seperti yang dibaca sama Android App lo
            let payload = JSON.stringify({
                ip: ipDomain,
                port: port,
                pin: pin
            });

            // Render QR Code ke dalam div #qrcode-display
            new QRCode(document.getElementById("qrcode-display"), {
                text: payload,
                width: 220,
                height: 220,
                colorDark: "#0f172a",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H // High Error Correction
            });
        });

        // Copy Token Script (Sesuai aslinya)
        function copyToken(elementId) {
            var copyText = document.getElementById(elementId);
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(copyText.value);

            let btn = copyText.nextElementSibling;
            let originalText = btn.innerHTML;
            btn.innerHTML = '<i class="lni lni-checkmark"></i> Tersalin';
            btn.classList.add('btn-success', 'text-white', 'border-success');
            btn.classList.remove('btn-outline-primary');

            setTimeout(() => {
                btn.innerHTML = originalText;
                btn.classList.remove('btn-success', 'text-white', 'border-success');
                btn.classList.add('btn-outline-primary');
            }, 2000);
        }
    </script>
@endpush
