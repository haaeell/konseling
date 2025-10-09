@extends('layouts.dashboard')

@section('title', 'Laporan Konseling')
@section('breadcumb', 'Laporan Konseling')

@section('content')
<div class="container-fluid mt-4">
    <div class="card shadow-lg border-0">
        <div class="card-header">
            <h4 class="mb-3"><i class="bi bi-clipboard-data text-primary"></i> Laporan Konseling</h4>

            {{-- Filter Bulan dan Tahun --}}
            <form action="{{ route('laporan.index') }}" method="GET" class="mb-3">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label for="month" class="form-label">Bulan</label>
                        <select id="month" name="month" class="form-select">
                            <option value="">-- Pilih Bulan --</option>
                            @foreach(range(1, 12) as $m)
                                <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                                    {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="year" class="form-label">Tahun</label>
                        <select id="year" name="year" class="form-select">
                            <option value="">-- Pilih Tahun --</option>
                            @for ($y = date('Y'); $y >= 2020; $y--)
                                <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                                    {{ $y }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Tampilkan
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="card-body">
            {{-- Statistik Utama --}}
            <div class="row text-center mb-4">
               <div class="row text-center mb-4">
    {{-- Total Konseling --}}
    <div class="col-md-4 mb-3">
        <div class="card border-primary shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-center gap-3">
                    <i class="bi bi-people-fill fs-1 text-primary"></i>
                    <div>
                        <h6 class="text-secondary mb-1">Total Konseling</h6>
                        <h2 class="fw-bold text-primary mb-0">{{ $totalKonseling }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Kategori Terbanyak --}}
    <div class="col-md-4 mb-3">
        <div class="card border-success shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-center gap-3">
                    <i class="bi bi-bar-chart-line-fill fs-1 text-success"></i>
                    <div>
                        <h6 class="text-secondary mb-1">Kategori Terbanyak</h6>
                        <h5 class="fw-bold text-success mb-0">
                            {{ $kategoriCounts->first()->nama ?? '-' }}
                        </h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Kategori Tersedikit --}}
    <div class="col-md-4 mb-3">
        <div class="card border-danger shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-center gap-3">
                    <i class="bi bi-graph-down-arrow fs-1 text-danger"></i>
                    <div>
                        <h6 class="text-secondary mb-1">Kategori Tersedikit</h6>
                        <h5 class="fw-bold text-danger mb-0">
                            {{ $kategoriCounts->last()->nama ?? '-' }}
                        </h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

            </div>

            {{-- Grafik Perbandingan Kategori --}}
            <div class="mb-5">
                <h5 class="mb-3"><i class="bi bi-bar-chart-line text-primary"></i> Grafik Jumlah Konseling per Kategori</h5>
                <canvas id="chartKategori" height="120"></canvas>
            </div>

            {{-- Tabel Daftar Kategori --}}
            <h5 class="mb-3"><i class="bi bi-list-ul text-primary"></i> Detail Kategori Konseling</h5>
            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle">
                    <thead class="table-primary">
                        <tr class="text-center">
                            <th width="10%">No</th>
                            <th>Kategori</th>
                            <th width="20%">Jumlah Konseling</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kategoriCounts as $index => $kategori)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $kategori->nama }}</td>
                                <td class="text-center fw-bold">{{ $kategori->permohonan_count }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">
                                    Tidak ada data kategori konseling.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
{{-- Chart.js CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById('chartKategori').getContext('2d');
    const chartKategori = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($kategoriCounts->pluck('nama')) !!},
            datasets: [{
                label: 'Jumlah Konseling',
                data: {!! json_encode($kategoriCounts->pluck('permohonan_count')) !!},
                backgroundColor: [
                    '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e',
                    '#e74a3b', '#858796', '#5a5c69'
                ],
                borderRadius: 5
            }]
        },
        options: {
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.parsed.y + ' konseling';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });
</script>
@endsection
