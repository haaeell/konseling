<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KriteriaPrioritasSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('kriteria_prioritas')->truncate();
        DB::table('kriteria_prioritas_detail')->truncate();

        // ============================
        // 1. Insert Master Kriteria
        // ============================
        $kriteria = [
            ['kode' => 'TU', 'nama' => 'Tingkat Urgensi Permasalahan', 'bobot' => 40],
            ['kode' => 'DM', 'nama' => 'Dampak Masalah terhadap Siswa', 'bobot' => 30],
            ['kode' => 'KM', 'nama' => 'Kategori Masalah', 'bobot' => 20],
            ['kode' => 'RK', 'nama' => 'Riwayat Konseling', 'bobot' => 10],
        ];

        DB::table('kriteria_prioritas')->insert($kriteria);

        // Ambil ID kriteria setelah insert
        $TU = DB::table('kriteria_prioritas')->where('kode', 'TU')->value('id');
        $DM = DB::table('kriteria_prioritas')->where('kode', 'DM')->value('id');
        $KM = DB::table('kriteria_prioritas')->where('kode', 'KM')->value('id');
        $RK = DB::table('kriteria_prioritas')->where('kode', 'RK')->value('id');

        // ============================
        // 2. Insert Detail Kriteria
        // ============================
        $detail = [

            // ---- Tingkat Urgensi (TU) ----
            ['kriteria_id' => $TU, 'label' => 'Tidak mendesak (0–20)',     'skor' => 20],
            ['kriteria_id' => $TU, 'label' => 'Sedang mendesak (21–50)',  'skor' => 50],
            ['kriteria_id' => $TU, 'label' => 'Mendesak (51–80)',         'skor' => 80],
            ['kriteria_id' => $TU, 'label' => 'Sangat mendesak (81–100)', 'skor' => 100],

            // ---- Dampak Masalah (DM) ----
            ['kriteria_id' => $DM, 'label' => 'Dampak kecil (0–20)',       'skor' => 20],
            ['kriteria_id' => $DM, 'label' => 'Dampak sedang (21–50)',    'skor' => 50],
            ['kriteria_id' => $DM, 'label' => 'Dampak besar (51–80)',     'skor' => 80],
            ['kriteria_id' => $DM, 'label' => 'Dampak sangat besar (81–100)', 'skor' => 100],

            // ---- Kategori Masalah (KM) ----
            ['kriteria_id' => $KM, 'label' => 'Kategori ringan (0–20)',     'skor' => 20],
            ['kriteria_id' => $KM, 'label' => 'Kategori sedang (21–50)',    'skor' => 50],
            ['kriteria_id' => $KM, 'label' => 'Kategori berat (51–80)',     'skor' => 80],
            ['kriteria_id' => $KM, 'label' => 'Kategori sangat berat (81–100)', 'skor' => 100],

            // ---- Riwayat Konseling (RK) ----
            ['kriteria_id' => $RK, 'label' => 'Sering konseling (0–20)',     'skor' => 20],
            ['kriteria_id' => $RK, 'label' => 'Pernah beberapa kali (21–50)', 'skor' => 50],
            ['kriteria_id' => $RK, 'label' => 'Jarang atau 1x (51–80)',       'skor' => 80],
            ['kriteria_id' => $RK, 'label' => 'Belum pernah sama sekali (81–100)', 'skor' => 100],
        ];

        DB::table('kriteria_prioritas_detail')->insert($detail);
    }
}
