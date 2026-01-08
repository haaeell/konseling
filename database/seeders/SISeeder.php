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

        $data = [
            [
                'nama'  => 'Tingkat Urgensi',
                'bobot' => 25,
                'subs'  => [
                    ['nama' => 'Tidak Mendesak',   'skor' => 20, 'guide' => 'Masalah ringan dan tidak memerlukan penanganan segera'],
                    ['nama' => 'Sedang Mendesak',  'skor' => 40, 'guide' => 'Perlu perhatian namun masih bisa ditunda'],
                    ['nama' => 'Mendesak',         'skor' => 70, 'guide' => 'Perlu segera ditangani agar tidak memburuk'],
                    ['nama' => 'Sangat Mendesak',  'skor' => 90, 'guide' => 'Harus segera ditangani karena berisiko tinggi'],
                ],
            ],
            [
                'nama'  => 'Dampak Masalah',
                'bobot' => 25,
                'subs'  => [
                    ['nama' => 'Dampak Kecil',        'skor' => 20, 'guide' => 'Tidak mengganggu aktivitas utama siswa'],
                    ['nama' => 'Dampak Sedang',       'skor' => 40, 'guide' => 'Mulai mempengaruhi kegiatan belajar'],
                    ['nama' => 'Dampak Besar',        'skor' => 70, 'guide' => 'Mengganggu prestasi dan perilaku siswa'],
                    ['nama' => 'Dampak Sangat Besar', 'skor' => 90, 'guide' => 'Mengganggu kehidupan akademik dan sosial'],
                ],
            ],
            [
                'nama'  => 'Kategori Masalah',
                'bobot' => 25,
                'subs'  => [
                    ['nama' => 'Akademik', 'skor' => 20, 'guide' => 'Masalah terkait nilai atau pembelajaran'],
                    ['nama' => 'Karir',    'skor' => 40, 'guide' => 'Masalah terkait perencanaan masa depan'],
                    ['nama' => 'Pribadi',  'skor' => 70, 'guide' => 'Masalah pribadi atau emosional'],
                    ['nama' => 'Sosial',   'skor' => 90, 'guide' => 'Masalah hubungan sosial atau lingkungan'],
                ],
            ],
            [
                'nama'  => 'Riwayat Konseling',
                'bobot' => 25,
                'subs'  => [
                    ['nama' => 'Sudah Sering Konseling', 'skor' => 20, 'guide' => 'Lebih dari 3 kali dalam 1 bulan'],
                    ['nama' => 'Sudah Beberapa Kali',    'skor' => 40, 'guide' => '1–3 kali dalam 1 bulan'],
                    ['nama' => 'Jarang Pernah',          'skor' => 70, 'guide' => 'Pernah tapi tidak rutin'],
                    ['nama' => 'Belum Pernah Konseling', 'skor' => 90, 'guide' => 'Belum pernah melakukan konseling'],
                ],
            ],
        ];

        // ================== INSERT DATA ==================

        foreach ($data as $kriteria) {
            $kriteriaId = DB::table('kriteria')->insertGetId([
                'nama'       => $kriteria['nama'],
                'bobot'      => $kriteria['bobot'],
            ]);

            foreach ($kriteria['subs'] as $sub) {
                DB::table('sub_kriterias')->insert([
                    'kriteria_id' => $kriteriaId,
                    'nama_sub'    => $sub['nama'],
                    'guide_text'  => $sub['guide'],
                    'skor'        => $sub['skor'],
                ]);
            }
        }
    }
}
