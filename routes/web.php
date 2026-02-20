<?php

use App\Livewire\Dashboard;
use App\Livewire\Auth\Login;
use App\Livewire\BannerManager;
use App\Livewire\ContentManager;
use App\Livewire\GalleryManager;
use App\Livewire\Settings;
use App\Livewire\JadwalSholat;
use App\Livewire\KeuanganMasjid;
use App\Livewire\RunningTextManager;
use App\Livewire\ScheduleManager;
use App\Livewire\UserPengurus;
use App\Livewire\LiveDisplay;
use App\Livewire\PublicKeuangan;
use App\Livewire\ThemeColorManager;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; // Tambahkan ini

// Guest Route
Route::get('/login', Login::class)->name('login')->middleware('guest');
Route::get('/live-display', LiveDisplay::class)->name('live.display');
Route::get('/transparansi-keuangan', PublicKeuangan::class)->name('keuangan.publik');

// Auth Route
Route::middleware('auth')->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard');
    Route::get('/settings', Settings::class)->name('settings');
    Route::get('/jadwal-sholat', JadwalSholat::class)->name('jadwal.sholat');
    Route::get('/user-pengurus', UserPengurus::class)->name('user.pengurus');
    Route::get('/keuangan-masjid', KeuanganMasjid::class)->name('keuangan.masjid');
    Route::get('/running-text', RunningTextManager::class)->name('running.text');
    Route::get('/gallery', GalleryManager::class)->name('gallery');
    Route::get('/schedule', ScheduleManager::class)->name('schedule');
    Route::get('/banner', BannerManager::class)->name('banner');
    Route::get('/doa-hadist', ContentManager::class)->name('doa.hadist');
    Route::get('/settings/themes', ThemeColorManager::class)->name('settings.themes');

    // ROUTE LOGOUT (BARU)
    Route::get('/logout', function () {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect()->route('login');
    })->name('logout');
});
