@extends('layout.auth', ['title' => 'Manajemen Device'])

@section('content')
    <div class="title-wrapper pt-30">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="title">
                    <h2>Daftar Device & Tunnel</h2>
                </div>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="{{ route('admin.devices.create') }}" class="main-btn primary-btn btn-hover btn-sm">
                    <i class="lni lni-plus mr-5"></i> Tambah Device
                </a>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert-box success-alert alert-dismissible fade show" role="alert">
            <p class="text-medium">{{ session('success') }}</p>
        </div>
    @endif
    @if (session('error'))
        <div class="alert-box danger-alert alert-dismissible fade show" role="alert">
            <p class="text-medium">{{ session('error') }}</p>
        </div>
    @endif

    <div class="tables-wrapper">
        <div class="card-style mb-30">
            <div class="table-wrapper table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>
                                <h6>Device / User</h6>
                            </th>
                            <th>
                                <h6>Tunnel URL</h6>
                            </th>
                            <th>
                                <h6>Status</h6>
                            </th>
                            <th>
                                <h6>Last Seen</h6>
                            </th>
                            <th>
                                <h6>Aksi</h6>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($devices as $device)
                            <tr id="row-{{ $device->id }}">
                                <td>
                                    <p class="text-bold">{{ $device->device_name }}</p>
                                    <small class="text-gray">{{ $device->user->name ?? 'Unknown User' }}</small>
                                </td>
                                <td>
                                    <a href="{{ $device->tunnel_url }}" target="_blank"
                                        class="text-primary text-decoration-underline">
                                        {{ str_replace('https://', '', $device->tunnel_url) }}
                                    </a>
                                </td>
                                <td>
                                    @if ($device->is_online)
                                        <span class="status-btn success-btn btn-sm">Online</span>
                                    @else
                                        <span class="status-btn close-btn btn-sm">Offline</span>
                                    @endif
                                </td>
                                <td>
                                    <p class="text-sm">
                                        {{ $device->last_seen_at ? $device->last_seen_at->diffForHumans() : 'Belum pernah' }}
                                    </p>
                                </td>
                                <td>
                                    <div class="action">
                                        <!-- Tombol Copy Token -->
                                        <button
                                            onclick="navigator.clipboard.writeText('{{ $device->hash_token }}'); TOAST('success','Token disalin!')"
                                            class="text-success me-3 bg-transparent border-0" title="Copy Token">
                                            <i class="lni lni-clipboard"></i>
                                        </button>
                                        <a href="{{ route('admin.devices.edit', $device->id) }}" class="text-primary me-3">
                                            <i class="lni lni-pencil"></i>
                                        </a>
                                        <button class="text-danger border-0 bg-transparent btn-delete"
                                            data-id="{{ $device->id }}">
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
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const row = document.getElementById(`row-${id}`);

                Swal.fire({
                    title: 'Hapus Device & Tunnel?',
                    text: "Data akan dihapus beserta konfigurasi DNS dan Tunnel di Cloudflare secara permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hancurkan!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Menghapus...',
                            allowOutsideClick: false,
                            didOpen: () => Swal.showLoading()
                        });

                        fetch(`/admin/devices/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            }
                        }).then(res => res.json()).then(data => {
                            if (data.status === 'success') {
                                Swal.fire('Dihapus!', data.message, 'success');
                                row.remove();
                            } else {
                                Swal.fire('Gagal!', data.message, 'error');
                            }
                        }).catch(() => Swal.fire('Error!', 'Koneksi ke server gagal.', 'error'));
                    }
                });
            });
        });
    </script>
@endpush
