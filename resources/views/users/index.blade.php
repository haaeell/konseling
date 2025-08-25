@extends('layouts.dashboard')

@section('content')
    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs" id="userTabs">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#tabSiswa">Siswa</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#tabGuru">Guru</a>
                </li>
            </ul>
        </div>

        <div class="card-body tab-content">
            <!-- TAB SISWA -->
            <div class="tab-pane fade show active" id="tabSiswa">
                <button class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#modalUser"
                    onclick="setupModal('create', 'siswa')">
                    + Tambah Siswa
                </button>
                <table id="tableSiswa" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>NISN</th>
                            <th>Kelas</th>
                            <th>Orangtua</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($siswa as $s)
                            <tr>
                                <td>{{ $s->user->name }}</td>
                                <td>{{ $s->nisn }}</td>
                                <td>{{ $s->kelas->nama }}</td>
                                <td>{{ $s->nama_orangtua }}</td>
                                <td>
                                    <button class="btn btn-sm btn-info" data-bs-toggle="modal"
                                        data-bs-target="#modalDetailSiswa{{ $s->id }}">
                                        Detail
                                    </button>
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#modalUser"
                                        onclick="setupModal('edit', 'siswa', {{ $s->id }}, '{{ $s->user->name }}', '{{ $s->user->email }}', '{{ $s->nisn }}', '{{ $s->nis }}', {{ $s->kelas_id }}, '{{ $s->jenis_kelamin }}', '{{ $s->nama_orangtua }}', '{{ $s->no_telp_orangtua }}', '{{ $s->alamat }}', '{{ $s->orangtua && $s->orangtua->user ? $s->orangtua->user->email : '' }}')">
                                        Edit
                                    </button>
                                    <form action="{{ route('users.destroy', $s->user->id) }}" method="POST"
                                        style="display:inline-block;" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Modal Detail Siswa -->
                            <div class="modal fade" id="modalDetailSiswa{{ $s->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">

                                        {{-- Header --}}
                                        <div class="modal-header">
                                            <h5 class="modal-title">Detail Siswa - {{ $s->user->name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>

                                        {{-- Body --}}
                                        <div class="modal-body">

                                            {{-- Data Siswa --}}
                                            <h6 class="mb-3">🧑 Data Siswa</h6>
                                            <div class="mb-2 d-flex">
                                                <div class="fw-bold me-2" style="width:150px;">Nama</div>
                                                <div> : {{ $s->user->name }}</div>
                                            </div>
                                            <div class="mb-2 d-flex">
                                                <div class="fw-bold me-2" style="width:150px;">Email</div>
                                                <div> : {{ $s->user->email }}</div>
                                            </div>
                                            <div class="mb-2 d-flex">
                                                <div class="fw-bold me-2" style="width:150px;">NISN</div>
                                                <div> : {{ $s->nisn }}</div>
                                            </div>
                                            <div class="mb-2 d-flex">
                                                <div class="fw-bold me-2" style="width:150px;">NIS</div>
                                                <div> : {{ $s->nis }}</div>
                                            </div>
                                            <div class="mb-2 d-flex">
                                                <div class="fw-bold me-2" style="width:150px;">Kelas</div>
                                                <div> : {{ $s->kelas->nama }}</div>
                                            </div>
                                            <div class="mb-2 d-flex">
                                                <div class="fw-bold me-2" style="width:150px;">Jenis Kelamin</div>
                                                <div> : {{ $s->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                                            </div>
                                            <div class="mb-2 d-flex">
                                                <div class="fw-bold me-2" style="width:150px;">Alamat</div>
                                                <div> : {{ $s->alamat }}</div>
                                            </div>

                                            <hr>

                                            {{-- Data Orangtua --}}
                                            <h6 class="mb-3">👨‍👩‍👦 Data Orangtua</h6>
                                            <div class="mb-2 d-flex">
                                                <div class="fw-bold me-2" style="width:150px;">Nama</div>
                                                <div> : {{ $s->nama_orangtua }}</div>
                                            </div>
                                            <div class="mb-2 d-flex">
                                                <div class="fw-bold me-2" style="width:150px;">Email</div>
                                                <div>
                                                    :
                                                    {{ $s->orangtua && $s->orangtua->user ? $s->orangtua->user->email : '-' }}
                                                </div>
                                            </div>
                                            <div class="mb-2 d-flex">
                                                <div class="fw-bold me-2" style="width:150px;">No HP</div>
                                                <div> : {{ $s->no_telp_orangtua }}</div>
                                            </div>
                                        </div>

                                        {{-- Footer --}}
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- TAB GURU -->
            <div class="tab-pane fade" id="tabGuru">
                <div class="row mb-2">
                    <div class="col-md-4">
                        <select class="form-control" id="filterRoleGuru">
                            <option value="">Semua Role</option>
                            <option value="kepala_sekolah">Kepala Sekolah</option>
                            <option value="bk">BK</option>
                            <option value="walikelas">Wali Kelas</option>
                        </select>
                    </div>
                    <div class="col-md-8 text-end">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalUser"
                            onclick="setupModal('create', 'guru')">
                            + Tambah Guru
                        </button>
                    </div>
                </div>
                <table id="tableGuru" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>NIP</th>
                            <th>Role Guru</th>
                            <th>Kelas</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($guru as $g)
                            <tr>
                                <td>{{ $g->user->name }}</td>
                                <td>{{ $g->nip }}</td>
                                <td>{{ $g->role_guru }}</td>
                                <td>{{ $g->kelas->nama ?? '-' }}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#modalUser"
                                        onclick="setupModal('edit', 'guru', {{ $g->id }}, '{{ $g->user->name }}', '{{ $g->user->email }}', '{{ $g->nip }}', '{{ $g->jenis_kelamin }}', '{{ $g->no_hp }}', '{{ $g->alamat }}', '{{ $g->role_guru }}', {{ $g->kelas_id ?? 'null' }})">
                                        Edit
                                    </button>
                                    <form action="{{ route('users.destroy', $g->user->id) }}" method="POST"
                                        style="display:inline-block;" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Unified Modal for Create/Update -->
    <div class="modal fade" id="modalUser" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form id="userForm" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="modalTitle"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body row g-2">
                        <input type="hidden" name="role" id="role">
                        <input type="hidden" name="id" id="userId">
                        <div class="col-md-6">
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <div class="form-floating">
                                    <input type="text" name="name" id="name" class="form-control"
                                        placeholder="Nama">
                                    <label for="name">Nama</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <div class="form-floating">
                                    <input type="email" name="email" id="email" class="form-control"
                                        placeholder="Email">
                                    <label for="email">Email</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <div class="form-floating">
                                    <input type="password" name="password" id="password" class="form-control"
                                        placeholder="Password">
                                    <label for="password">Password (isi jika mau ganti)</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 siswa-field">
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="bi bi-card-text"></i></span>
                                <div class="form-floating">
                                    <input type="text" name="nisn" id="nisn" class="form-control"
                                        placeholder="NISN">
                                    <label for="nisn">NISN</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 siswa-field">
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="bi bi-card-text"></i></span>
                                <div class="form-floating">
                                    <input type="text" name="nis" id="nis" class="form-control"
                                        placeholder="NIS">
                                    <label for="nis">NIS</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 guru-field">
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="bi bi-card-text"></i></span>
                                <div class="form-floating">
                                    <input type="text" name="nip" id="nip" class="form-control"
                                        placeholder="NIP">
                                    <label for="nip">NIP</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="bi bi-gender-ambiguous"></i></span>
                                <div class="form-floating">
                                    <select name="jenis_kelamin" id="jenis_kelamin" class="form-control">
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                    <label for="jenis_kelamin">Jenis Kelamin</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 siswa-field">
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <div class="form-floating">
                                    <input type="text" name="nama_orangtua" id="nama_orangtua" class="form-control"
                                        placeholder="Nama Orangtua">
                                    <label for="nama_orangtua">Nama Orangtua</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 siswa-field">
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <div class="form-floating">
                                    <input type="email" name="email_orangtua" id="email_orangtua" class="form-control"
                                        placeholder="Email Orangtua">
                                    <label for="email_orangtua">Email Orangtua</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 siswa-field">
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                <div class="form-floating">
                                    <input type="text" name="no_telp_orangtua" id="no_telp_orangtua"
                                        class="form-control" placeholder="No HP Orangtua">
                                    <label for="no_telp_orangtua">No HP Orangtua</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 guru-field">
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                <div class="form-floating">
                                    <input type="text" name="no_hp" id="no_hp" class="form-control"
                                        placeholder="No HP">
                                    <label for="no_hp">No HP</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="bi bi-building"></i></span>
                                <div class="form-floating">
                                    <select name="kelas_id" id="kelas_id" class="form-control">
                                        <option value="">-</option>
                                        @foreach ($kelas as $k)
                                            <option value="{{ $k->id }}">{{ $k->nama }} -
                                                {{ $k->tahunAkademik->tahun }}</option>
                                        @endforeach
                                    </select>
                                    <label for="kelas_id">Kelas</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 guru-field">
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                                <div class="form-floating">
                                    <select name="role_guru" id="role_guru" class="form-control">
                                        <option value="kepala_sekolah">Kepala Sekolah</option>
                                        <option value="bk">BK</option>
                                        <option value="walikelas">Wali Kelas</option>
                                    </select>
                                    <label for="role_guru">Role Guru</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                <div class="form-floating">
                                    <textarea name="alamat" id="alamat" class="form-control" placeholder="Alamat"></textarea>
                                    <label for="alamat">Alamat</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="submitButton">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(function() {
            const siswaTable = $('#tableSiswa').DataTable();
            const guruTable = $('#tableGuru').DataTable();

            // Role filter for Guru
            $('#filterRoleGuru').on('change', function() {
                guruTable.column(2).search(this.value).draw();
            });

            // Delete confirmation with SweetAlert
            $('.delete-form').on('submit', function(e) {
                e.preventDefault();
                const form = this;
                Swal.fire({
                    title: 'Yakin mau hapus?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        function setupModal(action, role, id = null, name = '', email = '', nisn = '', nis = '', kelas_id = null,
            jenis_kelamin = '', nama_orangtua = '', no_telp_orangtua = '', alamat = '', email_orangtua = '', role_guru = '',
            guru_kelas_id = null) {
            const form = $('#userForm');
            const modalTitle = $('#modalTitle');
            const submitButton = $('#submitButton');

            // Reset form and fields
            form[0].reset();
            $('.siswa-field').hide();
            $('.guru-field').hide();
            $('#kelas_id').prop('required', role === 'siswa');
            $('input[name="_method"]').remove();

            // Set form action
            if (action === 'create') {
                form.attr('action', '{{ route('users.store') }}');
                modalTitle.text('Tambah ' + (role === 'siswa' ? 'Siswa' : 'Guru'));
                submitButton.text('Simpan');
                $('#password').prop('required', true);
            } else {
                form.attr('action', '{{ route('users.update', ':id') }}'.replace(':id', id));
                form.append('<input type="hidden" name="_method" value="PUT">');
                modalTitle.text('Edit ' + (role === 'siswa' ? 'Siswa' : 'Guru'));
                submitButton.text('Update');
                $('#password').prop('required', false);

                // Fill form with existing data
                $('#userId').val(id);
                $('#name').val(name);
                $('#email').val(email);
                $('#nisn').val(nisn);
                $('#nis').val(nis);
                $('#kelas_id').val(kelas_id);
                $('#jenis_kelamin').val(jenis_kelamin);
                $('#nama_orangtua').val(nama_orangtua);
                $('#no_telp_orangtua').val(no_telp_orangtua);
                $('#alamat').val(alamat);
                $('#email_orangtua').val(email_orangtua);
                $('#nip').val(nisn);
                $('#no_hp').val(no_telp_orangtua);
                $('#role_guru').val(role_guru);
                if (role_guru === 'walikelas') {
                    $('#kelas_id').val(guru_kelas_id);
                }
            }

            // Show relevant fields based on role
            $('#role').val(role);
            if (role === 'siswa') {
                $('.siswa-field').show();
                $('#nisn').prop('required', true);
                $('#nis').prop('required', true);
                $('#nama_orangtua').prop('required', true);
                $('#no_telp_orangtua').prop('required', true);
                $('#email_orangtua').prop('required', true);
            } else {
                $('.guru-field').show();
                $('#nip').prop('required', true);
                $('#no_hp').prop('required', true);
                $('#role_guru').prop('required', true);
                $('#kelas_id').prop('required', $('#role_guru').val() === 'walikelas');
            }

            // Handle role_guru change
            $('#role_guru').on('change', function() {
                $('#kelas_id').prop('required', this.value === 'walikelas');
            });
        }
    </script>
@endpush
