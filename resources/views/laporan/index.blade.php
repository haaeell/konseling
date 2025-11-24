@extends('layouts.dashboard')

@section('title', 'Laporan Konseling')
@section('breadcumb', 'Laporan Konseling')

@section('content')
    <div class="container-fluid mt-4">

        <div class="card shadow-lg border-0">
            <div class="card-header">
                <h4 class="mb-3"><i class="bi bi-clipboard-data text-primary"></i> Laporan Konseling</h4>

                <div class="col-md-2 mt-2">
                    <a href="{{ route('laporan.cetak-pdf', ['month' => request('month'), 'year' => request('year')]) }}"
                        class="btn btn-danger w-100" target="_blank">
                        <i class="bi bi-file-earmark-pdf"></i> Cetak PDF
                    </a>
                </div>

                {{-- FILTER --}}
                <form action="{{ route('laporan.index') }}" method="GET" class="mb-3">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Bulan</label>
                            <select name="month" class="form-select">
                                <option value="">Semua</option>
                                @foreach (range(1, 12) as $m)
                                    <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                                        {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Tahun</label>
                            <select name="year" class="form-select">
                                <option value="">Semua</option>
                                @for ($y = date('Y'); $y >= 2020; $y--)
                                    <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                                        {{ $y }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <div class="col-md-2">
                            <button class="btn btn-primary w-100 mt-4">
                                <i class="bi bi-search"></i> Tampilkan
                            </button>
                        </div>
                    </div>
                </form>


            </div>

            <div class="card-body">

                {{-- STATISTIK TOTAL --}}
                <div class="row text-center mb-4 d-flex justify-content-center">
                    <div class="col-md-4">
                        <div class="card border-danger shadow-sm">
                            <div class="card-body">
                                <i class="bi bi-people-fill fs-1 text-danger"></i>
                                <h6 class="text-secondary mt-2">Total Permohonan Konseling</h6>
                                <h2 class="fw-bold text-danger">{{ $totalPengajuanKonseling }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 ">
                        <div class="card border-primary shadow-sm">
                            <div class="card-body">
                                <i class="bi bi-people-fill fs-1 text-primary"></i>
                                <h6 class="text-secondary mt-2">Total Konseling Selesai</h6>
                                <h2 class="fw-bold text-primary">{{ $total }}</h2>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- PIE CHART --}}
                <h5 class="mb-4"><i class="bi bi-pie-chart-fill text-primary"></i> Statistik Faktor Penilaian</h5>

                <div class="row">
                    {{-- KATEGORI --}}
                    <div class="col-md-3 mb-4">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h6 class="text-center fw-bold">Kategori Masalah</h6>
                                <canvas id="pieKategori"></canvas>
                            </div>
                        </div>
                    </div>

                    {{-- URGENSI --}}
                    <div class="col-md-3 mb-4">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h6 class="text-center fw-bold">Tingkat Urgensi</h6>
                                <canvas id="pieUrgensi"></canvas>
                            </div>
                        </div>
                    </div>

                    {{-- DAMPAK --}}
                    <div class="col-md-3 mb-4">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h6 class="text-center fw-bold">Dampak Masalah</h6>
                                <canvas id="pieDampak"></canvas>
                            </div>
                        </div>
                    </div>

                    {{-- RIWAYAT --}}
                    <div class="col-md-3 mb-4">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h6 class="text-center fw-bold">Riwayat Konseling</h6>
                                <canvas id="pieRiwayat"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const colors = ['#4e73df', '#1cc88a', '#f6c23e', '#e74a3b', '#36b9cc'];

        const rekap = @json($rekap);

        function buatPie(key, id) {
            new Chart(document.getElementById(id), {
                type: 'pie',
                data: {
                    labels: Object.keys(rekap[key]),
                    datasets: [{
                        data: Object.values(rekap[key]),
                        backgroundColor: colors
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }

        buatPie('kategori', 'pieKategori');
        buatPie('urgensi', 'pieUrgensi');
        buatPie('dampak', 'pieDampak');
        buatPie('riwayat', 'pieRiwayat');
    </script>
@endsection
