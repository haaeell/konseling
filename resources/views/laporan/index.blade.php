@extends('layouts.dashboard')

@section('title', 'Laporan Konseling Siswa')
@section('breadcumb', 'Laporan Konseling Siswa')

@section('content')
    <div class="container-fluid mt-4">

        <div class="card shadow-sm border-0">
            <div class="card-header">
                <h4 class="mb-3">
                    <i class="bi bi-journal-text text-primary"></i> Laporan Konseling Siswa
                </h4>

                @if (!Auth::user()->isOrangTua())
                    <form action="{{ route('laporan.index') }}" method="GET" class="row g-3">

                        <div class="col-md-2">
                            <label class="form-label">Tahun Akademik</label>
                            <select name="tahun_akademik" class="form-select" required>
                                @foreach ($tahunAjaranList as $th)
                                    <option value="{{ $th->id }}"
                                        {{ $request->tahun_akademik == $th->id ? 'selected' : '' }}>
                                        {{ $th->tahun }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Kelas</label>
                            <select name="kelas" class="form-select">
                                <option value="">Semua Kelas</option>
                                @foreach ($kelasList as $k)
                                    <option value="{{ $k->id }}" {{ $request->kelas == $k->id ? 'selected' : '' }}>
                                        {{ $k->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100" target="_blank">
                                <i class="bi bi-search"></i> Cari
                            </button>
                        </div>

                        <div class="col-md-2 d-flex align-items-end">
                            <a href="{{ route('laporan.pdf', request()->all()) }}" class="btn btn-danger w-100" target="_blank">
                                <i class="bi bi-file-earmark-pdf"></i> Cetak
                            </a>
                        </div>
                    </form>
                @else
                    <form action="{{ route('laporan.index') }}" method="GET" class="row g-3">
                        <div class="col-md-2 d-flex align-items-end">
                            <a href="{{ route('laporan.pdf', request()->all()) }}" class="btn btn-danger w-100" target="_blank">
                                <i class="bi bi-file-earmark-pdf"></i> Cetak
                            </a>
                        </div>
                    </form>
                @endif
            </div>

            <div class="card-body">

                <h5 class="fw-bold mb-3">Data Konseling Siswa</h5>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Tanggal Konseling</th>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                                <th>Masalah</th>
                                <th>Tempat</th>
                                <th>Penyelesaian</th>
                                <th>Konselor</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($laporan as $index => $row)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $row->tanggal_disetujui ? \Carbon\Carbon::parse($row->tanggal_disetujui)->format('d-m-Y') : '-' }}</td>
                                    <td>{{ $row->siswa->user->name }}</td>
                                    <td>{{ $row->siswa->kelas->nama }}</td>
                                    <td>
                                        <small>{{ $row->deskripsi_permasalahan }}</small>
                                        <br>
                                        @if ($row->kategori_masalah_label)
                                            <span class="badge bg-primary">
                                                {{ $row->kategori_masalah_label }}
                                            </span>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                    <td><small>{{ $row->tempat ?? '-' }}</small></td>
                                    <td><small>{{ $row->rangkuman ?? '-' }}</small></td>
                                    <td>{{ $row->nama_konselor ?? '-' }}</td>
                                    <td>
                                        <span class="badge {{ $row->status === 'selesai' ? 'bg-success' : ($row->status === 'ditolak' ? 'bg-danger' : ($row->status === 'disetujui' ? 'bg-info' : 'bg-warning')) }}">
                                            {{ ucfirst($row->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach

                            @if ($laporan->isEmpty())
                                <tr>
                                    <td colspan="9" class="text-center text-muted">
                                        Tidak ada data konseling.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
