<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('sub_kriterias', function (Blueprint $table) {
            $table->text('guide_text')->nullable()->after('nama_sub');
        });
    }

    public function down(): void
    {
        Schema::table('sub_kriterias', function (Blueprint $table) {
            $table->dropColumn('guide_text');
        });
    }
};
