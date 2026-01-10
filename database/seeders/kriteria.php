<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class kriteria extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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
