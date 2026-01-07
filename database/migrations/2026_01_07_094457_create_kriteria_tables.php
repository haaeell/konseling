<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kriteria', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->float('bobot');
            $table->timestamps();
        });

        // Tabel Sub Kriteria (Pilihan dropdown-nya)
        Schema::create('sub_kriterias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kriteria_id')->constrained('kriteria')->onDelete('cascade');
            $table->string('nama_sub'); // Contoh: Sangat Mendesak
            $table->integer('skor');    // 90, 70, dst
            $table->timestamps();
        });
    }
};
