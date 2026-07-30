@extends('layout.auth', ['title' => 'Beranda'])

@section('content')
    <!-- ========== TITLE WRAPPER ========== -->
    <div class="title-wrapper pt-30">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="title">
                    <h2>Dashboard Statistik</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- ========== METRIC CARDS ========== -->
    <div class="row">
        <div class="col-xl-4 col-lg-4 col-sm-6">
            <div class="icon-card mb-30">
                <div class="icon purple">
                    <i class="lni lni-users"></i>
                </div>
                <div class="content">
                    <h6 class="mb-10">Total User</h6>
                    <h3 class="text-bold mb-10">{{ number_format($totalUsers) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-sm-6">
            <div class="icon-card mb-30">
                <div class="icon success">
                    <i class="lni lni-user"></i>
                </div>
                <div class="content">
                    <h6 class="mb-10">User Aktif</h6>
                    <h3 class="text-bold mb-10">{{ number_format($activeUsers) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-sm-6">
            <div class="icon-card mb-30">
                <div class="icon primary">
                    <i class="lni lni-laptop-phone"></i>
                </div>
                <div class="content">
                    <h6 class="mb-10">Total Device</h6>
                    <h3 class="text-bold mb-10">{{ number_format($totalDevices) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- ========== CHARTS SECTION ========== -->
    <div class="row">
        <!-- Bar Chart -->
        <div class="col-lg-8">
            <div class="card-style mb-30">
                <div class="title d-flex flex-wrap justify-content-between align-items-center">
                    <div class="left">
                        <h6 class="text-medium mb-30">Perbandingan Jumlah Data (Bar Chart)</h6>
                    </div>
                </div>
                <div class="chart">
                    <canvas id="barChart" style="width: 100%; height: 350px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Doughnut Chart -->
        <div class="col-lg-4">
            <div class="card-style mb-30">
                <div class="title d-flex flex-wrap justify-content-between align-items-center">
                    <div class="left">
                        <h6 class="text-medium mb-30">Komposisi (Doughnut)</h6>
                    </div>
                </div>
                <div class="chart">
                    <canvas id="doughnutChart" style="width: 100%; height: 350px;"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <!-- Pastikan Chart.js udah di-load dari bundle PlainAdmin atau via CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const rawData = @json($chartData);

            const colors = [
                'rgba(155, 81, 224, 0.8)', // Purple (Total User)
                'rgba(33, 150, 83, 0.8)', // Green (User Aktif)
                'rgba(47, 128, 237, 0.8)' // Blue (Total Device)
            ];

            // 1. Bar Chart Setup
            const barCtx = document.getElementById('barChart').getContext('2d');
            new Chart(barCtx, {
                type: 'bar',
                data: {
                    labels: rawData.labels,
                    datasets: [{
                        label: 'Jumlah',
                        data: rawData.data,
                        backgroundColor: colors,
                        borderRadius: 6,
                        barThickness: 40
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // 2. Doughnut Chart Setup
            const doughnutCtx = document.getElementById('doughnutChart').getContext('2d');
            new Chart(doughnutCtx, {
                type: 'doughnut',
                data: {
                    labels: rawData.labels,
                    datasets: [{
                        data: rawData.data,
                        backgroundColor: colors,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        });
    </script>
@endpush
