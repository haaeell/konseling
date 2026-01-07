<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class SISeeder extends Seeder
{
    public function run(): void
    {
        // ====== DATA DASAR ======
        $tahunAkademikId = DB::table('tahun_akademik')->insertGetId([
            'tahun' => '2024/2025',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $kelasId = DB::table('kelas')->insertGetId([
            'nama' => 'XII IPA 1',
            'tahun_akademik_id' => $tahunAkademikId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ====== SISWA (PAKAI NIS & DOMAIN SMANJA) ======
        $nis = '2024001';
        $nisn = '1234567890';
        $namaSiswa = 'Danti';
        $namaOrtu = 'Pak Andi';

        // User siswa
        $userSiswaId = DB::table('users')->insertGetId([
            'name' => $namaSiswa,
            'email' => $nis . '@smanja.sch.id',
            'password' => Hash::make('password'),
            'role' => 'siswa',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // User orangtua
        $userOrtuId = DB::table('users')->insertGetId([
            'name' => $namaOrtu,
            'email' => 'ortu_' . $nis . '@smanja.sch.id',
            'password' => Hash::make('password'),
            'role' => 'orangtua',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Data siswa
        $siswaId = DB::table('siswa')->insertGetId([
            'user_id' => $userSiswaId,
            'nisn' => $nisn,
            'nis' => $nis,
            'kelas_id' => $kelasId,
            'jenis_kelamin' => 'P',
            'nama_orangtua' => $namaOrtu,
            'no_telp_orangtua' => '08123456789',
            'alamat' => 'Jl. Merdeka No. 10',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Data orangtua
        DB::table('orangtua')->insert([
            'user_id' => $userOrtuId,
            'nama' => $namaOrtu,
            'hubungan_dengan_siswa' => 'Ayah',
            'no_hp' => '08123456789',
            'alamat' => 'Jl. Merdeka No. 10',
            'siswa_id' => $siswaId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ====== GURU ======
        $namaGuru = 'Bu Siti';
        $userGuruId = DB::table('users')->insertGetId([
            'name' => $namaGuru,
            'email' => 'guru_' . strtolower(str_replace(' ', '', $namaGuru)) . '@smanja.sch.id',
            'password' => Hash::make('password'),
            'role' => 'guru',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('guru')->insert([
            'user_id' => $userGuruId,
            'nama' => $namaGuru,
            'nip' => '198001012005012001',
            'jenis_kelamin' => 'P',
            'no_hp' => '08234567890',
            'alamat' => 'Jl. Pendidikan No. 5',
            'role_guru' => 'walikelas',
            'kelas_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $namaGuru = 'Eko Adinuryadin';
        $userGuruId = DB::table('users')->insertGetId([
            'name' => $namaGuru,
            'email' => 'guru_' . strtolower(str_replace(' ', '', $namaGuru)) . '@smanja.sch.id',
            'password' => Hash::make('password'),
            'role' => 'guru',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('guru')->insert([
            'user_id' => $userGuruId,
            'nama' => $namaGuru,
            'nip' => '197805252008011011',
            'jenis_kelamin' => 'L',
            'no_hp' => '08234567893',
            'alamat' => 'Jl. Pendidikan No. 5',
            'role_guru' => 'kepala_sekolah',
            'kelas_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);


        $namaGuru = 'Suraji';
        $userGuruId = DB::table('users')->insertGetId([
            'name' => $namaGuru,
            'email' => 'guru_' . strtolower(str_replace(' ', '', $namaGuru)) . '@smanja.sch.id',
            'password' => Hash::make('password'),
            'role' => 'guru',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('guru')->insert([
            'user_id' => $userGuruId,
            'nama' => $namaGuru,
            'nip' => '196804102005011012',
            'jenis_kelamin' => 'L',
            'no_hp' => '08234567891',
            'alamat' => 'Jl. Pendidikan No. 5',
            'role_guru' => 'bk',
            'kelas_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ================== KRITERIA & SUB KRITERIA ==================

        // 1. Tingkat Urgensi
        $urgensiId = DB::table('kriteria')->insertGetId([
            'nama' => 'Tingkat Urgensi',
            'bobot' => 25,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('sub_kriterias')->insert([
            ['kriteria_id' => $urgensiId, 'nama_sub' => 'Tidak Mendesak', 'skor' => 20, 'created_at' => now(), 'updated_at' => now()],
            ['kriteria_id' => $urgensiId, 'nama_sub' => 'Sedang Mendesak', 'skor' => 40, 'created_at' => now(), 'updated_at' => now()],
            ['kriteria_id' => $urgensiId, 'nama_sub' => 'Mendesak', 'skor' => 70, 'created_at' => now(), 'updated_at' => now()],
            ['kriteria_id' => $urgensiId, 'nama_sub' => 'Sangat Mendesak', 'skor' => 90, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 2. Dampak Masalah
        $dampakId = DB::table('kriteria')->insertGetId([
            'nama' => 'Dampak Masalah',
            'bobot' => 25,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('sub_kriterias')->insert([
            ['kriteria_id' => $dampakId, 'nama_sub' => 'Dampak Kecil', 'skor' => 20, 'created_at' => now(), 'updated_at' => now()],
            ['kriteria_id' => $dampakId, 'nama_sub' => 'Dampak Sedang', 'skor' => 40, 'created_at' => now(), 'updated_at' => now()],
            ['kriteria_id' => $dampakId, 'nama_sub' => 'Dampak Besar', 'skor' => 70, 'created_at' => now(), 'updated_at' => now()],
            ['kriteria_id' => $dampakId, 'nama_sub' => 'Dampak Sangat Besar', 'skor' => 90, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 3. Kategori Masalah
        $kategoriId = DB::table('kriteria')->insertGetId([
            'nama' => 'Kategori Masalah',
            'bobot' => 25,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('sub_kriterias')->insert([
            ['kriteria_id' => $kategoriId, 'nama_sub' => 'Akademik', 'skor' => 20, 'created_at' => now(), 'updated_at' => now()],
            ['kriteria_id' => $kategoriId, 'nama_sub' => 'Karir', 'skor' => 40, 'created_at' => now(), 'updated_at' => now()],
            ['kriteria_id' => $kategoriId, 'nama_sub' => 'Pribadi', 'skor' => 70, 'created_at' => now(), 'updated_at' => now()],
            ['kriteria_id' => $kategoriId, 'nama_sub' => 'Sosial', 'skor' => 90, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 4. Riwayat Konseling
        $riwayatId = DB::table('kriteria')->insertGetId([
            'nama' => 'Riwayat Konseling',
            'bobot' => 25,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('sub_kriterias')->insert([
            ['kriteria_id' => $riwayatId, 'nama_sub' => 'Sudah Sering Konseling', 'skor' => 20, 'created_at' => now(), 'updated_at' => now()],
            ['kriteria_id' => $riwayatId, 'nama_sub' => 'Sudah Beberapa Kali', 'skor' => 40, 'created_at' => now(), 'updated_at' => now()],
            ['kriteria_id' => $riwayatId, 'nama_sub' => 'Jarang Pernah', 'skor' => 70, 'created_at' => now(), 'updated_at' => now()],
            ['kriteria_id' => $riwayatId, 'nama_sub' => 'Belum Pernah Konseling', 'skor' => 90, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
