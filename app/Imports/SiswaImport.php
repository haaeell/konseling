<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Siswa;
use App\Models\Orangtua;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SiswaImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Buat user siswa
        $userSiswa = User::create([
            'name' => $row['name'],
            'email' => $row['email'],
            'password' => Hash::make('password'),
            'role' => 'siswa',
        ]);

        // Buat user orangtua
        $userOrangtua = User::create([
            'name' => $row['nama_orangtua'],
            'email' => 'orangtua_' . $row['email'],
            'password' => Hash::make('password'),
            'role' => 'orangtua',
        ]);

        // Buat data siswa
        $siswa = Siswa::create([
            'user_id' => $userSiswa->id,
            'nisn' => $row['nisn'],
            'nis' => $row['nis'],
            'kelas_id' => $row['kelas_id'],
            'jenis_kelamin' => $row['jenis_kelamin'],
            'nama_orangtua' => $row['nama_orangtua'],
            'no_telp_orangtua' => $row['no_telp_orangtua'],
            'alamat' => $row['alamat'],
        ]);

        // Buat data orangtua
        Orangtua::create([
            'user_id' => $userOrangtua->id,
            'nama' => $row['nama_orangtua'],
            'hubungan_dengan_siswa' => $row['hubungan_dengan_siswa'],
            'no_hp' => $row['no_telp_orangtua'],
            'alamat' => $row['alamat'],
            'siswa_id' => $siswa->id,
        ]);

        return $siswa;
    }
}
