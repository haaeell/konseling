@extends('layouts.dashboard')

@section('title', 'Permohonan Konseling')
@section('breadcumb', 'Permohonan Konseling')
@section('content')
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                @if (auth()->user()->role === 'siswa' ||
                        (auth()->user()->role === 'guru' && auth()->user()->guru && auth()->user()->guru->role_guru === 'walikelas'))
                    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal"
                        data-bs-target="#permohonanKonselingModal">
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
                                <th>Tipe</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Deskripsi Permasalahan</th>
                                <th>Status</th>
                                <th>Skor Prioritas</th>
                                <th>Bukti</th>
                                @if (auth()->user()->role === 'guru' && auth()->user()->guru && auth()->user()->guru->role_guru === 'bk')
                                    <th>Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody id="permohonanKonselingTable">
                            @foreach ($permohonanKonseling as $permohonan)
                                <tr data-id="{{ $permohonan->id }}">
                                    <td>{{ $permohonan->siswa->user->name }}</td>
                                    <td>
                                        @if ($permohonan->report_type === 'self')
                                            <span class="text-primary">
                                                <i class="bi bi-person"></i> Laporan Siswa
                                            </span>
                                        @else
                                            <span class="text-dark">
                                                <i class="bi bi-person-badge"></i> Laporan Guru
                                            </span>
                                        @endif
                                    </td>

                                    <td>{{ \Carbon\Carbon::parse($permohonan->tanggal_pengajuan)->format('d-m-Y') }}</td>

                                    <td>{{ Str::limit($permohonan->deskripsi_permasalahan, 50) }}</td>

                                    {{-- STATUS --}}
                                    <td>
                                        <span
                                            class="badge
                                                {{ $permohonan->status === 'menunggu'
                                                    ? 'bg-warning'
                                                    : ($permohonan->status === 'disetujui'
                                                        ? 'bg-success'
                                                        : ($permohonan->status === 'selesai'
                                                            ? 'bg-primary'
                                                            : 'bg-danger')) }}">
                                            {{ ucfirst($permohonan->status) }}
                                        </span>

                                        @if ($permohonan->status === 'ditolak' && $permohonan->alasan_penolakan)
                                            <div class="mt-1 small text-danger">
                                                <i class="bi bi-info-circle"></i> {{ $permohonan->alasan_penolakan }}
                                            </div>
                                        @endif
                                    </td>

                                    {{-- SKOR PRIORITAS --}}
                                    <td>
                                        <strong>{{ $permohonan->skor_prioritas }}</strong>
                                        <div class="text-muted small">
                                            @foreach ($permohonan->permohonanKriteria as $pk)
                                                <div>{{ $pk->kriteria_nama }}: {{ $pk->sub_kriteria_nama }}
                                                    ({{ $pk->skor }})
                                                </div>
                                            @endforeach
                                        </div>
                                    </td>


                                    @if (auth()->user()->role === 'guru' && auth()->user()->guru && auth()->user()->guru->role_guru === 'bk')
                                        <td>
                                            @if ($permohonan->status === 'menunggu')
                                                <button class="btn btn-sm btn-success approve-permohonan"
                                                    data-id="{{ $permohonan->id }}" data-bs-toggle="modal"
                                                    data-bs-target="#approveModal">
                                                    <i class="bi bi-check-circle"></i> Setujui
                                                </button>

                                                <button class="btn btn-sm btn-danger reject-permohonan"
                                                    data-id="{{ $permohonan->id }}" data-bs-toggle="modal"
                                                    data-bs-target="#rejectModal">
                                                    <i class="bi bi-x-circle"></i> Tolak
                                                </button>
                                            @endif

                                            @if ($permohonan->status === 'disetujui')
                                                <button class="btn btn-sm btn-primary complete-permohonan"
                                                    data-id="{{ $permohonan->id }}" data-bs-toggle="modal"
                                                    data-bs-target="#completeModal">
                                                    <i class="bi bi-check2-all"></i> Selesai
                                                </button>
                                            @endif
                                        </td>
                                    @endif
                                    <td>
                                        @if ($permohonan->bukti_masalah)
                                            @php
                                                $ext = strtolower(
                                                    pathinfo($permohonan->bukti_masalah, PATHINFO_EXTENSION),
                                                );
                                            @endphp

                                            {{-- FOTO --}}
                                            @if (in_array($ext, ['jpg', 'jpeg', 'png']))
                                                <a href="{{ asset('storage/' . $permohonan->bukti_masalah) }}"
                                                    target="_blank">
                                                    <img src="{{ asset('storage/' . $permohonan->bukti_masalah) }}"
                                                        width="60" class="img-thumbnail">
                                                </a>

                                                {{-- VIDEO --}}
                                            @elseif (in_array($ext, ['mp4', 'mov', 'avi']))
                                                <video width="100" controls>
                                                    <source src="{{ asset('storage/' . $permohonan->bukti_masalah) }}">
                                                </video>

                                                {{-- FILE LAIN --}}
                                            @else
                                                <a href="{{ asset('storage/' . $permohonan->bukti_masalah) }}"
                                                    class="btn btn-info btn-sm" target="_blank">
                                                    <i class="bi bi-eye"></i> Lihat File
                                                </a>
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @if (auth()->user()->role === 'siswa' ||
            (auth()->user()->role === 'guru' && auth()->user()->guru && auth()->user()->guru->role_guru === 'walikelas'))
        <div class="modal fade" id="permohonanKonselingModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">Buat Permohonan Konseling</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <form method="POST" action="{{ route('permohonan-konseling.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="modal-body">

                            @if (auth()->user()->role === 'guru' && auth()->user()->guru->role_guru === 'walikelas')
                                <div class="mb-3">
                                    <label class="form-label">Siswa</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                                        <select class="form-control" name="siswa_id" id="siswa_id" required>
                                            <option value="">Pilih Siswa</option>
                                            @foreach ($siswaWali as $siswa)
                                                <option value="{{ $siswa->id }}">{{ $siswa->user->name }} -
                                                    {{ $siswa->nisn }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                            @php

                                if ($jumlahRiwayat > 3) {
                                    $riwayatNama = 'Sudah Sering Konseling';
                                    $riwayatSkor = 20;
                                } elseif ($jumlahRiwayat >= 1) {
                                    $riwayatNama = 'Sudah Beberapa Kali';
                                    $riwayatSkor = 40;
                                } else {
                                    $riwayatNama = 'Belum Pernah Konseling';
                                    $riwayatSkor = 90;
                                }
                            @endphp

                            @foreach ($kriteria as $k)
                                @if ($k->nama === 'Riwayat Konseling')
                                    <div class="mb-3">
                                        <label class="form-label">{{ $k->nama }}</label>

                                        <select class="form-control" id="riwayat_konseling_display" disabled>
                                            <option>Silakan pilih siswa</option>
                                        </select>

                                        <div class="alert alert-info mt-2 small" id="riwayat_info" style="display:none">
                                            <div>
                                                Jumlah konseling bulan ini:
                                                <span class="badge bg-primary" id="jumlah_riwayat">0</span>
                                                <hr class="my-2">

                                                <div>
                                                    Nilai ditentukan otomatis berdasarkan aturan berikut:
                                                    <ul class="mb-0 ps-3">
                                                        <li>
                                                            <strong>0 kali</strong> →
                                                            Belum Pernah Konseling
                                                        </li>
                                                        <li>
                                                            <strong>1–3 kali</strong> →
                                                            Sudah Beberapa Kali
                                                        </li>
                                                        <li>
                                                            <strong>> 3 kali</strong> →
                                                            Sudah Sering Konseling
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <input type="hidden" name="kriteria[{{ $k->id }}]" id="riwayat_skor">
                                        <input type="hidden" name="sub_kriteria[{{ $k->id }}]" id="riwayat_nama">
                                    </div>
                                @else
                                    <div class="mb-3">
                                        <label class="form-label">{{ $k->nama }}</label>
                                        <input type="hidden" name="kriteria[{{ $k->id }}]" id="kriteria_{{ $k->id }}" required>
                                        <input type="hidden" name="sub_kriteria[{{ $k->id }}]" id="label_{{ $k->id }}">
                                        <div class="mt-2">
                                            @foreach ($k->subKriteria->sortByDesc('skor') as $sub)
                                                <div class="card mb-2 border-start border-4 border-secondary shadow-sm subkriteria-card" style="cursor:pointer; transition:0.2s;" data-kriteria="{{ $k->id }}" data-skor="{{ $sub->skor }}" data-nama="{{ $sub->nama_sub }}">
                                                    <div class="card-body py-2">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div class="d-flex align-items-center" style="gap: 0.5rem;">
                                                                <strong class="mb-0">{{ $sub->nama_sub }}</strong>
                                                            </div>
                                                            <span class="badge bg-secondary">{{ $sub->skor }}</span>
                                                        </div>
                                                        <div class="text-muted small mt-1">
                                                            <i class="bi bi-lightbulb"></i>
                                                            {{ $sub->guide_text }}
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="text-danger small mt-1" id="error_kriteria_{{ $k->id }}" style="display:none;">Pilih salah satu sub kriteria.</div>
                                    </div>
                                @endif
                            @endforeach


                            <div class="mb-3">
                                <label class="form-label">Deskripsi Permasalahan</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-textarea"></i></span>
                                    <textarea class="form-control" name="deskripsi_permasalahan" rows="4" required
                                        placeholder="Jelaskan permasalahan secara lengkap..."></textarea>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Upload Bukti Masalah (Foto/Video) <span
                                        class="text-muted">(Opsional)</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-paperclip"></i></span>
                                    <input type="file" class="form-control" name="bukti_masalah"
                                        accept="image/*,video/*">
                                </div>
                                <small class="text-muted">Format yang didukung: JPG, PNG, MP4, MOV. Tidak wajib.</small>
                            </div>
                        </div>

                        <div id="ringkasan-pilihan" class="alert alert-info mb-2" style="display:none;"></div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button class="btn btn-primary"><i class="bi bi-send"></i> Kirim Permohonan</button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    @endif
    @if (auth()->user()->role === 'guru' && auth()->user()->guru && auth()->user()->guru->role_guru === 'bk')
        <div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel"
            aria-hidden="true">
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
                                    <input type="datetime-local" class="form-control" id="tanggal_disetujui"
                                        name="tanggal_disetujui"
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
                            <div class="form-group mb-3">
                                <label for="alasan_penolakan" class="form-label">Alasan Penolakan</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-chat-dots"></i></span>
                                    <textarea class="form-control" id="alasan_penolakan" name="alasan_penolakan"
                                        placeholder="Tuliskan alasan penolakan..." required></textarea>
                                </div>
                            </div>
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
        <div class="modal fade" id="completeModal" tabindex="-1" aria-labelledby="completeModalLabel"
            aria-hidden="true">
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
                                    <textarea class="form-control" id="rangkuman" name="rangkuman" placeholder="Masukkan rangkuman hasil konseling"
                                        required>{{ old('rangkuman') }}</textarea>
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
            function loadRiwayat(siswaId) {
                $.get('/ajax/riwayat-konseling/' + siswaId, function(res) {
                    $('#riwayat_konseling_display').html(
                        `<option>${res.nama} (${res.skor})</option>`
                    );
                    $('#jumlah_riwayat').text(res.jumlah);
                    $('#riwayat_info').show();
                    $('#riwayat_skor').val(res.skor);
                    $('#riwayat_nama').val(res.nama);
                });
            }

            @if (auth()->user()->role === 'siswa')
                // AUTO LOAD UNTUK SISWA
                loadRiwayat({{ auth()->user()->siswa->id }});
            @endif

            @if (auth()->user()->role === 'guru' && auth()->user()->guru->role_guru === 'walikelas')
                // LOAD SAAT PILIH SISWA
                $('#siswa_id').on('change', function() {
                    let siswaId = $(this).val();
                    if (siswaId) loadRiwayat(siswaId);
                });
            @endif


            // Interaktif sub kriteria dengan highlight, ceklis, dan ringkasan
            function updateRingkasanPilihan() {
                var ringkasan = [];
                @foreach ($kriteria as $k)
                    @if ($k->nama !== 'Riwayat Konseling')
                        var nama = $('#label_{{ $k->id }}').val();
                        if (nama) ringkasan.push('<span class="badge bg-primary">{{ $k->nama }}: ' + nama + '</span>');
                    @endif
                @endforeach
            }

            $('.subkriteria-card').on('click', function() {
                var kriteriaId = $(this).data('kriteria');
                var skor = $(this).data('skor');
                var nama = $(this).data('nama');

                // Set value ke input hidden
                $('#kriteria_' + kriteriaId).val(skor);
                $('#label_' + kriteriaId).val(nama);

                // Highlight card terpilih dan ceklis
                var group = $(this).closest('.mt-2').find('.subkriteria-card');
                group.removeClass('border-primary bg-light').addClass('border-secondary');
                group.find('.ceklis-icon').hide();
                $(this).removeClass('border-secondary').addClass('border-primary bg-light');
                $(this).find('.ceklis-icon').show();

                // Hilangkan error jika ada
                $('#error_kriteria_' + kriteriaId).hide();

                // Update ringkasan
                updateRingkasanPilihan();
            });

            // Update ringkasan saat load (jika ada value lama)
            updateRingkasanPilihan();

            // Validasi sebelum submit
            $('form[action*="permohonan-konseling.store"]').on('submit', function(e) {
                var valid = true;
                @foreach ($kriteria as $k)
                    @if ($k->nama !== 'Riwayat Konseling')
                        if (!$('#kriteria_{{ $k->id }}').val()) {
                            $('#error_kriteria_{{ $k->id }}').show();
                            valid = false;
                        }
                    @endif
                @endforeach
                if (!valid) e.preventDefault();
            });

            $('.approve-permohonan').on('click', function() {
                $('#approveForm').attr('action', "{{ url('permohonan-konseling/approve') }}/" + $(this)
                    .data('id'));
            });

            $('.reject-permohonan').on('click', function() {
                $('#rejectForm').attr('action', "{{ url('permohonan-konseling/reject') }}/" + $(this).data(
                    'id'));
            });

            $('.complete-permohonan').on('click', function() {
                $('#completeForm').attr('action', "{{ url('permohonan-konseling/complete') }}/" + $(this)
                    .data('id'));
            });

            $('#datatablePermohonanpe').DataTable({
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
