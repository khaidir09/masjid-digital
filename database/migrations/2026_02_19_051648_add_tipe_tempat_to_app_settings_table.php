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
        Schema::table('app_settings', function (Blueprint $table) {
            // Kita gunakan string agar nanti kalau ada tipe lain (seperti Langgar atau Surau) bisa masuk,
            // tapi defaultnya kita set 'Masjid'
            $table->string('tipe_tempat')->default('Masjid')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('app_settings', function (Blueprint $table) {
            $table->dropColumn('tipe_tempat');
        });
    }
};
