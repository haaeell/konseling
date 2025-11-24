<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('permohonan_konseling', function (Blueprint $table) {

            if (!Schema::hasColumn('permohonan_konseling', 'tingkat_urgensi_label')) {
                $table->string('tingkat_urgensi_label')->nullable();
            }

            if (!Schema::hasColumn('permohonan_konseling', 'tingkat_urgensi_skor')) {
                $table->integer('tingkat_urgensi_skor')->nullable();
            }

            if (!Schema::hasColumn('permohonan_konseling', 'dampak_masalah_label')) {
                $table->string('dampak_masalah_label')->nullable();
            }

            if (!Schema::hasColumn('permohonan_konseling', 'dampak_masalah_skor')) {
                $table->integer('dampak_masalah_skor')->nullable();
            }

            if (!Schema::hasColumn('permohonan_konseling', 'kategori_masalah_label')) {
                $table->string('kategori_masalah_label')->nullable();
            }

            if (!Schema::hasColumn('permohonan_konseling', 'kategori_masalah_skor')) {
                $table->integer('kategori_masalah_skor')->nullable();
            }

            if (!Schema::hasColumn('permohonan_konseling', 'riwayat_konseling_label')) {
                $table->string('riwayat_konseling_label')->nullable();
            }

            if (!Schema::hasColumn('permohonan_konseling', 'riwayat_konseling_skor')) {
                $table->integer('riwayat_konseling_skor')->nullable();
            }

            if (!Schema::hasColumn('permohonan_konseling', 'skor_prioritas')) {
                $table->float('skor_prioritas')->default(0);
            }
        });
    }

    public function down(): void
    {
        Schema::table('permohonan_konseling', function (Blueprint $table) {

            $columns = [
                'tingkat_urgensi_label',
                'tingkat_urgensi_skor',
                'dampak_masalah_label',
                'dampak_masalah_skor',
                'kategori_masalah_label',
                'kategori_masalah_skor',
                'riwayat_konseling_label',
                'riwayat_konseling_skor',
                'skor_prioritas'
            ];

            foreach ($columns as $col) {
                if (Schema::hasColumn('permohonan_konseling', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
