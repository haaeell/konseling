@extends('layouts.dashboard')

@section('title', 'jadwal Konseling')
@section('breadcumb', 'jadwal Konseling')
@section('content')
    <div class="container mt-5">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="datatableJadwal">
                        <thead>
                            <tr>
                                <th>Siswa</th>
                                <th>Kategori</th>
                                <th>Jadwal Konseling</th>
                                <th>Deskripsi Permasalahan</th>
                                <th>Status</th>
                                <th>Skor Prioritas</th>
                                @if (auth()->user()->role === 'guru' && auth()->user()->guru && auth()->user()->guru->role_guru === 'bk')
                                    <th>Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody id="jadwalKonselingTable">
                            @foreach ($jadwalKonseling as $jadwal)
                                <tr data-id="{{ $jadwal->id }}">
                                    <td>{{ $jadwal->siswa->user->name }}</td>
                                    <td>{{ $jadwal->kategori->nama }}</td>
                                 <td>{{ \Carbon\Carbon::parse($jadwal->tanggal_disetujui)->format('d-m-Y H:i') }}</td>
                                    <td>{{ Str::limit($jadwal->deskripsi_permasalahan, 50) }}</td>
                                    <td>
                                        <span class="badge
                                            {{ $jadwal->status === 'menunggu' ? 'bg-warning' :
                                               ($jadwal->status === 'disetujui' ? 'bg-success' :
                                               ($jadwal->status === 'selesai' ? 'bg-primary' : 'bg-danger')) }}">
                                            {{ ucfirst($jadwal->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $jadwal->skor_prioritas }}</td>
                                    @if (auth()->user()->role === 'guru' && auth()->user()->guru && auth()->user()->guru->role_guru === 'bk')
                                        <td>
                                            @if ($jadwal->status === 'menunggu')
                                                <button class="btn btn-sm btn-success approve-jadwal" data-id="{{ $jadwal->id }}"
                                                    data-bs-toggle="modal" data-bs-target="#approveModal"><i class="bi bi-check-circle"></i> Setujui</button>
                                                <button class="btn btn-sm btn-danger reject-jadwal" data-id="{{ $jadwal->id }}"
                                                    data-bs-toggle="modal" data-bs-target="#rejectModal"><i class="bi bi-x-circle"></i> Tolak</button>
                                            @endif
                                            @if ($jadwal->status === 'disetujui')
                                                <button class="btn btn-sm btn-primary complete-jadwal" data-id="{{ $jadwal->id }}"
                                                    data-bs-toggle="modal" data-bs-target="#completeModal"><i class="bi bi-check2-all"></i> Selesai</button>
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Setujui jadwal (BK) -->
    @if (auth()->user()->role === 'guru' && auth()->user()->guru && auth()->user()->guru->role_guru === 'bk')

        <!-- Modal Selesai Konseling (BK) -->
        <div class="modal fade" id="completeModal" tabindex="-1" aria-labelledby="completeModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="completeModalLabel">Selesaikan Konseling</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="completeForm" method="POST" action="{{ route('permohonan-konseling.complete', 0) }}">
                        @csrf
                        @method('PATCH')
                        <div class="modal-body">
                            <div class="form-group mb-3">
                                <label for="rangkuman" class="form-label">Rangkuman Konseling</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-textarea"></i></span>
                                    <textarea class="form-control" id="rangkuman" name="rangkuman" placeholder="Masukkan rangkuman hasil konseling" required>{{ old('rangkuman') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Selesai</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Set action URL untuk form setujui
            $('.approve-jadwal').on('click', function() {
                $('#approveForm').attr('action', "{{ url('permohonan-konseling/approve') }}/" + $(this).data('id'));
            });

            // Set action URL untuk form tolak
            $('.reject-jadwal').on('click', function() {
                $('#rejectForm').attr('action', "{{ url('permohonan-konseling/reject') }}/" + $(this).data('id'));
            });

            // Set action URL untuk form selesai
            $('.complete-jadwal').on('click', function() {
                $('#completeForm').attr('action', "{{ url('permohonan-konseling/complete') }}/" + $(this).data('id'));
            });

            // Inisialisasi DataTables dengan sorting berdasarkan skor_prioritas
            $('#datatableJadwal').DataTable({
                order: [[5, 'desc']], // Urutkan berdasarkan kolom skor_prioritas (index 5) secara descending
                columnDefs: [
                    { orderable: false, targets: -1 } // Nonaktifkan sorting pada kolom aksi
                ]
            });
        });
    </script>
@endsection
