<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('theme_colors', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // emerald, blue, dll
            $table->string('label'); // Nama tampilan: Emerald Green, Ocean Blue
            $table->string('main_color'); // Hex: #10b981
            $table->string('dark_color'); // Hex: #064e3b
            $table->string('light_color'); // Hex: #a7f3d0
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('theme_colors');
    }
};
