<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contents', function (Blueprint $table) {
            $table->id();
            $table->enum('category', ['doa', 'hadist', 'quotes'])->default('doa');
            $table->string('judul');
            $table->text('teks_arab')->nullable(); // Khusus doa/hadist
            $table->text('teks_indo');             // Arti atau isi konten
            $table->string('sumber')->nullable();  // Misal: HR. Bukhari atau QS. Al-Baqarah
            $table->boolean('is_active')->default(true);
            $table->integer('durasi')->default(15); // Berapa detik tampil di layar
            $table->timestamps();
        });

        // 1. Tabel Running Text
        Schema::create('running_texts', function (Blueprint $table) {
            $table->id();
            $table->text('teks');
            $table->enum('tipe', ['info', 'ayat', 'hadits', 'ucapan'])->default('info');
            $table->boolean('is_active')->default(true);
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });

        // 2. Tabel Galeri (Album)
        Schema::create('galleries', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kegiatan');
            $table->date('tanggal_kegiatan')->nullable();
            $table->text('deskripsi')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 3. Tabel Foto (Isi Album)
        Schema::create('photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gallery_id')->constrained('galleries')->cascadeOnDelete();
            $table->string('file_path');
            $table->string('caption')->nullable();
            $table->timestamps();
        });

        // 4. Tabel Banner/Info Slide (Poster Kajian dll)
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('judul')->nullable();
            $table->string('image_path');
            $table->date('tgl_mulai')->nullable();
            $table->date('tgl_selesai')->nullable(); // Auto hide kalau lewat tanggal
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contents');
        Schema::dropIfExists('banners');
        Schema::dropIfExists('photos');
        Schema::dropIfExists('galleries');
        Schema::dropIfExists('running_texts');
    }
};
