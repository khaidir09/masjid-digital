<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\Keuangan;
use App\Models\JadwalSholat;
use App\Models\AppSetting;
use App\Models\RunningText;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.app')]
#[Title('Dashboard Utama')]
class Dashboard extends Component
{
    public $theme_color = 'emerald'; // Default

    public function mount()
    {
        // Ambil settingan warna dari database
        $settings = AppSetting::first();
        if ($settings) {
            $this->theme_color = $settings->theme_color;
        }
    }

    public function changeTheme($color)
    {
        // Simpan ke database
        $settings = AppSetting::first();
        if (!$settings) {
            $settings = AppSetting::create(['nama_masjid' => 'Masjid Kita']);
        }

        $settings->update(['theme_color' => $color]);
        $this->theme_color = $color;

        // Refresh page biar layout berubah warnanya (optional, bisa juga reactive)
        return $this->redirectRoute('dashboard', navigate: true);
    }

    public function render()
    {
        $saldo = Keuangan::where('kategori', 'pemasukan')->sum('nominal') - Keuangan::where('kategori', 'pengeluaran')->sum('nominal');
        $jadwal = JadwalSholat::where('tanggal', date('Y-m-d'))->first();
        $total_info = RunningText::where('is_active', true)->count();

        return view('livewire.dashboard', [
            'saldo' => $saldo,
            'jadwal' => $jadwal,
            'total_info' => $total_info,
        ]);
    }

    public function logout() {
        Auth::logout();
        return redirect()->route('login');
    }
}
