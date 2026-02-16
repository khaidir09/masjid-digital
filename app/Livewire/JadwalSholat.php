<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\JadwalSholat as JadwalModel;
use App\Models\AppSetting;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use App\Services\PrayerCalculator; // Pastikan ini di-import jika pakai Service

#[Layout('components.layouts.app')]
#[Title('Jadwal Sholat')]
class JadwalSholat extends Component
{
    use WithPagination;

    public $tahun_generate, $bulan_generate, $bulan_filter;
    public $isGenerating = false;
    public $progress = 0;
    public $statusText = '';
    public $currentDay = 1;
    public $totalDaysInMonth = 0;

    // 1. WAJIB DEKLARASI DI SINI BRO!
    public $currentDate;

    public function mount()
    {
        $this->tahun_generate = date('Y');
        $this->bulan_generate = (int)date('m');
        $this->bulan_filter = (int)date('m');

        // Set default awal agar view tidak error saat load
        $this->currentDate = Carbon::createFromDate($this->tahun_generate, $this->bulan_generate, 1)->format('Y-m-d');
    }

    public function startGenerate()
    {
        $this->isGenerating = true;
        $this->progress = 0;
        $this->currentDay = 1;

        $date = Carbon::createFromDate($this->tahun_generate, $this->bulan_generate, 1);
        $this->totalDaysInMonth = $date->daysInMonth;

        JadwalModel::whereYear('tanggal', $this->tahun_generate)
                    ->whereMonth('tanggal', $this->bulan_generate)
                    ->delete();

        $this->statusText = "Menghubungkan ke API Kemenag...";
        $this->dispatch('process-next-day');
    }

    public function generateNextDay()
    {
        $setting = AppSetting::first();
        $kotaId = $setting->kota_id ?? 'c7e1249ffc03eb9ded908c236bd1996d';

        $dateObj = Carbon::createFromDate($this->tahun_generate, $this->bulan_generate, $this->currentDay);

        // 2. DEFINISIKAN currentDate dan dateStr agar bisa dipakai
        $this->currentDate = $dateObj->format('Y-m-d');
        $dateStr = $this->currentDate;

        $this->statusText = "Sinkronisasi: " . $dateObj->translatedFormat('d F Y');
        $this->progress = round(($this->currentDay / $this->totalDaysInMonth) * 100);

        try {
            // Mengambil URL dari database setting
            $baseUrlJadwal = rtrim($setting->api_jadwal_sholat, '/') . '/';
            $baseUrlHijri  = rtrim($setting->api_hijriah, '/') . '/';

            $resJadwal = Http::timeout(10)->get($baseUrlJadwal . $kotaId . '/' . $dateStr)->json();
            $resHijri = Http::timeout(10)->get($baseUrlHijri . $dateStr)->json();

            if ($resJadwal['status'] && $resHijri['status']) {
                $j = $resJadwal['data']['jadwal'][$dateStr];

                // Pastikan struktur path JSON-nya benar sesuai output API MyQuran v3
                $h = $resHijri['data']['hijr'];

                JadwalModel::create([
                    'tanggal' => $dateStr,
                    'tanggal_hijriah' => $h['today'] ?? $h['hijr']['today'],
                    'imsak'   => $j['imsak'],
                    'subuh'   => $j['subuh'],
                    'terbit'  => $j['terbit'],
                    'dhuha'   => $j['dhuha'],
                    'dzuhur'  => $j['dzuhur'],
                    'ashar'   => $j['ashar'],
                    'maghrib' => $j['maghrib'],
                    'isya'    => $j['isya'],
                ]);
            }
        } catch (\Exception $e) {
            // Opsional: Log error jika ingin debug kenapa gagal
            // \Log::error($e->getMessage());
        }

        if ($this->currentDay < $this->totalDaysInMonth) {
            $this->currentDay++;
            $this->dispatch('process-next-day');
        } else {
            $this->isGenerating = false;
            $this->bulan_filter = $this->bulan_generate;
            session()->flash('message', "Alhamdulillah, Jadwal Bulan " . $dateObj->translatedFormat('F') . " Berhasil Disinkronkan!");
        }
    }

    public function render()
    {
        $jadwal = JadwalModel::whereYear('tanggal', $this->tahun_generate)
            ->when($this->bulan_filter, fn($q) => $q->whereMonth('tanggal', $this->bulan_filter))
            ->orderBy('tanggal', 'asc')
            ->paginate(31);

        return view('livewire.jadwal-sholat', ['data_jadwal' => $jadwal, 'setting' => AppSetting::first()]);
    }
}
