@extends('layout.auth', ['title' => 'Update Data Device'])

@section('content')
    <div class="title-wrapper pt-30">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="title">
                    <h2>Edit Device: {{ $device->device_name }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card-style mb-30">
                <form action="{{ route('admin.devices.update', $device->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="select-style-1">
                        <label>Pilih User Pemilik</label>
                        <div class="select-position">
                            <select name="user_id" required>
                                <option value="">-- Pilih User --</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ $device->user_id == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('user_id')
                            <span class="text-danger mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="input-style-1">
                        <label>Nama Device</label>
                        <input type="text" name="device_name" placeholder="Contoh: Desktop PC - Utama"
                            value="{{ old('device_name', $device->device_name) }}" required />
                        @error('device_name')
                            <span class="text-danger mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Sekedar info, field ini didisable karena terikat dengan CF -->
                    <div class="input-style-1">
                        <label>Tunnel URL (Hanya Baca)</label>
                        <input type="text" value="{{ $device->tunnel_url }}" class="bg-light text-muted" disabled />
                        <small class="text-gray mt-1">URL Tunnel tidak bisa diubah karena sudah terdaftar di
                            Cloudflare.</small>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="main-btn primary-btn btn-hover">Update Device</button>
                        <a href="{{ route('admin.devices.index') }}" class="main-btn light-btn btn-hover">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
