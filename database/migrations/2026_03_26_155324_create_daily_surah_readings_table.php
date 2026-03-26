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
        Schema::create('daily_surah_readings', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('waktu_sholat'); // Subuh, Dzuhur, Ashar, Maghrib, Isya
            $table->string('surah');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_surah_readings');
    }
};
