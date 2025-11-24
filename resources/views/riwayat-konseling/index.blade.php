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
                                    <td>
                                        <strong>{{ $jadwal->skor_prioritas }}</strong>
                                        <div class="text-muted small">
                                            <div>Urgensi: {{ $jadwal->tingkat_urgensi_label }}
                                                ({{ $jadwal->tingkat_urgensi_skor }})
                                            </div>
                                            <div>Dampak: {{ $jadwal->dampak_masalah_label }}
                                                ({{ $jadwal->dampak_masalah_skor }})</div>
                                            <div>Kategori: {{ $jadwal->kategori_masalah_label }}
                                                ({{ $jadwal->kategori_masalah_skor }})</div>
                                            <div>Riwayat: {{ $jadwal->riwayat_konseling_label }}
                                                ({{ $jadwal->riwayat_konseling_skor }})</div>
                                        </div>
                                    </td>

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
