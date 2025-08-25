@extends('layouts.dashboard')

@section('title', 'Tahun Akademik')
@section('breadcumb', 'Tahun Akademik')
@section('content')
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tahunAkademikModal">
                    <i class="bi bi-plus-circle"></i> Tambah
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="datatable">
                        <thead>
                            <tr>
                                <th>Tahun</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tahunAkademikTable">
                            @foreach ($tahunAkademiks as $tahun)
                                <tr data-id="{{ $tahun->id }}">
                                    <td>{{ $tahun->tahun }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-warning edit-tahun" data-id="{{ $tahun->id }}"
                                            data-tahun="{{ $tahun->tahun }}" data-bs-toggle="modal"
                                            data-bs-target="#tahunAkademikModal"><i class="bi bi-pencil"></i> Edit</button>
                                        <form action="{{ route('tahun-akademik.destroy', $tahun->id) }}" method="POST"
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

    <!-- Modal Tahun Akademik -->
    <div class="modal fade" id="tahunAkademikModal" tabindex="-1" aria-labelledby="tahunAkademikModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tahunAkademikModalLabel">Tambah Tahun Akademik</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="tahunAkademikForm" method="POST" action="{{ route('tahun-akademik.store') }}">
                    @csrf
                    <input type="hidden" name="id" id="tahun_id">
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="tahun" class="form-label">Tahun Akademik</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                                <input type="text" class="form-control" id="tahun" name="tahun"
                                    placeholder="Contoh: 2023/2024" value="{{ old('tahun') }}" required>
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
            $('.edit-tahun').on('click', function() {
                $('#tahunAkademikModalLabel').text('Edit Tahun Akademik');
                $('#tahun_id').val($(this).data('id'));
                $('#tahun').val($(this).data('tahun'));
                $('#tahunAkademikForm').attr('action', "{{ url('tahun-akademik') }}/" + $(this).data('id'));
                if (!$('#tahunAkademikForm input[name="_method"]').length) {
                    $('#tahunAkademikForm').append('<input type="hidden" name="_method" value="PUT">');
                }
            });

            // Reset modal saat tambah data
            $('#tahunAkademikModal').on('show.bs.modal', function(event) {
                if (!$(event.relatedTarget).hasClass('edit-tahun')) {
                    $('#tahunAkademikModalLabel').text('Tambah Tahun Akademik');
                    $('#tahunAkademikForm')[0].reset();
                    $('#tahunAkademikForm').attr('action', "{{ route('tahun-akademik.store') }}");
                    $('#tahunAkademikForm').find('input[name="_method"]').remove();
                }
            });
        });
    </script>
@endsection
