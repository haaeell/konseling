@extends('layouts.dashboard')

@section('title', 'Riwayat Konseling')
@section('breadcumb', 'Riwayat Konseling')
@section('content')
    <div class="container mt-5">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="datatableRiwayat">
                        <thead>
                            <tr>
                                <th>Siswa</th>
                                <th>Kategori</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Deskripsi Permasalahan</th>
                                <th>Status</th>
                                <th>Rangkuman</th>
                                <th>Skor Prioritas</th>
                            </tr>
                        </thead>
                        <tbody id="riwayatKonselingTable">
                            @foreach ($riwayatKonseling as $jadwal)
                                <tr data-id="{{ $jadwal->id }}">
                                    <td>{{ $jadwal->siswa->user->name }}</td>
                                    <td>{{ $jadwal->kategori->nama }}</td>
                                    <td>{{ \Carbon\Carbon::parse($jadwal->tanggal_pengajuan)->format('d-m-Y') }}</td>
                                    <td>{{ Str::limit($jadwal->deskripsi_permasalahan, 50) }}</td>
                                    <td>
                                        <span
                                            class="badge
                                            {{ $jadwal->status === 'menunggu'
                                                ? 'bg-warning'
                                                : ($jadwal->status === 'disetujui'
                                                    ? 'bg-success'
                                                    : ($jadwal->status === 'selesai'
                                                        ? 'bg-primary'
                                                        : 'bg-danger')) }}">
                                            {{ ucfirst($jadwal->status) }}
                                        </span>

                                        @if ($jadwal->status === 'ditolak' && $jadwal->alasan_penolakan)
                                            <div class="mt-1 small text-danger">
                                                <i class="bi bi-info-circle"></i> {{ $jadwal->alasan_penolakan }}
                                            </div>
                                        @endif
                                    </td>
                                    <td>{{ Str::limit($jadwal->rangkuman, 50) }}</td>
                                    <td>{{ $jadwal->skor_prioritas }}</td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#datatableRiwayat').DataTable({
                order: [
                    [5, 'desc']
                ],
                columnDefs: [{
                    orderable: false,
                    targets: -1
                }]
            });
        });
    </script>
@endsection
