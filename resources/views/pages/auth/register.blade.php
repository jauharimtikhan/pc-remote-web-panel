@extends('layout.guest', ['title' => 'Register'])
@section('content')
    <section class="">
        <div class="signup-wrapper">
            <div class="form-wrapper">
                <h2 class="">Pendaftaran Akun <span class="fw-bold text-warning fs-20">PRAMAXX</span> Remote</h6>
                    <p class="text-sm mb-25">
                        Daftar sekali dan gunakan sepuasnya.
                        <br>
                        <small class="text-danger">*selama domain masih ready</small>
                    </p>
                    <form method="post" action="{{ route('register.post') }}">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="input-style-1">
                                    <label class="form-label">Nama Lengkap</label>
                                    <input type="text" value="{{ old('nama_lengkap') }}" name="nama_lengkap"
                                        placeholder="Masukan nama lengkap anda" />
                                    @error('nama_lengkap')
                                        <span class="text-danger mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <!-- end col -->
                            <div class="col-12">
                                <div class="input-style-1">
                                    <label class="form-label">Username</label>
                                    <input type="text" value="{{ old('username') }}" name="username"
                                        placeholder="Buat Username anda disini" />
                                    @error('username')
                                        <span class="text-danger mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <!-- end col -->
                            <div class="col-12">
                                <div class="input-style-1">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="password" id="pass"
                                        placeholder="Buat password anda" />
                                    @error('password')
                                        <span class="text-danger mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <!-- end col -->
                            <div class="col-12">
                                <div class="form-check checkbox-style mb-30">
                                    <input class="form-check-input" type="checkbox" value="" id="togglePass" />
                                    <label class="form-check-label" for="togglePass">
                                        Tampilkan Password</label>
                                </div>
                            </div>
                            <!-- end col -->
                            <div class="col-12">
                                <div class="button-group d-flex justify-content-center flex-wrap">
                                    <button class="main-btn primary-btn btn-hover w-100 text-center">
                                        Daftar Sekarang
                                    </button>
                                </div>
                            </div>
                            <div class="col-12 mt-3">
                                <div class="button-group d-flex justify-content-center flex-wrap">
                                    <p class="fw-2">Sudah punya akun? <a href="{{ route('login') }}"
                                            class="">Login</a></p>
                                </div>
                            </div>
                        </div>
                        <!-- end row -->
                    </form>
            </div>
        </div>
    </section>
@endsection


@push('js')
    <script type="text/javascript">
        $('#togglePass').change(function() {
            let isChecked = $(this).is(':checked');
            $('#pass').attr('type',
                isChecked ? "text" : "password");

        })
    </script>
@endpush
