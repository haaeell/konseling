@extends('layouts.dashboard')

@section('title', 'Guru')
@section('breadcumb', 'Guru')
@section('content')
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#guruModal">
                    <i class="bi bi-plus-circle"></i> Tambah
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="datatable">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>NIP</th>
                                <th>Jenis Kelamin</th>
                                <th>No HP</th>
                                <th>Alamat</th>
                                <th>Role Guru</th>
                                <th>Kelas (Wali Kelas)</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="guruTable">
                            @foreach ($guru as $g)
                                <tr data-id="{{ $g->id }}">
                                    <td>{{ $g->nama }}</td>
                                    <td>{{ $g->nip }}</td>
                                    <td>{{ $g->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                    <td>{{ $g->no_hp }}</td>
                                    <td>{{ $g->alamat }}</td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $g->role_guru)) }}</td>
                                    <td>{{ $g->kelas ? $g->kelas->nama : '-' }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-warning edit-guru" data-id="{{ $g->id }}"
                                            data-nama="{{ $g->nama }}" data-nip="{{ $g->nip }}"
                                            data-jenis_kelamin="{{ $g->jenis_kelamin }}" data-no_hp="{{ $g->no_hp }}"
                                            data-alamat="{{ $g->alamat }}" data-role_guru="{{ $g->role_guru }}"
                                            data-kelas_id="{{ $g->kelas_id }}" data-user_id="{{ $g->user_id }}"
                                            data-email="{{ $g->user->email }}" data-bs-toggle="modal"
                                            data-bs-target="#guruModal"><i class="bi bi-pencil"></i> Edit</button>
                                        <form action="{{ route('guru.destroy', $g->id) }}" method="POST"
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

    <!-- Modal Guru -->
    <div class="modal fade" id="guruModal" tabindex="-1" aria-labelledby="guruModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="guruModalLabel">Tambah Guru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="guruForm" method="POST" action="{{ route('guru.store') }}">
                    @csrf
                    <input type="hidden" name="id" id="guru_id">
                    <input type="hidden" name="user_id" id="user_id">
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control" id="nama" name="nama"
                                    placeholder="Masukkan nama guru" value="{{ old('nama') }}" required>
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
                            <label for="nip" class="form-label">NIP</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-card-text"></i></span>
                                <input type="text" class="form-control" id="nip" name="nip"
                                    placeholder="Masukkan NIP" value="{{ old('nip') }}" required>
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
                            <label for="no_hp" class="form-label">No HP</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                <input type="text" class="form-control" id="no_hp" name="no_hp"
                                    placeholder="Masukkan nomor HP" value="{{ old('no_hp') }}" required>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                <textarea class="form-control" id="alamat" name="alamat" placeholder="Masukkan alamat" required>{{ old('alamat') }}</textarea>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="role_guru" class="form-label">Role Guru</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-briefcase"></i></span>
                                <select class="form-control" id="role_guru" name="role_guru" required>
                                    <option value="">Pilih Role Guru</option>
                                    <option value="walikelas">Wali Kelas</option>
                                    <option value="bk">BK</option>
                                    <option value="kepala_sekolah">Kepala Sekolah</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group mb-3" id="kelas_field" style="display: none;">
                            <label for="kelas_id" class="form-label">Kelas (Wali Kelas)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-book"></i></span>
                                <select class="form-control" id="kelas_id" name="kelas_id">
                                    <option value="">Pilih Kelas</option>
                                    @foreach ($kelas as $k)
                                        <option value="{{ $k->id }}">{{ $k->nama }}</option>
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
            // Tampilkan/sembunyikan field kelas berdasarkan role_guru
            $('#role_guru').on('change', function() {
                if ($(this).val() === 'walikelas') {
                    $('#kelas_field').show();
                    $('#kelas_id').attr('required', true);
                } else {
                    $('#kelas_field').hide();
                    $('#kelas_id').removeAttr('required').val('');
                }
            });

            // Edit data
            $('.edit-guru').on('click', function() {
                $('#guruModalLabel').text('Edit Guru');
                $('#guru_id').val($(this).data('id'));
                $('#user_id').val($(this).data('user_id'));
                $('#nama').val($(this).data('nama'));
                $('#email').val($(this).data('email'));
                $('#nip').val($(this).data('nip'));
                $('#jenis_kelamin').val($(this).data('jenis_kelamin'));
                $('#no_hp').val($(this).data('no_hp'));
                $('#alamat').val($(this).data('alamat'));
                $('#role_guru').val($(this).data('role_guru'));
                $('#kelas_id').val($(this).data('kelas_id'));
                $('#guruForm').attr('action', "{{ url('guru') }}/" + $(this).data('id'));
                if (!$('#guruForm input[name="_method"]').length) {
                    $('#guruForm').append('<input type="hidden" name="_method" value="PUT">');
                }

                // Tampilkan field kelas jika role_guru adalah walikelas
                if ($(this).data('role_guru') === 'walikelas') {
                    $('#kelas_field').show();
                    $('#kelas_id').attr('required', true);
                } else {
                    $('#kelas_field').hide();
                    $('#kelas_id').removeAttr('required');
                }
            });

            // Reset modal saat tambah data
            $('#guruModal').on('show.bs.modal', function(event) {
                if (!$(event.relatedTarget).hasClass('edit-guru')) {
                    $('#guruModalLabel').text('Tambah Guru');
                    $('#guruForm')[0].reset();
                    $('#guruForm').attr('action', "{{ route('guru.store') }}");
                    $('#guruForm').find('input[name="_method"]').remove();
                    $('#kelas_field').hide();
                    $('#kelas_id').removeAttr('required').val('');
                }
            });
        });
    </script>
@endsection
