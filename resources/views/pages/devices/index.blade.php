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
                                            onclick="navigator.clipboard.writeText('{{ $device->web_token }}'); TOAST('success','Token disalin!')"
                                            class="text-success me-3 bg-transparent border-0" title="Copy Token">
                                            <i class="lni lni-clipboard"></i>
                                        </button>
                                        <button
                                            onclick="navigator.clipboard.writeText('{{ route('user.not-auth', $device->id) }}'); TOAST('success','Guest URL disalin!')"
                                            class="text-success me-3 bg-transparent border-0" title="Copy Guest Url">
                                            <svg width="20" height="20" viewBox="0 0 25 25" fill="none"
                                                xmlns="http://www.w3.org/2000/svg" transform="rotate(0 0 0)">
                                                <path
                                                    d="M13.516 12.6289L14.5336 13.6465C15.5347 14.6476 17.1579 14.6476 18.159 13.6465L21.7492 10.0563C22.7503 9.05519 22.7503 7.43207 21.7492 6.43096L18.1215 2.80332C17.1204 1.80221 15.4973 1.80221 14.4962 2.80332L10.906 6.39351C9.90489 7.39462 9.90489 9.01774 10.906 10.0189L11.9251 11.0379L11.4815 11.4814L10.4623 10.4622C9.4612 9.46111 7.83808 9.46111 6.83697 10.4622L3.25083 14.0484C2.24972 15.0495 2.24972 16.6726 3.25083 17.6737L6.8785 21.3014C7.87961 22.3025 9.50273 22.3025 10.5038 21.3014L14.09 17.7152C15.0911 16.7141 15.0911 15.091 14.09 14.0899L13.0725 13.0724L13.516 12.6289ZM16.5305 4.39431L20.1582 8.02195C20.2806 8.14438 20.2806 8.34288 20.1582 8.46531L16.568 12.0555C16.4456 12.1779 16.2471 12.1779 16.1246 12.0555L15.107 11.0379L16.0123 10.1327C16.4516 9.69331 16.4516 8.981 16.0123 8.54166C15.573 8.10232 14.8606 8.10232 14.4213 8.54166L13.516 9.44692L12.497 8.42786C12.3746 8.30543 12.3746 8.10693 12.497 7.9845L16.0872 4.39431C16.2096 4.27188 16.4081 4.27188 16.5305 4.39431ZM11.4815 14.6634L12.499 15.6809C12.6214 15.8033 12.6214 16.0018 12.499 16.1242L8.91285 19.7104C8.79042 19.8328 8.59192 19.8328 8.46949 19.7104L4.84182 16.0827C4.71939 15.9603 4.71939 15.7618 4.84182 15.6393L8.42796 12.0532C8.55039 11.9308 8.74889 11.9308 8.87132 12.0532L9.89054 13.0724L8.99636 13.9666C8.55702 14.4059 8.55702 15.1183 8.99636 15.5576C9.4357 15.9969 10.148 15.9969 10.5873 15.5576L11.4815 14.6634Z"
                                                    fill="#343C54" />
                                            </svg>
                                        </button>
                                        <a href="{{ route('admin.devices.edit', ['device' => $device->id]) }}"
                                            class="text-primary me-3">
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

@push('js')
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
