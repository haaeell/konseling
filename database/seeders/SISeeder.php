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
        // ===== USERS (dummy akun siswa, guru, ortu) =====
        $userSiswa = DB::table('users')->insertGetId([
            'name' => 'Budi',
            'email' => 'budi@siswa.com',
            'password' => Hash::make('password'),
            'role' => 'siswa',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $userOrtu = DB::table('users')->insertGetId([
            'name' => 'andi',
            'email' => 'andi@ortu.com',
            'password' => Hash::make('password'),
            'role' => 'orangtua',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $userGuru = DB::table('users')->insertGetId([
            'name' => 'bk',
            'email' => 'bk@guru.com',
            'password' => Hash::make('password'),
            'role' => 'guru',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ===== Tahun Akademik =====
        $tahunAkademikId = DB::table('tahun_akademik')->insertGetId([
            'tahun' => '2024/2025',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ===== Kelas =====
        $kelasId = DB::table('kelas')->insertGetId([
            'nama' => 'XII IPA 1',
            'tahun_akademik_id' => $tahunAkademikId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ===== Siswa =====
        $siswaId = DB::table('siswa')->insertGetId([
            'user_id' => $userSiswa,
            'nisn' => '1234567890',
            'nis' => '2024001',
            'kelas_id' => $kelasId,
            'jenis_kelamin' => 'L',
            'no_telp_orangtua' => '08123456789',
            'nama_orangtua' => 'Pak Andi',
            'alamat' => 'Jl. Merdeka No. 10',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ===== Orangtua =====
        DB::table('orangtua')->insert([
            'user_id' => $userOrtu,
            'nama' => 'Pak Andi',
            'hubungan_dengan_siswa' => 'Ayah',
            'no_hp' => '08123456789',
            'alamat' => 'Jl. Merdeka No. 10',
            'siswa_id' => $siswaId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ===== Guru =====
        $guruId = DB::table('guru')->insertGetId([
            'user_id' => $userGuru,
            'nama' => 'Bu Siti',
            'nip' => '198001012005012001',
            'jenis_kelamin' => 'P',
            'no_hp' => '08234567890',
            'alamat' => 'Jl. Pendidikan No. 5',
            'role_guru' => 'bk',
            'kelas_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ===== Kategori Konseling =====
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

        // ===== Permohonan Konseling =====
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
