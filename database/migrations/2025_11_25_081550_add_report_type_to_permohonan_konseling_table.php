<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('permohonan_konseling', function (Blueprint $table) {
            $table->enum('report_type', ['self', 'teacher'])
                ->default('self')
                ->after('riwayat_konseling_skor');
        });
    }

    public function down(): void
    {
        Schema::table('permohonan_konseling', function (Blueprint $table) {
            $table->dropColumn('report_type');
        });
    }
};
