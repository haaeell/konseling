@extends('layouts.dashboard')

@section('title', 'Permohonan Konseling')
@section('breadcumb', 'Permohonan Konseling')
@section('content')
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                @if (auth()->user()->role === 'siswa' || auth()->user()->role === 'guru' && auth()->user()->guru && auth()->user()->guru->role_guru === 'walikelas')
                    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#permohonanKonselingModal">
                        <i class="bi bi-plus-circle"></i> Buat Permohonan
                    </button>
                @endif
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="datatablePermohonan">
                        <thead>
                            <tr>
                                <th>Siswa</th>
                                <th>Kategori</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Deskripsi Permasalahan</th>
                                <th>Status</th>
                                <th>Skor Prioritas</th>
                                @if (auth()->user()->role === 'guru' && auth()->user()->guru && auth()->user()->guru->role_guru === 'bk')
                                    <th>Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody id="permohonanKonselingTable">
                            @foreach ($permohonanKonseling as $permohonan)
                                <tr data-id="{{ $permohonan->id }}">
                                    <td>{{ $permohonan->siswa->user->name }}</td>
                                    <td>{{ $permohonan->kategori->nama }}</td>
                                    <td>{{ \Carbon\Carbon::parse($permohonan->tanggal_pengajuan)->format('d-m-Y') }}</td>
                                    <td>{{ Str::limit($permohonan->deskripsi_permasalahan, 50) }}</td>
                                    <td>
                                        <span class="badge
                                            {{ $permohonan->status === 'menunggu' ? 'bg-warning' :
                                               ($permohonan->status === 'disetujui' ? 'bg-success' :
                                               ($permohonan->status === 'selesai' ? 'bg-primary' : 'bg-danger')) }}">
                                            {{ ucfirst($permohonan->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $permohonan->skor_prioritas }}</td>
                                    @if (auth()->user()->role === 'guru' && auth()->user()->guru && auth()->user()->guru->role_guru === 'bk')
                                        <td>
                                            @if ($permohonan->status === 'menunggu')
                                                <button class="btn btn-sm btn-success approve-permohonan" data-id="{{ $permohonan->id }}"
                                                    data-bs-toggle="modal" data-bs-target="#approveModal"><i class="bi bi-check-circle"></i> Setujui</button>
                                                <button class="btn btn-sm btn-danger reject-permohonan" data-id="{{ $permohonan->id }}"
                                                    data-bs-toggle="modal" data-bs-target="#rejectModal"><i class="bi bi-x-circle"></i> Tolak</button>
                                            @endif
                                            @if ($permohonan->status === 'disetujui')
                                                <button class="btn btn-sm btn-primary complete-permohonan" data-id="{{ $permohonan->id }}"
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

    <!-- Modal Buat Permohonan (Siswa) -->
    @if (auth()->user()->role === 'siswa' || (auth()->user()->role === 'guru' && auth()->user()->guru && auth()->user()->guru->role_guru === 'walikelas'))
        <div class="modal fade" id="permohonanKonselingModal" tabindex="-1" aria-labelledby="permohonanKonselingModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="permohonanKonselingModalLabel">Buat Permohonan Konseling</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="permohonanKonselingForm" method="POST" action="{{ route('permohonan-konseling.store') }}">
                        @csrf
                        <div class="modal-body">
                            @if (auth()->user()->role === 'guru' && auth()->user()->guru && auth()->user()->guru->role_guru === 'walikelas')
                                <div class="form-group mb-3">
                                    <label for="siswa_id" class="form-label">Siswa</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                                        <select class="form-control" id="siswa_id" name="siswa_id" required>
                                            <option value="">Pilih Siswa</option>
                                            @foreach ($siswaWali as $siswa)
                                                <option value="{{ $siswa->id }}" {{ old('siswa_id') == $siswa->id ? 'selected' : '' }}>
                                                    {{ $siswa->user->name }} - {{ $siswa->nisn }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                            <div class="form-group mb-3">
                                <label for="kategori_id" class="form-label">Kategori Konseling</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-tag"></i></span>
                                    <select class="form-control" id="kategori_id" name="kategori_id" required>
                                        <option value="">Pilih Kategori</option>
                                        @foreach ($kategoriKonseling as $kategori)
                                            <option value="{{ $kategori->id }}" data-skor="{{ $kategori->skor_prioritas }}">{{ $kategori->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="tanggal_pengajuan" class="form-label">Tanggal Pengajuan</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                                    <input type="date" class="form-control" id="tanggal_pengajuan" name="tanggal_pengajuan"
                                        value="{{ old('tanggal_pengajuan', now()->format('Y-m-d')) }}" required>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="deskripsi_permasalahan" class="form-label">Deskripsi Permasalahan</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-textarea"></i></span>
                                    <textarea class="form-control" id="deskripsi_permasalahan" name="deskripsi_permasalahan" placeholder="Jelaskan permasalahan Anda" required>{{ old('deskripsi_permasalahan') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Kirim Permohonan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Setujui Permohonan (BK) -->
    @if (auth()->user()->role === 'guru' && auth()->user()->guru && auth()->user()->guru->role_guru === 'bk')
        <div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="approveModalLabel">Setujui Permohonan Konseling</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="approveForm" method="POST" action="{{ route('permohonan-konseling.approve', 0) }}">
                        @csrf
                        @method('PATCH')
                        <div class="modal-body">
                            <div class="form-group mb-3">
                                <label for="tanggal_disetujui" class="form-label">Tanggal Konseling</label>
                              <div class="input-group">
    <span class="input-group-text"><i class="bi bi-calendar"></i></span>
    <input type="datetime-local" class="form-control" id="tanggal_disetujui" name="tanggal_disetujui"
        value="{{ old('tanggal_disetujui', now()->format('Y-m-d\TH:i')) }}" required>
</div>

                            </div>
                            <div class="form-group mb-3">
                                <label for="tempat" class="form-label">Tempat Konseling</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                    <input type="text" class="form-control" id="tempat" name="tempat"
                                        placeholder="Masukkan tempat konseling" value="{{ old('tempat') }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success">Setujui</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Tolak Permohonan (BK) -->
        <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="rejectModalLabel">Tolak Permohonan Konseling</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="rejectForm" method="POST" action="{{ route('permohonan-konseling.reject', 0) }}">
                        @csrf
                        @method('PATCH')
                        <div class="modal-body">
                            <p>Apakah Anda yakin ingin menolak permohonan ini?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger">Tolak</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

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
            $('.approve-permohonan').on('click', function() {
                $('#approveForm').attr('action', "{{ url('permohonan-konseling/approve') }}/" + $(this).data('id'));
            });

            // Set action URL untuk form tolak
            $('.reject-permohonan').on('click', function() {
                $('#rejectForm').attr('action', "{{ url('permohonan-konseling/reject') }}/" + $(this).data('id'));
            });

            // Set action URL untuk form selesai
            $('.complete-permohonan').on('click', function() {
                $('#completeForm').attr('action', "{{ url('permohonan-konseling/complete') }}/" + $(this).data('id'));
            });

            // Inisialisasi DataTables dengan sorting berdasarkan skor_prioritas
            $('#datatablePermohonanpe').DataTable({
                order: [[5, 'desc']], // Urutkan berdasarkan kolom skor_prioritas (index 5) secara descending
                columnDefs: [
                    { orderable: false, targets: -1 } // Nonaktifkan sorting pada kolom aksi
                ]
            });
        });
    </script>
@endsection
