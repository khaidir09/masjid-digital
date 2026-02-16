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
    Schema::table('jadwal_sholat', function (Blueprint $table) {
        $table->string('tanggal_hijriah')->after('tanggal')->nullable();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal_sholat', function (Blueprint $table) {
            //
        });
    }
};
