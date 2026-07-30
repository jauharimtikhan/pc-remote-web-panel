@extends('layout.auth', ['title' => 'Tambah'])

@section('content')
    <div class="title-wrapper pt-30">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="title">
                    <h2>Tambah User Baru</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card-style mb-30">
                <form action="{{ route('admin.pengguna.store') }}" method="POST">
                    @csrf

                    <div class="input-style-1">
                        <label>Nama Lengkap</label>
                        <input type="text" name="name" placeholder="John Doe" value="{{ old('name') }}" required />
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="input-style-1">
                        <label>Username</label>
                        <input type="test" name="username" placeholder="john123" value="{{ old('username') }}"
                            required />
                        @error('username')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="input-style-1">
                        <label>Password</label>
                        <input type="password" name="password" placeholder="Minimal 8 karakter" required />
                        @error('password')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="select-style-1">
                        <label>Status</label>
                        <div class="select-position">
                            <select name="status" required>
                                <option value="active">Aktif</option>
                                <option value="not_active">Non-Aktif</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="main-btn primary-btn btn-hover">Simpan</button>
                        <a href="{{ route('admin.pengguna.index') }}" class="main-btn light-btn btn-hover">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
