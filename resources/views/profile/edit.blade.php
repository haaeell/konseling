@extends('layouts.dashboard')

@section('title', 'Update Profile')
@section('breadcumb', 'Profile')

@section('content')
    <div class="container-fluid mt-4">
        <div class="card shadow-lg border-0">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0 text-white"><i class="bi bi-person-circle"></i> Update Profile</h5>
            </div>

            <div class="card-body p-3">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf

                    {{-- ========== UMUM ========== --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                    </div>

                    <hr>

                    {{-- ========== SPESIFIK PER ROLE ========== --}}
                    @if ($role === 'siswa' && $dataTambahan)
                        <h6 class="fw-bold text-primary mt-3 mb-2">Data Siswa</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Orang Tua</label>
                                <input type="text" name="nama_orangtua" class="form-control"
                                    value="{{ old('nama_orangtua', $dataTambahan->nama_orangtua) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">No. Telepon Orang Tua</label>
                                <input type="text" name="no_telp_orangtua" class="form-control"
                                    value="{{ old('no_telp_orangtua', $dataTambahan->no_telp_orangtua) }}">
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Alamat</label>
                                <textarea name="alamat" class="form-control" rows="2">{{ old('alamat', $dataTambahan->alamat) }}</textarea>
                            </div>
                        </div>
                    @elseif ($role === 'guru' && $dataTambahan)
                        <h6 class="fw-bold text-primary mt-3 mb-2">Data Guru</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">No. HP</label>
                                <input type="text" name="no_hp" class="form-control"
                                    value="{{ old('no_hp', $dataTambahan->no_hp) }}">
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Alamat</label>
                                <textarea name="alamat" class="form-control" rows="2">{{ old('alamat', $dataTambahan->alamat) }}</textarea>
                            </div>
                        </div>
                    @elseif ($role === 'orangtua' && $dataTambahan)
                        <h6 class="fw-bold text-primary mt-3 mb-2">Data Orang Tua</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">No. HP</label>
                                <input type="text" name="no_hp" class="form-control"
                                    value="{{ old('no_hp', $dataTambahan->no_hp) }}">
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Alamat</label>
                                <textarea name="alamat" class="form-control" rows="2">{{ old('alamat', $dataTambahan->alamat) }}</textarea>
                            </div>
                        </div>
                    @endif

                    <hr>

                    {{-- ========== PASSWORD ========== --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Password Baru</label>
                        <input type="password" name="password" class="form-control"
                            placeholder="Kosongkan jika tidak ingin diubah">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control"
                            placeholder="Ulangi password baru">
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-save"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
