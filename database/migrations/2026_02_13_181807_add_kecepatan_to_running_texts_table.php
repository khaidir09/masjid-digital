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
        Schema::table('running_texts', function (Blueprint $table) {
            // Kita pakai integer 1-10 (1 = Lambat, 10 = Cepat)
            // Default 5 (Sedang)
            $table->tinyInteger('kecepatan')->default(5)->after('tipe');
        });
    }

    public function down(): void
    {
        Schema::table('running_texts', function (Blueprint $table) {
            $table->dropColumn('kecepatan');
        });
    }
};
