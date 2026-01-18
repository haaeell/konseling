<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Siswa;
use App\Models\Orangtua;
use App\Models\Kelas;
use App\Models\TahunAkademik;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SiswaImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // === 1. Cari atau buat kelas ===
        $kelas = Kelas::where('nama', $row['nama_kelas'])->first();

        if (!$kelas) {
            // Ambil tahun akademik terbaru
            $tahunTerbaru = TahunAkademik::orderBy('id', 'desc')->first();

            // Jika belum ada tahun akademik, buat default
            if (!$tahunTerbaru) {
                $tahunTerbaru = TahunAkademik::create([
                    'nama' => '2025/2026',
                    'semester' => 'Ganjil',
                    'status' => 'aktif',
                ]);
            }

            // Buat kelas baru
            $kelas = Kelas::create([
                'nama' => $row['nama_kelas'],
                'tahun_akademik_id' => $tahunTerbaru->id,
            ]);
        }

        // === 2. Buat user siswa ===
        $userSiswa = User::create([
            'name' => $row['name'],
            'email' => $row['email'],
            'password' => Hash::make('password'),
            'role' => 'siswa',
        ]);

        // === 3. Buat user orangtua ===
        $userOrangtua = User::create([
            'name' => $row['nama_orangtua'],
            'email' => 'orangtua_' . $row['email'],
            'password' => Hash::make('password'),
            'role' => 'orangtua',
        ]);

        // === 4. Buat data siswa ===
        $siswa = Siswa::create([
            'user_id' => $userSiswa->id,
            'nisn' => $row['nisn'],
            'nis' => $row['nis'],
            'kelas_id' => $kelas->id,
            'jenis_kelamin' => $row['jenis_kelamin'],
            'alamat' => $row['alamat'],
        ]);

        // === 5. Buat data orangtua ===
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
