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

                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- ========== FOTO PROFIL ========== --}}
                    <div class="text-center mb-4">
                        <div class="position-relative d-inline-block">
                            <img id="previewImage"
                                src="{{ $user->foto ? asset('storage/' . $user->foto) : 'https://placehold.co/120x120?text=Foto' }}"
                                alt="Foto Profil" class="rounded-circle shadow-sm"
                                style="width: 120px; height: 120px; object-fit: cover;">

                            <label for="foto"
                                class="btn btn-sm btn-light border position-absolute bottom-0 end-0 rounded-circle"
                                style="width: 35px; height: 35px;">
                                <i class="bi bi-camera-fill text-primary"></i>
                            </label>
                        </div>
                        <input type="file" name="foto" id="foto" accept="image/*" class="d-none">
                        <p class="text-muted small mt-2">Format: JPG, PNG, Max 2MB</p>
                    </div>

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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputFoto = document.querySelector('#foto');
            const previewImg = document.querySelector('#previewImage');

            if (!inputFoto || !previewImg) return;

            inputFoto.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (!file) return;

                const reader = new FileReader();

                reader.onload = function(event) {
                    previewImg.src = event.target.result;
                    // Hilangkan cache, ganti dengan blob sementara agar browser langsung refresh
                    previewImg.style.opacity = '0.8';
                    setTimeout(() => previewImg.style.opacity = '1', 200);
                };

                reader.readAsDataURL(file);
            });
        });
    </script>
@endsection
