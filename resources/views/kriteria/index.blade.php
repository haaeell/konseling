@extends('layouts.dashboard')
@section('title', 'Manajemen Kriteria & Sub Kriteria')
@section('breadcumb', 'Manajemen Kriteria & Sub Kriteria')


@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahKriteria">
                <i class="bi bi-plus-circle"></i> Tambah Kriteria
            </button>
        </div>

        <div class="accordion" id="accordionKriteria">
            @foreach ($kriteria as $k)
                @php
                    $isRiwayat = strtolower($k->nama) === 'riwayat konseling';
                @endphp
                <div class="accordion-item mb-2 shadow-sm">
                    <h2 class="accordion-header" id="heading{{ $k->id }}">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapse{{ $k->id }}" aria-expanded="false"
                            aria-controls="collapse{{ $k->id }}">
                            <i class="bi bi-list-check me-2"></i>
                            {{ $k->nama }} <span class="badge bg-info ms-2">Bobot: {{ $k->bobot }}</span>
                        </button>
                    </h2>
                    <div id="collapse{{ $k->id }}" class="accordion-collapse collapse"
                        aria-labelledby="heading{{ $k->id }}" data-bs-parent="#accordionKriteria">
                        <div class="accordion-body">
                            <div class="d-flex justify-content-end mb-2">
                                <button class="btn btn-sm btn-warning me-2" data-bs-toggle="modal"
                                    data-bs-target="#modalEditKriteria{{ $k->id }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('kriteria.destroy', $k->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus kriteria ini?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th><i class="bi bi-journal-text me-1"></i>Sub Kriteria</th>
                                        <th><i class="bi bi-question-circle me-1"></i>Guide</th>
                                        @if ($isRiwayat)
                                            <th>Range</th>
                                        @endif
                                        <th><i class="bi bi-star me-1"></i>Skor</th>
                                        <th width="120"><i class="bi bi-gear me-1"></i>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($k->subKriteria as $sub)
                                        <tr>
                                            <td>{{ $sub->nama_sub }}</td>
                                            <td>
                                                <small class="text-muted">
                                                    {{ $sub->guide_text ?? '-' }}
                                                </small>
                                            </td>
                                            @if ($isRiwayat)
                                                <td>
                                                    <span class="badge bg-secondary">
                                                        {{ $sub->range_min }} - {{ $sub->range_max }}
                                                    </span>
                                                </td>
                                            @endif
                                            <td>{{ $sub->skor }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                                    data-bs-target="#modalEditSub{{ $sub->id }}">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <form action="{{ route('subkriteria.destroy', $sub->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Hapus sub kriteria?')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <button class="btn btn-success btn-sm" data-bs-toggle="modal"
                                data-bs-target="#modalTambahSub{{ $k->id }}">
                                <i class="bi bi-plus"></i> Tambah Sub Kriteria
                            </button>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="modalTambahSub{{ $k->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <form action="{{ route('subkriteria.store') }}" method="POST" class="modal-content">
                            @csrf
                            <input type="hidden" name="kriteria_id" value="{{ $k->id }}">
                            <div class="modal-header">
                                <h5 class="modal-title">Tambah Sub Kriteria</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Nama Sub Kriteria</label>
                                    <input type="text" name="nama_sub" class="form-control" required>
                                </div>
                                @if ($isRiwayat)
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Range Minimum</label>
                                            <input type="number" name="range_min" class="form-control" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Range Maksimum</label>
                                            <input type="number" name="range_max" class="form-control" required>
                                        </div>
                                    </div>
                                @endif

                                <div class="mb-3">
                                    <label class="form-label">
                                        Guide Text
                                        <small class="text-muted">(petunjuk penilaian)</small>
                                    </label>
                                    <textarea name="guide_text" class="form-control" rows="2"
                                        placeholder="Contoh: Pilih ini jika siswa sering melakukan pelanggaran ringan"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Skor</label>
                                    <input type="number" name="skor" class="form-control" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="modal fade" id="modalEditKriteria{{ $k->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <form action="{{ route('kriteria.update', $k->id) }}" method="POST" class="modal-content">
                            @csrf @method('PUT')
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Kriteria</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Nama Kriteria</label>
                                    <input type="text" name="nama" class="form-control"
                                        value="{{ $k->nama }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Bobot</label>
                                    <input type="number" step="0.01" name="bobot" class="form-control"
                                        value="{{ $k->bobot }}" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>

                @foreach ($k->subKriteria as $sub)
                    <div class="modal fade" id="modalEditSub{{ $sub->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <form action="{{ route('subkriteria.update', $sub->id) }}" method="POST"
                                class="modal-content">
                                @csrf @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Sub Kriteria</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Nama Sub Kriteria</label>
                                        <input type="text" name="nama_sub" class="form-control"
                                            value="{{ $sub->nama_sub }}" required>
                                    </div>
                                    @if (strtolower($k->nama) === 'riwayat konseling')
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Range Minimum</label>
                                                <input type="number" name="range_min" class="form-control"
                                                    value="{{ $sub->range_min }}">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Range Maksimum</label>
                                                <input type="number" name="range_max" class="form-control"
                                                    value="{{ $sub->range_max }}">
                                            </div>
                                        </div>
                                    @endif

                                    <div class="mb-3">
                                        <label class="form-label">Guide Text</label>
                                        <textarea name="guide_text" class="form-control" rows="2">{{ $sub->guide_text }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Skor</label>
                                        <input type="number" name="skor" class="form-control"
                                            value="{{ $sub->skor }}" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-primary">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach
            @endforeach
        </div>

        <div class="modal fade" id="modalTambahKriteria" tabindex="-1">
            <div class="modal-dialog">
                <form action="{{ route('kriteria.store') }}" method="POST" class="modal-content">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Kriteria</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Kriteria</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Bobot</label>
                            <input type="number" step="0.01" name="bobot" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    @endsection
