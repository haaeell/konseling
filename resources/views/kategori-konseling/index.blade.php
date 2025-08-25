@extends('layouts.dashboard')

@section('title', 'Kategori Konseling')
@section('breadcumb', 'Kategori Konseling')
@section('content')
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#kategoriKonselingModal">
                    <i class="bi bi-plus-circle"></i> Tambah
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="datatable">
                        <thead>
                            <tr>
                                <th>Nama Kategori</th>
                                <th>Skor Prioritas</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="kategoriKonselingTable">
                            @foreach ($kategoriKonseling as $kategori)
                                <tr data-id="{{ $kategori->id }}">
                                    <td>{{ $kategori->nama }}</td>
                                    <td>{{ $kategori->skor_prioritas }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-warning edit-kategori" data-id="{{ $kategori->id }}"
                                            data-nama="{{ $kategori->nama }}" data-skor_prioritas="{{ $kategori->skor_prioritas }}"
                                            data-bs-toggle="modal" data-bs-target="#kategoriKonselingModal"><i class="bi bi-pencil"></i> Edit</button>
                                        <form action="{{ route('kategori-konseling.destroy', $kategori->id) }}" method="POST"
                                            style="display:inline;"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i>
                                                Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Kategori Konseling -->
    <div class="modal fade" id="kategoriKonselingModal" tabindex="-1" aria-labelledby="kategoriKonselingModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="kategoriKonselingModalLabel">Tambah Kategori Konseling</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="kategoriKonselingForm" method="POST" action="{{ route('kategori-konseling.store') }}">
                    @csrf
                    <input type="hidden" name="id" id="kategori_id">
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="nama" class="form-label">Nama Kategori</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-tag"></i></span>
                                <input type="text" class="form-control" id="nama" name="nama"
                                    placeholder="Contoh: Konseling Akademik" value="{{ old('nama') }}" required>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="skor_prioritas" class="form-label">Skor Prioritas (1-10)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-star"></i></span>
                                <input type="number" class="form-control" id="skor_prioritas" name="skor_prioritas"
                                    placeholder="Masukkan skor prioritas (1-10)" value="{{ old('skor_prioritas', 5) }}"
                                    min="1" max="10" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Edit data
            $('.edit-kategori').on('click', function() {
                $('#kategoriKonselingModalLabel').text('Edit Kategori Konseling');
                $('#kategori_id').val($(this).data('id'));
                $('#nama').val($(this).data('nama'));
                $('#skor_prioritas').val($(this).data('skor_prioritas'));
                $('#kategoriKonselingForm').attr('action', "{{ url('kategori-konseling') }}/" + $(this).data('id'));
                if (!$('#kategoriKonselingForm input[name="_method"]').length) {
                    $('#kategoriKonselingForm').append('<input type="hidden" name="_method" value="PUT">');
                }
            });

            // Reset modal saat tambah data
            $('#kategoriKonselingModal').on('show.bs.modal', function(event) {
                if (!$(event.relatedTarget).hasClass('edit-kategori')) {
                    $('#kategoriKonselingModalLabel').text('Tambah Kategori Konseling');
                    $('#kategoriKonselingForm')[0].reset();
                    $('#kategoriKonselingForm').attr('action', "{{ route('kategori-konseling.store') }}");
                    $('#kategoriKonselingForm').find('input[name="_method"]').remove();
                    $('#skor_prioritas').val(5); // Default value
                }
            });
        });
    </script>
@endsection
