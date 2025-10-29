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
            'email' => $nis . '@smanja.ac.id',
            'password' => Hash::make('password'),
            'role' => 'siswa',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // User orangtua
        $userOrtuId = DB::table('users')->insertGetId([
            'name' => $namaOrtu,
            'email' => 'ortu_' . $nis . '@smanja.ac.id',
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
            'email' => 'guru_' . strtolower(str_replace(' ', '', $namaGuru)) . '@smanja.ac.id',
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
            'role_guru' => 'bk',
            'kelas_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ====== KATEGORI KONSELING ======
        $kategoriAkademikId = DB::table('kategori_konseling')->insertGetId([
            'nama' => 'Akademik',
            'skor_prioritas' => 70,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kategori_konseling')->insert([
            ['nama' => 'Pribadi', 'skor_prioritas' => 60, 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Sosial', 'skor_prioritas' => 50, 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Karir', 'skor_prioritas' => 40, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // ====== PERMOHONAN KONSELING (dummy) ======
        DB::table('permohonan_konseling')->insert([
            'siswa_id' => $siswaId,
            'kategori_id' => $kategoriAkademikId,
            'tanggal_pengajuan' => Carbon::now()->toDateString(),
            'deskripsi_permasalahan' => 'Kesulitan memahami materi Matematika.',
            'status' => 'menunggu',
            'rangkuman' => null,
            'tanggal_disetujui' => null,
            'tempat' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
