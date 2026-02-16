<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_sholat', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal')->unique(); // 1 tanggal 1 baris
            $table->time('imsak');
            $table->time('subuh');
            $table->time('terbit');
            $table->time('dhuha');
            $table->time('dzuhur');
            $table->time('ashar');
            $table->time('maghrib');
            $table->time('isya');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_sholat');
    }
};
