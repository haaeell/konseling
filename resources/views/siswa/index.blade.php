@extends('layouts.dashboard')

@section('title', 'Siswa')
@section('breadcumb', 'Siswa')
@section('content')
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
              <div class="d-flex mb-3">
    <!-- Tombol Tambah -->
    <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#siswaModal">
        <i class="bi bi-plus-circle"></i> Tambah
    </button>

    <!-- Tombol Download Template -->
    <a href="{{ route('siswa.template') }}" class="btn btn-info me-2">
        <i class="bi bi-download"></i> Download Template
    </a>

    <!-- Form Import -->
    <form action="{{ route('siswa.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="input-group">
            <input type="file" name="file" class="form-control" accept=".xlsx,.xls" required>
            <button class="btn btn-success" type="submit">
                <i class="bi bi-file-earmark-arrow-up"></i> Import Excel
            </button>
        </div>
    </form>
</div>

            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="datatable">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>NISN</th>
                                <th>NIS</th>
                                <th>Kelas</th>
                                <th>Jenis Kelamin</th>
                                <th>Nama Orangtua</th>
                                <th>No Telp Orangtua</th>
                                <th>Alamat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="siswaTable">
                            @foreach ($siswa as $s)
                                <tr data-id="{{ $s->id }}">
                                    <td>{{ $s->user->name }}</td>
                                    <td>{{ $s->nisn }}</td>
                                    <td>{{ $s->nis }}</td>
                                    <td>{{ $s->kelas ? $s->kelas->nama : '-' }}</td>
                                    <td>{{ $s->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                    <td>{{ $s->nama_orangtua }}</td>
                                    <td>{{ $s->no_telp_orangtua }}</td>
                                    <td>{{ $s->alamat }}</td>
                                    <td class="d-flex text-nowrap gap-2">
                                        <button class="btn btn-sm btn-warning edit-siswa" data-id="{{ $s->id }}"
                                            data-name="{{ $s->user->name }}" data-email="{{ $s->user->email }}"
                                            data-nisn="{{ $s->nisn }}" data-nis="{{ $s->nis }}"
                                            data-kelas_id="{{ $s->kelas_id }}"
                                            data-jenis_kelamin="{{ $s->jenis_kelamin }}"
                                            data-nama_orangtua="{{ $s->nama_orangtua }}"
                                            data-no_telp_orangtua="{{ $s->no_telp_orangtua }}"
                                            data-alamat="{{ $s->alamat }}" data-user_id="{{ $s->user_id }}"
                                            data-hubungan_dengan_siswa="{{ $s->orangtua->first() ? $s->orangtua->first()->hubungan_dengan_siswa : '' }}"
                                            data-bs-toggle="modal" data-bs-target="#siswaModal"><i class="bi bi-pencil"></i> Edit</button>
                                        <form action="{{ route('siswa.destroy', $s->id) }}" method="POST"
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

    <!-- Modal Siswa -->
    <div class="modal fade" id="siswaModal" tabindex="-1" aria-labelledby="siswaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="siswaModalLabel">Tambah Siswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="siswaForm" method="POST" action="{{ route('siswa.store') }}">
                    @csrf
                    <input type="hidden" name="id" id="siswa_id">
                    <input type="hidden" name="user_id" id="user_id">
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">Nama</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="Masukkan nama siswa" value="{{ old('name') }}" required>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="email" class="form-label">Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" class="form-control" id="email" name="email"
                                    placeholder="Masukkan email" value="{{ old('email') }}" required>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="nisn" class="form-label">NISN</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-card-text"></i></span>
                                <input type="text" class="form-control" id="nisn" name="nisn"
                                    placeholder="Masukkan NISN" value="{{ old('nisn') }}" required>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="nis" class="form-label">NIS</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-card-text"></i></span>
                                <input type="text" class="form-control" id="nis" name="nis"
                                    placeholder="Masukkan NIS" value="{{ old('nis') }}" required>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="kelas_id" class="form-label">Kelas</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-book"></i></span>
                                <select class="form-control" id="kelas_id" name="kelas_id" required>
                                    <option value="">Pilih Kelas</option>
                                    @foreach ($kelas as $k)
                                        <option value="{{ $k->id }}">{{ $k->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-gender-ambiguous"></i></span>
                                <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="nama_orangtua" class="form-label">Nama Orangtua</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control" id="nama_orangtua" name="nama_orangtua"
                                    placeholder="Masukkan nama orangtua" value="{{ old('nama_orangtua') }}" required>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="hubungan_dengan_siswa" class="form-label">Hubungan dengan Siswa</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-people"></i></span>
                                <select class="form-control" id="hubungan_dengan_siswa" name="hubungan_dengan_siswa" required>
                                    <option value="">Pilih Hubungan</option>
                                    <option value="Ayah">Ayah</option>
                                    <option value="Ibu">Ibu</option>
                                    <option value="Wali">Wali</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="no_telp_orangtua" class="form-label">No Telp Orangtua</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                <input type="text" class="form-control" id="no_telp_orangtua" name="no_telp_orangtua"
                                    placeholder="Masukkan nomor telepon orangtua" value="{{ old('no_telp_orangtua') }}" required>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                <textarea class="form-control" id="alamat" name="alamat" placeholder="Masukkan alamat" required>{{ old('alamat') }}</textarea>
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
            $('.edit-siswa').on('click', function() {
                $('#siswaModalLabel').text('Edit Siswa');
                $('#siswa_id').val($(this).data('id'));
                $('#user_id').val($(this).data('user_id'));
                $('#name').val($(this).data('name'));
                $('#email').val($(this).data('email'));
                $('#nisn').val($(this).data('nisn'));
                $('#nis').val($(this).data('nis'));
                $('#kelas_id').val($(this).data('kelas_id'));
                $('#jenis_kelamin').val($(this).data('jenis_kelamin'));
                $('#nama_orangtua').val($(this).data('nama_orangtua'));
                $('#hubungan_dengan_siswa').val($(this).data('hubungan_dengan_siswa'));
                $('#no_telp_orangtua').val($(this).data('no_telp_orangtua'));
                $('#alamat').val($(this).data('alamat'));
                $('#siswaForm').attr('action', "{{ url('siswa') }}/" + $(this).data('id'));
                if (!$('#siswaForm input[name="_method"]').length) {
                    $('#siswaForm').append('<input type="hidden" name="_method" value="PUT">');
                }
            });

            // Reset modal saat tambah data
            $('#siswaModal').on('show.bs.modal', function(event) {
                if (!$(event.relatedTarget).hasClass('edit-siswa')) {
                    $('#siswaModalLabel').text('Tambah Siswa');
                    $('#siswaForm')[0].reset();
                    $('#siswaForm').attr('action', "{{ route('siswa.store') }}");
                    $('#siswaForm').find('input[name="_method"]').remove();
                }
            });
        });
    </script>
@endsection
