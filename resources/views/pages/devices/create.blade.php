@extends('layout.auth', ['title' => 'Tambah Device'])

@section('content')
    <div class="title-wrapper pt-30">
        <div class="title">
            <h2>Register Device Baru</h2>
        </div>
    </div>
    @if (session('error'))
        <div class="alert-box danger-alert alert-dismissible fade show mb-30" role="alert">
            <div class="alert">
                <p class="text-medium">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-12">
            <div class="card-style mb-30">
                <form action="{{ route('admin.devices.store') }}" method="POST">
                    @csrf

                    <div class="select-style-1">
                        <label>Pilih User Pemilik</label>
                        <div class="select-position">
                            <select name="user_id" required>
                                <option value="">-- Pilih User --</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->username }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="input-style-1">
                        <label>Nama Device</label>
                        <input type="text" name="device_name" placeholder="Contoh: Raspberry Pi - Cabang 1"
                            value="{{ old('device_name') }}" required />
                    </div>

                    <div class="alert-box primary-alert">
                        <div class="alert">
                            <p class="text-sm">Proses ini akan otomatis membuat subdomain 4 karakter random dan
                                mendaftarkan Zero Trust Tunnel ke Cloudflare.</p>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="main-btn primary-btn btn-hover">Generate & Simpan</button>
                        <a href="{{ route('admin.devices.index') }}" class="main-btn light-btn btn-hover">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
