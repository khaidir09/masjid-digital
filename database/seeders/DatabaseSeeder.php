<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AppSetting;
use App\Models\RunningText;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
{
    // 1. Buat Setting Default
    AppSetting::create([
        'nama_masjid' => 'Masjid Raya Darussalam',
        'alamat' => 'Jl. Sudirman No. 1, Pekanbaru',
        'zona_waktu' => 'Asia/Jakarta',
        'running_text_speed' => 10,
    ]);

    // 2. Buat User Superadmin
    User::create([
        'name' => 'Super Admin',
        'email' => 'admin@masjid.com',
        'password' => bcrypt('password'), // Ganti password nanti!
        'role' => 'superadmin',
    ]);

    // 3. Buat Running Text Default
    RunningText::create([
        'teks' => 'Selamat Datang di Masjid Raya Darussalam. Mohon luruskan dan rapatkan shaf.',
        'tipe' => 'info',
        'urutan' => 1
    ]);
}
}
