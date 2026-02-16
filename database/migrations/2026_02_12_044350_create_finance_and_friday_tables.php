<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Keuangan
        Schema::create('keuangan', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->enum('kategori', ['pemasukan', 'pengeluaran']);
            $table->string('sumber_atau_tujuan'); // "Infaq Jumat", "Beli Lampu"
            $table->decimal('nominal', 15, 2); // Support angka besar
            $table->text('keterangan')->nullable();
            $table->string('bukti_path')->nullable();
            // User yang input data ini
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // 4. Informasi Rekening Masjid
        Schema::create('rekenings', function (Blueprint $table) {
            $table->id();
            $table->string('nama_bank');
            $table->string('nama_akun');
            $table->string('nomor_rekening');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        //Petugas Jumat
        Schema::create('petugas_jumat', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('khatib');
            $table->string('imam');
            $table->string('muadzin')->nullable();
            $table->string('bilal')->nullable();
            $table->text('judul_ceramah')->nullable();
            $table->timestamps();
        });

        //Petugas Malam Ramadhan (Tarawih)
        Schema::create('petugas_ramadhan', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->integer('malam_ke');
            $table->string('penceramah');
            $table->string('imam');
            $table->string('muadzin')->nullable();
            $table->string('bilal')->nullable();
            $table->text('judul_ceramah')->nullable();
            $table->timestamps();
        });

        //Petugas Ied
        Schema::create('petugas_ied', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('khatib');
            $table->string('imam');
            $table->string('muadzin')->nullable();
            $table->string('bilal')->nullable();
            $table->text('judul_ceramah')->nullable();
            $table->enum('ied', ['Idul Fitri', 'Idul Adha'])->default('Idul Fitri');
            $table->timestamps();
        });

        //Pengajian Rutin
        Schema::create('pengajian_rutin', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('penceramah');
            $table->text('judul_ceramah')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('petugas_jumat');
        Schema::dropIfExists('petugas_ramadhan');
        Schema::dropIfExists('petugas_ied');
        Schema::dropIfExists('pengajian_rutin');
        Schema::dropIfExists('keuangan');
        Schema::dropIfExists('rekenings');
    }
};
