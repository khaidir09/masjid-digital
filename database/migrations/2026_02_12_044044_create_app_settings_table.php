<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_settings', function (Blueprint $table) {
            $table->id();
            $table->string('nama_masjid');
            $table->text('alamat')->nullable();

            // Koordinat untuk hitung jadwal sholat otomatis (backup offline)
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('zona_waktu')->default('Asia/Jakarta');

            //Api
            $table->string('api_cari_lokasi')->nullable();
            $table->string('api_jadwal_sholat')->nullable();
            $table->string('api_hijriah')->nullable();
            $table->string('kota_nama')->nullable();
            $table->string('kota_id')->nullable();

            // Media
            $table->string('logo_path')->nullable();
            $table->string('background_image')->nullable();   // Default BG kalau tidak ada slide
            $table->string('path_adzan')->default('sounds/adzan.mp3')->nullable();   // Path suara adzan
            $table->string('video_playlist_url')->nullable(); // Default Jika ada video profil/kajian

                                                                 // Konfigurasi Tampilan
            $table->integer('running_text_speed')->default(10);  // Kecepatan text
            $table->integer('durasi_slide_foto')->default(5000); // Milidetik

            // Timer Iqomah (Menit) - Bisa diset beda tiap waktu
            $table->integer('iqomah_subuh')->default(10);   
            $table->integer('iqomah_dzuhur')->default(10);
            $table->integer('iqomah_ashar')->default(10);
            $table->integer('iqomah_maghrib')->default(10);
            $table->integer('iqomah_isya')->default(10);

            // Koreksi Waktu (Jika jadwal otomatis selisih +- menit)
            $table->integer('koreksi_subuh')->default(0);
            $table->integer('koreksi_dzuhur')->default(0);
            $table->integer('koreksi_ashar')->default(0);
            $table->integer('koreksi_maghrib')->default(0);
            $table->integer('koreksi_isya')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_settings');
    }
};
