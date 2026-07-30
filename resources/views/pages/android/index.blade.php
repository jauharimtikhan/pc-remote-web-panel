@extends('layout.auth', ['title' => 'Manajemen Android Release'])

@section('content')
    <div class="title-wrapper pt-30">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="title">
                    <h2>Daftar Android Release</h2>
                </div>
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
                                <h6>Versi</h6>
                            </th>
                            <th>
                                <h6>Bundle Url</h6>
                            </th>
                            <th>
                                <h6>Rilis</h6>
                            </th>
                            <th>
                                <h6>Aksi</h6>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($releases as $release)
                            <tr id="row-{{ $release->id }}">
                                <td>
                                    <p class="text-bold">{{ $release->version }}</p>
                                </td>
                                <td>
                                    <p class="d-inline-block text-truncate" style="max-width: 150px;">
                                        {{ $release->bundle_url }}</p>
                                </td>
                                <td>
                                    {{ $release->created_at->diffForHumans() }}
                                </td>

                                <td>
                                    <div class="action">
                                        <button class="text-danger border-0 bg-transparent btn-delete"
                                            data-id="{{ $release->id }}">
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

                        fetch(`/admin/android-release/${id}`, {
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
