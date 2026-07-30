@extends('layout.auth', ['Update Pengguna'])

@section('content')
    <div class="title-wrapper pt-30">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="title">
                    <h2>Edit User: {{ $user->name }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card-style mb-30">
                <form action="{{ route('admin.pengguna.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="input-style-1">
                        <label>Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required />
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="input-style-1">
                        <label>Email</label>
                        <input type="text" name="username" value="{{ old('username', $user->username) }}" required />
                        @error('username')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="input-style-1">
                        <label>Password (Kosongkan jika tidak ingin diubah)</label>
                        <input type="password" name="password" placeholder="Password baru" />
                        @error('password')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="select-style-1">
                        <label>Status</label>
                        <div class="select-position">
                            <select name="status" required>
                                <option value="active" {{ $user->status === 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="not_active" {{ $user->status === 'not_active' ? 'selected' : '' }}>Non-Aktif
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="main-btn primary-btn btn-hover">Update</button>
                        <a href="{{ route('admin.pengguna.index') }}" class="main-btn light-btn btn-hover">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
