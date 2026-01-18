<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Orangtua;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $siswa = Siswa::with(['user', 'kelas', 'orangtua.user'])->get();
        $guru = Guru::with(['user', 'kelas'])->get();
        $kelas = Kelas::with('tahunAkademik')->get();

        return view('users.index', compact('siswa', 'guru', 'kelas'));
    }

    public function store(Request $request)
    {
        $role = $request->role;

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'nisn' => 'required_if:role,siswa|unique:siswa,nisn',
            'nis' => 'required_if:role,siswa|unique:siswa,nis',
            'nip' => 'required_if:role,guru|unique:guru,nip',
            'kelas_id' => 'required_if:role,siswa',
            'nama_orangtua' => 'required_if:role,siswa',
            'no_telp_orangtua' => 'required_if:role,siswa',
            'email_orangtua' => 'required_if:role,siswa|email|unique:users,email',
            'jenis_kelamin' => 'required|in:L,P',
            'role_guru' => 'required_if:role,guru|in:kepala_sekolah,bk,walikelas',
            'kelas_id' => 'required_if:role_guru,walikelas',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $role
        ]);

        if ($role === 'siswa') {
            $siswa = Siswa::create([
                'user_id' => $user->id,
                'nisn' => $request->nisn,
                'nis' => $request->nis,
                'kelas_id' => $request->kelas_id,
                'jenis_kelamin' => $request->jenis_kelamin,
                'alamat' => $request->alamat,
            ]);

            if ($request->email_orangtua) {
                $parentUser = User::create([
                    'name' => $request->nama_orangtua,
                    'email' => $request->email_orangtua,
                    'password' => Hash::make('123456'),
                    'role' => 'orangtua'
                ]);

                Orangtua::create([
                    'user_id' => $parentUser->id,
                    'nama' => $request->nama_orangtua,
                    'hubungan_dengan_siswa' => 'orangtua',
                    'no_hp' => $request->no_telp_orangtua,
                    'alamat' => $request->alamat,
                    'siswa_id' => $siswa->id
                ]);
            }
        }

        if ($role === 'guru') {
            Guru::create([
                'user_id' => $user->id,
                'nama' => $request->name,
                'nip' => $request->nip,
                'jenis_kelamin' => $request->jenis_kelamin,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
                'role_guru' => $request->role_guru,
                'kelas_id' => $request->role_guru === 'walikelas' ? $request->kelas_id : null,
            ]);
        }

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan!');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => "required|email|unique:users,email,{$user->id}",
            'password' => 'nullable|min:6',
            'nisn' => 'required_if:role,siswa|unique:siswa,nisn,' . ($user->siswa->id ?? 0),
            'nis' => 'required_if:role,siswa|unique:siswa,nis,' . ($user->siswa->id ?? 0),
            'nip' => 'required_if:role,guru|unique:guru,nip,' . ($user->guru->id ?? 0),
            'kelas_id' => 'required_if:role,siswa',
            'nama_orangtua' => 'required_if:role,siswa',
            'no_telp_orangtua' => 'required_if:role,siswa',
            'email_orangtua' => 'nullable|email|unique:users,email,' . ($user->siswa && $user->siswa->orangtua && $user->siswa->orangtua->user ? $user->siswa->orangtua->user->id : 0),
            'jenis_kelamin' => 'required|in:L,P',
            'role_guru' => 'required_if:role,guru|in:kepala_sekolah,bk,walikelas',
            'kelas_id' => 'required_if:role_guru,walikelas',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        if ($request->role === 'siswa') {
            $siswa = Siswa::where('user_id', $user->id)->first();
            $siswa->update([
                'nisn' => $request->nisn,
                'nis' => $request->nis,
                'kelas_id' => $request->kelas_id,
                'jenis_kelamin' => $request->jenis_kelamin,
                'alamat' => $request->alamat,
            ]);

            $orangtua = Orangtua::where('siswa_id', $siswa->id)->first();
            if ($orangtua && $request->email_orangtua) {
                $parentUser = $orangtua->user;
                $parentUser->update([
                    'name' => $request->nama_orangtua,
                    'email' => $request->email_orangtua,
                ]);
                $orangtua->update([
                    'nama' => $request->nama_orangtua,
                    'no_hp' => $request->no_telp_orangtua,
                    'alamat' => $request->alamat,
                ]);
            } elseif (!$orangtua && $request->email_orangtua) {
                $parentUser = User::create([
                    'name' => $request->nama_orangtua,
                    'email' => $request->email_orangtua,
                    'password' => Hash::make('123456'),
                    'role' => 'orangtua'
                ]);

                Orangtua::create([
                    'user_id' => $parentUser->id,
                    'nama' => $request->nama_orangtua,
                    'hubungan_dengan_siswa' => 'orangtua',
                    'no_hp' => $request->no_telp_orangtua,
                    'alamat' => $request->alamat,
                    'siswa_id' => $siswa->id
                ]);
            }
        } elseif ($request->role === 'guru') {
            $guru = Guru::where('user_id', $user->id)->first();
            $guru->update([
                'nama' => $request->name,
                'nip' => $request->nip,
                'jenis_kelamin' => $request->jenis_kelamin,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
                'role_guru' => $request->role_guru,
                'kelas_id' => $request->role_guru === 'walikelas' ? $request->kelas_id : null,
            ]);
        }

        return redirect()->route('users.index')->with('success', 'User berhasil diupdate!');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus!');
    }
}
