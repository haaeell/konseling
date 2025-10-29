<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Orangtua;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $role = $user->role;

        // Ambil data tambahan sesuai role
        $dataTambahan = null;
        if ($role === 'siswa' && $user->siswa) {
            $dataTambahan = $user->siswa;
        } elseif ($role === 'guru' && $user->guru) {
            $dataTambahan = $user->guru;
        } elseif ($role === 'orangtua') {
            $dataTambahan = $user->orangtua()->first();
        }

        return view('profile.edit', compact('user', 'role', 'dataTambahan'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $role = $user->role;

        $rules = ['name' => 'required|string|max:255'];
        if ($request->filled('password')) {
            $rules['password'] = 'confirmed|min:6';
        }

        // Validasi umum
        $request->validate($rules);

        // Update user
        $user->update([
            'name' => $request->name,
            'password' => $request->filled('password') ? Hash::make($request->password) : $user->password,
        ]);

        // Update data tambahan sesuai role
        if ($role === 'siswa' && $user->siswa) {
            $user->siswa->update([
                'no_telp_orangtua' => $request->no_telp_orangtua,
                'nama_orangtua'    => $request->nama_orangtua,
                'alamat'           => $request->alamat,
            ]);
        } elseif ($role === 'guru' && $user->guru) {
            $user->guru->update([
                'no_hp'    => $request->no_hp,
                'alamat'   => $request->alamat,
            ]);
        } elseif ($role === 'orangtua') {
            $ortu = $user->orangtua()->first();
            if ($ortu) {
                $ortu->update([
                    'no_hp'  => $request->no_hp,
                    'alamat' => $request->alamat,
                ]);
            }
        }

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
