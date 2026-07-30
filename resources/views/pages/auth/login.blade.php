@extends('layout.guest', ['title' => 'Login'])
@section('content')
    <section class="">
        <div class="signup-wrapper">
            <div class="form-wrapper">
                <h2 class="">Login Akun <span class="fw-bold text-warning fs-20">PRAMAXX</span> Remote</h6>
                    <p class="text-sm mb-25">
                        Daftar sekali dan gunakan sepuasnya.
                        <br>
                        <small class="text-danger">*selama domain masih ready</small>
                    </p>
                    <form action="{{ route('login.post') }}" method="post">
                        @csrf
                        <div class="row">
                            <!-- end col -->
                            <div class="col-12">
                                <div class="input-style-1">
                                    <label class="form-label">Username</label>
                                    <input type="text" name="username" value="{{ old('username') }}"
                                        placeholder="Buat Username anda disini" />
                                    @error('username')
                                        <span class="text-danger">{{ $message }}</span>
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
                                        <span class="text-danger">{{ $message }}</span>
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
                                    <button type="submit" class="main-btn primary-btn btn-hover w-100 text-center">
                                        Login
                                    </button>
                                </div>
                            </div>
                            <div class="col-12 mt-3">
                                <div class="button-group d-flex justify-content-center flex-wrap">
                                    <p class="fw-2">Belum punya akun? <a href="{{ route('register') }}"
                                            class="">Daftar Dulu</a></p>
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
