<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>My Devices - {{ config('app.name') }}</title>

    <!-- Panggil CSS PlainAdmin punya lu -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/lineicons.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}" />

    <style>
        /* Paksa main-wrapper ke kiri karena ga ada sidebar */
        .main-wrapper-full {
            margin-left: 0 !important;
            min-height: 100vh;
            background: #f3f6f9;
            /* Warna background modern */
        }

        /* Bikin navbar sedikit lebih lebar */
        .header-full {
            padding: 15px 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>

<body>
    <main class="main-wrapper main-wrapper-full">
        <!-- Navbar Simple (Tanpa Sidebar Toggle) -->
        <header class="header header-full bg-white d-flex justify-content-between align-items-center">
            <div class="header-left">
                <h4 class="text-primary text-bold">
                    <i class="lni lni-cloud-network"></i> MyApp Hub
                </h4>
            </div>
            <div class="header-right">
                <div class="profile-box d-flex align-items-center">
                    <div class="profile-info text-end me-3">
                        <h6 class="text-bold">{{ Auth::user()->name }}</h6>
                        <p class="text-sm">{{ Auth::user()->email }}</p>
                    </div>
                    <!-- Form Logout -->
                    <form method="POST" action="{{ route('admin.auth.logout') }}">
                        @csrf
                        <button type="submit" class="text-danger border-0 bg-transparent" title="Logout">
                            <i class="lni lni-exit" style="font-size: 1.5rem;"></i>
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <!-- Konten Utama -->
        @yield('content')

    </main>

    <!-- Script Utama PlainAdmin -->
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    @stack('scripts')
</body>

</html>
