<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pramaxx Remote - Device Dashboard</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                        }
                    }
                }
            }
        }
    </script>
    <!-- Google Fonts Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .tab-panel {
            transition: opacity 0.3s ease-in-out;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-800 min-h-screen flex flex-col">

    <!-- Header / Navbar -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-30 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <!-- Logo & Brand -->
            <div class="flex items-center space-x-3">
                <div
                    class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center text-white shadow-md shadow-indigo-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                        </path>
                    </svg>
                </div>
                <div>
                    <h1 class="font-bold text-slate-900 text-base sm:text-lg tracking-tight">MyApp Hub</h1>
                    <p class="text-xs text-slate-500 hidden sm:block">Pusat Kontrol Device & Tunnel</p>
                </div>
            </div>

            <!-- User Profile & Logout -->
            @auth
                <div class="flex items-center space-x-3">
                    <div class="hidden sm:text-right">
                        <p class="text-sm font-semibold text-slate-800">Pramaxx</p>
                        <p class="text-xs text-slate-500">pramaxx</p>
                    </div>
                    <div
                        class="h-9 w-9 rounded-full bg-slate-200 flex items-center justify-center text-slate-700 font-bold text-sm border border-slate-300">
                        P
                    </div>
                    <a href="{{ route('admin.auth.logout') }}" title="Logout"
                        class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                            </path>
                        </svg>
                    </a>
                </div>

            @endauth
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 flex-1 w-full space-y-6">

        <!-- Welcome Banner -->
        <div
            class="bg-gradient-to-r from-indigo-600 to-indigo-800 rounded-2xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-100 relative overflow-hidden">
            <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-white/10 rounded-full blur-2xl pointer-events-none">
            </div>
            <div class="relative z-10 max-w-2xl">
                <h2 class="text-2xl sm:text-3xl font-bold tracking-tight mb-2">Halo, Pramaxx! 👋</h2>
                <p class="text-indigo-100 text-sm sm:text-base leading-relaxed">
                    Ini adalah pusat kontrol utama untuk Device dan Tunnel Anda. Kelola koneksi perangkat, pantau status
                    online, dan hubungkan aplikasi Android dengan mudah.
                </p>
            </div>
        </div>

        <!-- Dashboard Grid Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            <!-- Left Column: Informasi Device (5 Cols) -->
            <div class="lg:col-span-5 space-y-6">
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-5">

                    <!-- Card Header -->
                    <div class="flex items-center justify-between">
                        <h3 class="font-bold text-slate-900 text-base sm:text-lg">Informasi Device</h3>
                        <span
                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-2 h-2 mr-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                            Online
                        </span>
                    </div>

                    <!-- Device Meta -->
                    <div class="bg-slate-50 rounded-xl p-4 border border-slate-100 space-y-1">
                        <div class="text-xs font-medium text-slate-400 uppercase tracking-wider">Perangkat Aktif</div>
                        <h4 class="text-lg font-bold text-slate-900">Laptop Utama</h4>
                        <p class="text-xs text-slate-500 flex items-center pt-1">
                            <svg class="w-3.5 h-3.5 mr-1 text-slate-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Terakhir aktif: 1 hari yang lalu
                        </p>
                    </div>

                    <hr class="border-slate-100">

                    <!-- Tunnel URL -->
                    <div class="space-y-2">
                        <label class="text-xs font-semibold text-slate-600 block">Tunnel Public URL</label>
                        <div class="flex items-center space-x-2">
                            <div
                                class="flex-1 bg-slate-100 border border-slate-200 rounded-lg px-3 py-2 text-xs sm:text-sm font-mono text-indigo-600 truncate">
                                <a href="https://xunx.pramaxx.biz.id" target="_blank" class="hover:underline">
                                    https://xunx.pramaxx.biz.id
                                </a>
                            </div>
                            <button
                                onclick="navigator.clipboard.writeText('https://xunx.pramaxx.biz.id'); alert('URL berhasil disalin!');"
                                class="p-2 bg-slate-100 hover:bg-slate-200 border border-slate-200 rounded-lg text-slate-600 transition-colors"
                                title="Salin URL">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Instruction Note -->
                    <div
                        class="bg-indigo-50/60 border border-indigo-100 rounded-xl p-3.5 text-xs text-indigo-900 leading-relaxed">
                        <span class="font-semibold block mb-1">💡 Catatan Token:</span>
                        Gunakan token otentikasi ini pada aplikasi remote server di perangkat Anda.
                    </div>

                </div>
            </div>

            <!-- Right Column: Tabs & Interactive Panel (7 Cols) -->
            <div class="lg:col-span-7">
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">

                    <!-- Tab Navigation Header -->
                    <div class="border-b border-slate-200 bg-slate-50/50">
                        <nav class="flex space-x-2 p-2 sm:px-6" aria-label="Tabs" role="tablist">
                            <!-- Tab Button 1 -->
                            <button onclick="switchTab('tab-control')" id="btn-tab-control" role="tab"
                                aria-selected="true"
                                class="tab-btn flex-1 sm:flex-initial px-4 py-2.5 text-xs sm:text-sm font-medium rounded-xl transition-all duration-200 flex items-center justify-center space-x-2 border border-indigo-600 text-indigo-600 bg-indigo-50/80 shadow-xs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span>Kontrol & Statistik</span>
                            </button>

                            <!-- Tab Button 2 -->
                            <button onclick="switchTab('tab-android')" id="btn-tab-android" role="tab"
                                aria-selected="false"
                                class="tab-btn flex-1 sm:flex-initial px-4 py-2.5 text-xs sm:text-sm font-medium rounded-xl transition-all duration-200 flex items-center justify-center space-x-2 border border-transparent text-slate-500 hover:text-slate-700 hover:bg-slate-100">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <span>Hubungkan Android</span>
                            </button>
                        </nav>
                    </div>

                    <!-- Tab Content Panels -->
                    <div class="p-6">

                        <!-- Panel 1: Kontrol & Statistik -->
                        <div id="tab-control" role="tabpanel" class="tab-panel space-y-6">
                            <div>
                                <h4 class="font-bold text-slate-900 text-base mb-1">Status Sistem & Perangkat</h4>
                                <p class="text-xs text-slate-500">Monitor penggunaan sumber daya dan lakukan manajemen
                                    sesi perangkat dari jarak jauh.</p>
                            </div>

                            <!-- Stats Cards Grid -->
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                                    <div class="text-xs text-slate-500 font-medium">CPU Usage</div>
                                    <div class="text-xl font-bold text-slate-900 mt-1">14.2%</div>
                                    <div class="w-full bg-slate-200 h-1.5 rounded-full mt-2 overflow-hidden">
                                        <div class="bg-indigo-600 h-full rounded-full" style="width: 14.2%"></div>
                                    </div>
                                </div>
                                <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                                    <div class="text-xs text-slate-500 font-medium">RAM Memory</div>
                                    <div class="text-xl font-bold text-slate-900 mt-1">3.4 / 8 GB</div>
                                    <div class="w-full bg-slate-200 h-1.5 rounded-full mt-2 overflow-hidden">
                                        <div class="bg-emerald-500 h-full rounded-full" style="width: 42.5%"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Quick Buttons -->
                            <div class="space-y-2">
                                <span class="text-xs font-semibold text-slate-600 block">Aksi Cepat</span>
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                                    <button onclick="alert('Perintah restart tunnel dikirim!')"
                                        class="p-3 bg-slate-50 hover:bg-indigo-50 border border-slate-200 hover:border-indigo-200 rounded-xl text-xs font-medium text-slate-700 hover:text-indigo-700 transition-all flex items-center justify-center space-x-2">
                                        <span>🔄 Restart Tunnel</span>
                                    </button>
                                    <button onclick="alert('Log diperbarui!')"
                                        class="p-3 bg-slate-50 hover:bg-indigo-50 border border-slate-200 hover:border-indigo-200 rounded-xl text-xs font-medium text-slate-700 hover:text-indigo-700 transition-all flex items-center justify-center space-x-2">
                                        <span>📋 Refresh Log</span>
                                    </button>
                                    <button onclick="alert('Sesi diamankan!')"
                                        class="p-3 bg-slate-50 hover:bg-red-50 border border-slate-200 hover:border-red-200 rounded-xl text-xs font-medium text-slate-700 hover:text-red-700 transition-all flex items-center justify-center space-x-2 col-span-2 sm:col-span-1">
                                        <span>🔒 Lock Sesi</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Panel 2: Scan QR Android -->
                        <div id="tab-android" role="tabpanel" class="tab-panel space-y-6 hidden">
                            <div class="text-center sm:text-left">
                                <h5 class="font-bold text-slate-900 text-base mb-1">Scan untuk Menghubungkan Android
                                </h5>
                                <p class="text-xs text-slate-500 leading-relaxed max-w-lg">
                                    Gunakan fitur <b>Scan QR</b> di aplikasi Android Pramaxx Remote untuk terhubung
                                    otomatis tanpa perlu mengetik manual.
                                </p>
                            </div>

                            <!-- QR Code Mockup container -->
                            <div
                                class="flex flex-col sm:flex-row items-center gap-6 bg-slate-50 p-5 rounded-2xl border border-slate-100">
                                <div
                                    class="bg-white p-3 rounded-xl border border-slate-200 shadow-xs flex items-center justify-center">
                                    <!-- Simulated SVG QR Code -->
                                    <svg class="w-32 h-32 text-slate-800" viewBox="0 0 24 24" fill="currentColor">
                                        <path
                                            d="M2 2h8v8H2V2zm2 2v4h4V4H4zm12-2h8v8h-8V2zm2 2v4h4V4h-4zM2 14h8v8H2v-8zm2 2v4h4v-4H4zm14 2h2v2h-2v-2zm-4-4h2v2h-2v-2zm4 4h4v4h-4v-4zm-4 4h2v2h-2v-2zm6-6h2v2h-2v-2z" />
                                    </svg>
                                </div>

                                <div class="flex-1 w-full space-y-3">
                                    <div
                                        class="flex justify-between items-center bg-white px-3.5 py-2 rounded-lg border border-slate-200 text-xs">
                                        <span class="text-slate-500 font-medium">Target:</span>
                                        <span
                                            class="font-mono font-bold text-slate-800 truncate ml-2">xunx.pramaxx.biz.id</span>
                                    </div>
                                    <div
                                        class="flex justify-between items-center bg-white px-3.5 py-2 rounded-lg border border-slate-200 text-xs">
                                        <span class="text-slate-500 font-medium">Port:</span>
                                        <span class="font-mono font-bold text-slate-800">8765</span>
                                    </div>
                                    <div
                                        class="flex justify-between items-center bg-white px-3.5 py-2 rounded-lg border border-slate-200 text-xs">
                                        <span class="text-slate-500 font-medium">PIN:</span>
                                        <span class="font-mono font-bold text-indigo-600 tracking-wider">017058</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>

    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-4 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-xs text-slate-400">
            &copy; 2026 Pramaxx Remote Hub. All rights reserved.
        </div>
    </footer>

    <!-- Tab Switcher Script (Smooth Transition) -->
    <script>
        function switchTab(tabId) {
            // Hide all tab panels
            document.querySelectorAll('.tab-panel').forEach(panel => {
                panel.classList.add('hidden');
            });

            // Reset all button styles
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('border-indigo-600', 'text-indigo-600', 'bg-indigo-50/80', 'shadow-xs');
                btn.classList.add('border-transparent', 'text-slate-500', 'hover:text-slate-700',
                    'hover:bg-slate-100');
                btn.setAttribute('aria-selected', 'false');
            });

            // Show selected panel
            document.getElementById(tabId).classList.remove('hidden');

            // Activate selected button style
            const activeBtn = document.getElementById('btn-' + tabId);
            activeBtn.classList.remove('border-transparent', 'text-slate-500', 'hover:text-slate-700',
                'hover:bg-slate-100');
            activeBtn.classList.add('border-indigo-600', 'text-indigo-600', 'bg-indigo-50/80', 'shadow-xs');
            activeBtn.setAttribute('aria-selected', 'true');
        }
    </script>
</body>

</html>
