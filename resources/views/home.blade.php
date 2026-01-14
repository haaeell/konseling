@extends('layouts.dashboard')

@section('content')
    <div class="container">

        <div class="row">
            <!-- Stats Cards -->
            <div class="col-6 col-lg-3 col-md-6">
                <div class="card shadow-lg">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                <div class="stats-icon green mb-2">
                                    <i class="iconly-boldAdd-User"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Total Siswa</h6>
                                <h6 class="font-extrabold mb-0">{{ $countSiswa }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 col-md-6">
                <div class="card shadow-lg">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                <div class="stats-icon yellow mb-2">
                                    <i class="iconly-boldUser"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Total Guru</h6>
                                <h6 class="font-extrabold mb-0">{{ $countGuru }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 col-md-6">
                <div class="card  shadow-lg">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                <div class="stats-icon blue mb-2">
                                    <i class="iconly-boldCredit-Card"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Total Walikelas</h6>
                                <h6 class="font-extrabold mb-0">{{ $countWalikelas }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 col-md-6">
                <div class="card  shadow-lg">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                <div class="stats-icon blue mb-2">
                                    <i class="iconly-boldCredit-Card"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Total Guru BK</h6>
                                <h6 class="font-extrabold mb-0">{{ $countGuruBk }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notifikasi & Jadwal Tugas Siswa -->
        <!--<div class="row mt-4">
            <div class="col-md-6 mb-3">
                <div class="card shadow-lg h-100">
                    <div class="card-header bg-primary text-white">
                        <i class="bi bi-bell"></i> Notifikasi
                    </div>
                    <div class="card-body">
                        {{-- Contoh notifikasi, ganti dengan @foreach jika dinamis --}}
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">Belum ada notifikasi baru.</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card shadow-lg h-100">
                    <div class="card-header bg-success text-white">
                        <i class="bi bi-calendar-event"></i> Jadwal Tugas
                    </div>
                    <div class="card-body">
                        {{-- Contoh jadwal tugas, ganti dengan @foreach jika dinamis --}}
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">Tidak ada tugas yang akan datang.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>-->

        <!-- Bar Chart -->
        <!-- <div class="row mt-4">
                <div class="col-12">
                    <div class="card shadow-lg">
                        <div class="card-header">
                            <h5 class="mb-0">Statistik Pemesanan Bulanan</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="barChart"></canvas>
                        </div>
                    </div>
                </div>
            </div> -->

    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('barChart').getContext('2d');
        const barChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Pemesanan',
                    data: [12, 19, 3, 5, 2, 3, 7, 6, 8, 10, 4, 9],
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>
@endsection
