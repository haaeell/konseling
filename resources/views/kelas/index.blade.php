@extends('layouts.dashboard')

@section('title', 'Kelas')
@section('breadcumb', 'Kelas')
@section('content')
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#kelasModal">
                    <i class="bi bi-plus-circle"></i> Tambah
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="datatable">
                        <thead>
                            <tr>
                                <th>Nama Kelas</th>
                                <th>Tahun Akademik</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="kelasTable">
                            @foreach ($kelas as $k)
                                <tr data-id="{{ $k->id }}">
                                    <td>{{ $k->nama }}</td>
                                    <td>{{ $k->tahunAkademik ? $k->tahunAkademik->tahun : '-' }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-warning edit-kelas" data-id="{{ $k->id }}"
                                            data-nama="{{ $k->nama }}" data-tahun_akademik_id="{{ $k->tahun_akademik_id }}"
                                            data-bs-toggle="modal" data-bs-target="#kelasModal"><i class="bi bi-pencil"></i> Edit</button>
                                        <form action="{{ route('kelas.destroy', $k->id) }}" method="POST"
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

    <!-- Modal Kelas -->
    <div class="modal fade" id="kelasModal" tabindex="-1" aria-labelledby="kelasModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="kelasModalLabel">Tambah Kelas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="kelasForm" method="POST" action="{{ route('kelas.store') }}">
                    @csrf
                    <input type="hidden" name="id" id="kelas_id">
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="nama" class="form-label">Nama Kelas</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-book"></i></span>
                                <input type="text" class="form-control" id="nama" name="nama"
                                    placeholder="Contoh: Kelas 10A" value="{{ old('nama') }}" required>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="tahun_akademik_id" class="form-label">Tahun Akademik</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                                <select class="form-control" id="tahun_akademik_id" name="tahun_akademik_id" required>
                                    <option value="">Pilih Tahun Akademik</option>
                                    @foreach ($tahunAkademiks as $tahun)
                                        <option value="{{ $tahun->id }}">{{ $tahun->tahun }}</option>
                                    @endforeach
                                </select>
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
            $('.edit-kelas').on('click', function() {
                $('#kelasModalLabel').text('Edit Kelas');
                $('#kelas_id').val($(this).data('id'));
                $('#nama').val($(this).data('nama'));
                $('#tahun_akademik_id').val($(this).data('tahun_akademik_id'));
                $('#kelasForm').attr('action', "{{ url('kelas') }}/" + $(this).data('id'));
                if (!$('#kelasForm input[name="_method"]').length) {
                    $('#kelasForm').append('<input type="hidden" name="_method" value="PUT">');
                }
            });

            // Reset modal saat tambah data
            $('#kelasModal').on('show.bs.modal', function(event) {
                if (!$(event.relatedTarget).hasClass('edit-kelas')) {
                    $('#kelasModalLabel').text('Tambah Kelas');
                    $('#kelasForm')[0].reset();
                    $('#kelasForm').attr('action', "{{ route('kelas.store') }}");
                    $('#kelasForm').find('input[name="_method"]').remove();
                }
            });
        });
    </script>
@endsection
