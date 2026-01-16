<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\PermohonanKonseling;
use App\Models\PermohonanKriteria;

return new class extends Migration
{
    public function up(): void
    {
        // Populate kriteria labels from permohonan_kriteria
        $permohonanList = PermohonanKonseling::all();

        foreach ($permohonanList as $permohonan) {
            $updateData = [];

            // Get kriteria data from permohonan_kriteria
            $kriteriaData = PermohonanKriteria::where('permohonan_konseling_id', $permohonan->id)->get();

            foreach ($kriteriaData as $pk) {
                if ($pk->kriteria_nama === 'Tingkat Urgensi') {
                    $updateData['tingkat_urgensi_label'] = $pk->sub_kriteria_nama;
                    $updateData['tingkat_urgensi_skor'] = $pk->skor;
                } elseif ($pk->kriteria_nama === 'Dampak Masalah') {
                    $updateData['dampak_masalah_label'] = $pk->sub_kriteria_nama;
                    $updateData['dampak_masalah_skor'] = $pk->skor;
                } elseif ($pk->kriteria_nama === 'Kategori Masalah') {
                    $updateData['kategori_masalah_label'] = $pk->sub_kriteria_nama;
                    $updateData['kategori_masalah_skor'] = $pk->skor;
                } elseif ($pk->kriteria_nama === 'Riwayat Konseling') {
                    $updateData['riwayat_konseling_label'] = $pk->sub_kriteria_nama;
                    $updateData['riwayat_konseling_skor'] = $pk->skor;
                }
            }

            if (!empty($updateData)) {
                $permohonan->update($updateData);
            }
        }
    }

    public function down(): void
    {
        // Reset all kriteria labels to null
        PermohonanKonseling::query()->update([
            'tingkat_urgensi_label' => null,
            'tingkat_urgensi_skor' => null,
            'dampak_masalah_label' => null,
            'dampak_masalah_skor' => null,
            'kategori_masalah_label' => null,
            'kategori_masalah_skor' => null,
            'riwayat_konseling_label' => null,
            'riwayat_konseling_skor' => null,
        ]);
    }
};
