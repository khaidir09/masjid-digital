<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Menambah kolom pengurus_id setelah id
            // Pastikan tabel 'pengurus' sudah di-migrate sebelumnya!
            $table->foreignId('pengurus_id')
                  ->nullable()
                  ->after('id')
                  ->constrained('pengurus')
                  ->nullOnDelete();

            // Menambah kolom role setelah password
            $table->string('role')
                  ->default('operator')
                  ->after('password');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['pengurus_id']);
            $table->dropColumn(['pengurus_id', 'role']);
        });
    }
};
