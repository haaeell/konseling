<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            // Drop kolom yang sudah duplikasi di table orangtua
            $table->dropColumn(['no_telp_orangtua', 'nama_orangtua']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            // Restore kolom jika rollback
            $table->string('nama_orangtua')->after('jenis_kelamin');
            $table->string('no_telp_orangtua')->after('nama_orangtua');
        });
    }
};
