@extends('layouts.dashboard')

@section('title', 'Manajemen User')

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="bi bi-people-fill me-2"></i> Manajemen User (BK)
            </h5>
        </div>

        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form method="GET" class="row g-2 mb-3">
                <div class="col-md-4">
                    <select name="role" class="form-select" onchange="this.form.submit()">
                        <option value="">-- Semua Role --</option>
                        <option value="guru" {{ request('role') == 'guru' ? 'selected' : '' }}>Guru</option>
                        <option value="siswa" {{ request('role') == 'siswa' ? 'selected' : '' }}>Siswa</option>
                        <option value="orangtua" {{ request('role') == 'orangtua' ? 'selected' : '' }}>Orang Tua</option>
                    </select>
                </div>

                @if (request()->has('role'))
                    <div class="col-md-2">
                        <a href="{{ route('manajemen-user.index') }}" class="btn btn-secondary w-100">
                            Reset
                        </a>
                    </div>
                @endif
            </form>


            <div class="table-responsive">
                <table class="table table-bordered align-middle" id="datatable">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Detail</th>
                            <th width="160">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="fw-semibold">{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge bg-primary text-uppercase">
                                        {{ $user->role }}
                                    </span>
                                </td>
                                <td>
                                    @if ($user->role === 'siswa' && $user->siswa)
                                        NIS: {{ $user->siswa->nis }} <br>
                                        Kelas: {{ $user->siswa->kelas->nama ?? '-' }}
                                    @elseif($user->role === 'orangtua' && $user->orangtua)
                                        Orang Tua dari: {{ $user->orangtua->siswa->nama ?? '-' }}
                                    @elseif($user->role === 'guru' && $user->guru)
                                        {{ strtoupper($user->guru->role_guru) }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <form method="POST" action="{{ route('manajemen-user.reset-password', $user->id) }}"
                                        onsubmit="return confirm('Reset password user ini?')">
                                        @csrf
                                        <button class="btn btn-sm btn-warning w-100">
                                            <i class="bi bi-arrow-repeat"></i> Reset Password
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach

                        @if ($users->isEmpty())
                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    Tidak ada data user
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
