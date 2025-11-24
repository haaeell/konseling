<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('permohonan_konseling', function (Blueprint $table) {

            // Hapus foreign key jika masih ada
            if (Schema::hasColumn('permohonan_konseling', 'kategori_id')) {
                try {
                    $table->dropForeign(['kategori_id']);
                } catch (\Exception $e) {
                    // ignore jika foreign key sudah hilang
                }

                // Hapus kolom kategori_id
                $table->dropColumn('kategori_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('permohonan_konseling', function (Blueprint $table) {

            if (!Schema::hasColumn('permohonan_konseling', 'kategori_id')) {
                $table->foreignId('kategori_id')
                    ->nullable()
                    ->constrained('kategori_konseling')
                    ->onDelete('set null');
            }
        });
    }
};
