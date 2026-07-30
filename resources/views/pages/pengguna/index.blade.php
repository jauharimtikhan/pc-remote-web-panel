@extends('layout.auth', ['title' => 'Manajemen Pengguna'])

@section('content')
    <div class="title-wrapper pt-30">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="title">
                    <h2>Daftar User</h2>
                </div>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="{{ route('admin.pengguna.create') }}" class="main-btn primary-btn btn-hover btn-sm">
                    <i class="lni lni-plus mr-5"></i> Tambah User
                </a>
            </div>
        </div>
    </div>

    <!-- Alert Success -->
    @if (session('success'))
        <div class="alert-box success-alert alert-dismissible fade show" role="alert">
            <div class="alert">
                <p class="text-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <div class="tables-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <div class="card-style mb-30">
                    <div class="table-wrapper table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>
                                        <h6>No</h6>
                                    </th>
                                    <th>
                                        <h6>Nama</h6>
                                    </th>
                                    <th>
                                        <h6>Role</h6>
                                    </th>
                                    <th>
                                        <h6>Status</h6>
                                    </th>
                                    <th>
                                        <h6>Aksi</h6>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr id="row-{{ $user->id }}">
                                        <td>
                                            <p>{{ $loop->iteration }}</p>
                                        </td>
                                        <td>
                                            <p>{{ $user->role }}</p>
                                        </td>
                                        <td>
                                            <p>{{ $user->username }}</p>
                                        </td>
                                        <td>
                                            @if ($user->status === 'active')
                                                <span class="status-btn success-btn">Aktif</span>
                                            @else
                                                <span class="status-btn close-btn">Non-Aktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="action">
                                                <a href="{{ route('admin.pengguna.edit', $user->id) }}"
                                                    class="text-primary me-3">
                                                    <i class="lni lni-pencil"></i>
                                                </a>
                                                <!-- Tombol hapus via AJAX -->
                                                <button class="text-danger border-0 bg-transparent btn-delete"
                                                    data-id="{{ $user->id }}" data-name="{{ $user->name }}">
                                                    <i class="lni lni-trash-can"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const deleteButtons = document.querySelectorAll('.btn-delete');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const userId = this.getAttribute('data-id');
                    const userName = this.getAttribute('data-name');
                    const row = document.getElementById(`row-${userId}`);

                    Swal.fire({
                        title: 'Yakin mau hapus?',
                        text: `User ${userName} bakal dihapus permanen nih!`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Proses AJAX Fetch
                            fetch(`/admin/pengguna/${userId}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Content-Type': 'application/json'
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.status === 'success') {
                                        Swal.fire(
                                            'Dihapus!',
                                            data.message,
                                            'success'
                                        );
                                        // Hapus baris dari tabel dengan animasi transisi
                                        row.style.transition = "all 0.5s ease";
                                        row.style.opacity = 0;
                                        setTimeout(() => row.remove(), 500);
                                    } else {
                                        Swal.fire('Error!', 'Gagal menghapus data.',
                                            'error');
                                    }
                                })
                                .catch(error => {
                                    Swal.fire('Error!', 'Terjadi kesalahan sistem.',
                                        'error');
                                });
                        }
                    });
                });
            });
        });
    </script>
@endpush
