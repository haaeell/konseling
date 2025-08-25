<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\User;
use App\Models\Orangtua;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SiswaController extends Controller
{
    public function index()
    {
        $siswa = Siswa::with(['user', 'kelas', 'orangtua'])->get();
        $kelas = Kelas::all();
        return view('siswa.index', compact('siswa', 'kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'nisn' => 'required|string|unique:siswa,nisn',
            'nis' => 'required|string|unique:siswa,nis',
            'kelas_id' => 'required|exists:kelas,id',
            'jenis_kelamin' => 'required|in:L,P',
            'nama_orangtua' => 'required|string|max:255',
            'hubungan_dengan_siswa' => 'required|string|in:Ayah,Ibu,Wali',
            'no_telp_orangtua' => 'required|string|max:20',
            'alamat' => 'required|string',
        ]);

        // Buat user untuk siswa
        $userSiswa = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make('password'), // Default password, bisa diganti
            'role' => 'siswa',
        ]);

        // Buat user untuk orangtua
        $userOrangtua = User::create([
            'name' => $request->nama_orangtua,
            'email' => 'orangtua_' . $request->email, // Email unik untuk orangtua
            'password' => Hash::make('password'), // Default password, bisa diganti
            'role' => 'orangtua',
        ]);

        // Buat data siswa
        $siswa = Siswa::create([
            'user_id' => $userSiswa->id,
            'nisn' => $request->nisn,
            'nis' => $request->nis,
            'kelas_id' => $request->kelas_id,
            'jenis_kelamin' => $request->jenis_kelamin,
            'nama_orangtua' => $request->nama_orangtua,
            'no_telp_orangtua' => $request->no_telp_orangtua,
            'alamat' => $request->alamat,
        ]);

        // Buat data orangtua
        Orangtua::create([
            'user_id' => $userOrangtua->id,
            'nama' => $request->nama_orangtua,
            'hubungan_dengan_siswa' => $request->hubungan_dengan_siswa,
            'no_hp' => $request->no_telp_orangtua,
            'alamat' => $request->alamat,
            'siswa_id' => $siswa->id,
        ]);

        return redirect()->route('siswa.index')->with('success', 'Siswa dan data orangtua berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id);
        $userSiswa = User::findOrFail($siswa->user_id);
        $orangtua = Orangtua::where('siswa_id', $siswa->id)->first();
        $userOrangtua = $orangtua ? User::findOrFail($orangtua->user_id) : null;

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $userSiswa->id,
            'nisn' => 'required|string|unique:siswa,nisn,' . $siswa->id,
            'nis' => 'required|string|unique:siswa,nis,' . $siswa->id,
            'kelas_id' => 'required|exists:kelas,id',
            'jenis_kelamin' => 'required|in:L,P',
            'nama_orangtua' => 'required|string|max:255',
            'hubungan_dengan_siswa' => 'required|string|in:Ayah,Ibu,Wali',
            'no_telp_orangtua' => 'required|string|max:20',
            'alamat' => 'required|string',
        ]);

        // Update user siswa
        $userSiswa->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Update data siswa
        $siswa->update([
            'nisn' => $request->nisn,
            'nis' => $request->nis,
            'kelas_id' => $request->kelas_id,
            'jenis_kelamin' => $request->jenis_kelamin,
            'nama_orangtua' => $request->nama_orangtua,
            'no_telp_orangtua' => $request->no_telp_orangtua,
            'alamat' => $request->alamat,
        ]);

        // Update atau buat data orangtua
        if ($orangtua && $userOrangtua) {
            $userOrangtua->update([
                'name' => $request->nama_orangtua,
                'email' => 'orangtua_' . $request->email, // Update email orangtua
            ]);

            $orangtua->update([
                'nama' => $request->nama_orangtua,
                'hubungan_dengan_siswa' => $request->hubungan_dengan_siswa,
                'no_hp' => $request->no_telp_orangtua,
                'alamat' => $request->alamat,
            ]);
        } else {
            // Buat user orangtua baru jika belum ada
            $userOrangtua = User::create([
                'name' => $request->nama_orangtua,
                'email' => 'orangtua_' . $request->email,
                'password' => Hash::make('password'),
                'role' => 'orangtua',
            ]);

            Orangtua::create([
                'user_id' => $userOrangtua->id,
                'nama' => $request->nama_orangtua,
                'hubungan_dengan_siswa' => $request->hubungan_dengan_siswa,
                'no_hp' => $request->no_telp_orangtua,
                'alamat' => $request->alamat,
                'siswa_id' => $siswa->id,
            ]);
        }

        return redirect()->route('siswa.index')->with('success', 'Siswa dan data orangtua berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $siswa = Siswa::findOrFail($id);
        $userSiswa = User::findOrFail($siswa->user_id);
        $siswa->delete(); // Menghapus siswa, user, dan orangtua terkait karena onDelete('cascade')
        $userSiswa->delete();
        return redirect()->route('siswa.index')->with('success', 'Siswa dan data orangtua berhasil dihapus.');
    }
}
