<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('permohonan_kriteria', function (Blueprint $table) {
            $table->id();

            $table->foreignId('permohonan_konseling_id')
                ->constrained('permohonan_konseling')
                ->cascadeOnDelete();

            $table->foreignId('kriteria_id')
                ->constrained('kriteria')
                ->cascadeOnDelete();

            $table->string('kriteria_nama');
            $table->string('sub_kriteria_nama');
            $table->integer('skor');
            $table->decimal('bobot', 5, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permohonan_kriteria');
    }
};
