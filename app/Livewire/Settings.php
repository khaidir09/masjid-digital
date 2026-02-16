<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\AppSetting;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

#[Layout('components.layouts.app')]
#[Title('Pengaturan Aplikasi')]
class Settings extends Component
{
    use WithFileUploads;

    public $nama_masjid, $alamat, $zona_waktu;
    public $latitude, $longitude;
    public $running_text_speed, $durasi_slide_foto;

    // Uploads
    public $logo, $background_image;
    public $old_logo, $old_background;

    public $video;
    public $old_video; // Ini path dari DB (Video Aktif)
    public $theme_color;

    // Status Upload Video
    public $isVideoSaving = false; // Indikator sedang memindah file

    public $iqomah = [];
    public $koreksi = [];

    public $api_cari_lokasi, $api_jadwal_sholat, $api_hijriah;
    public $kota_id, $kota_nama;

    public function mount()
    {
        $settings = AppSetting::first();

        if ($settings) {
            $this->nama_masjid = $settings->nama_masjid;
            $this->alamat = $settings->alamat;
            $this->latitude = $settings->latitude ?? '0.51788';
            $this->longitude = $settings->longitude ?? '101.44737';
            $this->zona_waktu = $settings->zona_waktu;
            $this->api_cari_lokasi = $settings->api_cari_lokasi ?? 'https://api.myquran.com/v3/sholat/kabkota/cari/';
            $this->api_jadwal_sholat = $settings->api_jadwal_sholat ?? 'https://api.myquran.com/v3/sholat/jadwal/';
            $this->api_hijriah = $settings->api_hijriah ?? 'https://api.myquran.com/v3/cal/hijr/';
            $this->kota_id = $settings->kota_id;
            $this->kota_nama = $settings->kota_nama;
            $this->running_text_speed = $settings->running_text_speed;
            $this->durasi_slide_foto = $settings->durasi_slide_foto;

            // Simpan path lama untuk preview
            $this->old_video = $settings->video_playlist_url;
            $this->old_logo = $settings->logo_path;
            $this->old_background = $settings->background_image;
            $this->theme_color = $settings->theme_color ?? 'emerald';

            $this->iqomah = [
                'subuh' => $settings->iqomah_subuh,
                'dzuhur' => $settings->iqomah_dzuhur,
                'ashar' => $settings->iqomah_ashar,
                'maghrib' => $settings->iqomah_maghrib,
                'isya' => $settings->iqomah_isya,
            ];

            $this->koreksi = [
                'subuh' => $settings->koreksi_subuh,
                'dzuhur' => $settings->koreksi_dzuhur,
                'ashar' => $settings->koreksi_ashar,
                'maghrib' => $settings->koreksi_maghrib,
                'isya' => $settings->koreksi_isya,
            ];
        }
    }

    public function searchCity($query)
    {
        if (strlen($query) < 3) return [];

        $response = Http::get($this->api_cari_lokasi . $query);
        if ($response->json('status')) {
            return $response->json('data');
        }
        return [];
    }

    // [BARU] Fungsi ini otomatis jalan setelah upload temp selesai 100%
    public function updatedVideo()
    {
        $this->validate([
            'video' => 'file|mimetypes:video/mp4,video/quicktime,video/x-msvideo,video/x-matroska|max:204800', // 200MB
        ], [
            'video.max' => 'Ukuran video terlalu besar. Maksimal 200MB.',
            'video.mimetypes' => 'Format video harus MP4, MOV, AVI, atau MKV.'
        ]);

        $this->isVideoSaving = true;

        try {
            $settings = AppSetting::first();

            // Hapus video lama jika ada
            if ($settings->video_playlist_url && Storage::disk('public')->exists($settings->video_playlist_url)) {
                Storage::disk('public')->delete($settings->video_playlist_url);
            }

            // Simpan video baru
            $path = $this->video->store('videos', 'public');

            // Update Database Langsung
            $settings->update(['video_playlist_url' => $path]);

            // Update Preview
            $this->old_video = $path;

            // Reset Input
            $this->video = null;

            session()->flash('video_success', 'Video berhasil diupload dan disimpan!');

        } catch (\Exception $e) {
            $this->addError('video', 'Gagal menyimpan video: ' . $e->getMessage());
        }

        $this->isVideoSaving = false;
    }

    public function save()
    {
        // Validasi Text & Gambar
        $this->validate([
            'nama_masjid' => 'required|string|max:255',
            'latitude' => 'required',
            'longitude' => 'required',
            'theme_color' => 'required|in:emerald,blue,violet,rose,amber',
            'logo' => 'nullable|image|max:2048',
            'background_image' => 'nullable|image|max:5120',
        ]);

        $settings = AppSetting::first();

        $data = [
            'nama_masjid' => $this->nama_masjid,
            'alamat' => $this->alamat,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'zona_waktu' => $this->zona_waktu,
            'api_cari_lokasi' => $this->api_cari_lokasi,
            'api_jadwal_sholat' => $this->api_jadwal_sholat,
            'api_hijriah' => $this->api_hijriah,
            'kota_id' => $this->kota_id,
            'kota_nama' => $this->kota_nama,
            'running_text_speed' => $this->running_text_speed,
            'durasi_slide_foto' => $this->durasi_slide_foto,
            'iqomah_subuh' => $this->iqomah['subuh'],
            'iqomah_dzuhur' => $this->iqomah['dzuhur'],
            'iqomah_ashar' => $this->iqomah['ashar'],
            'iqomah_maghrib' => $this->iqomah['maghrib'],
            'iqomah_isya' => $this->iqomah['isya'],
            'koreksi_subuh' => $this->koreksi['subuh'],
            'koreksi_dzuhur' => $this->koreksi['dzuhur'],
            'koreksi_ashar' => $this->koreksi['ashar'],
            'koreksi_maghrib' => $this->koreksi['maghrib'],
            'koreksi_isya' => $this->koreksi['isya'],
            'theme_color' => $this->theme_color,
        ];

        // Handle Logo
        if ($this->logo) {
            if ($settings->logo_path && Storage::disk('public')->exists($settings->logo_path)) {
                Storage::disk('public')->delete($settings->logo_path);
            }
            $data['logo_path'] = $this->logo->store('logos', 'public');
        }

        // Handle Background
        if ($this->background_image) {
            if ($settings->background_image && Storage::disk('public')->exists($settings->background_image)) {
                Storage::disk('public')->delete($settings->background_image);
            }
            $data['background_image'] = $this->background_image->store('backgrounds', 'public');
        }

        // NOTE: Video tidak dihandle disini lagi karena sudah di updatedVideo()

        $settings->update($data);

        $this->logo = null;
        $this->background_image = null;

        $this->old_logo = $settings->logo_path;
        $this->old_background = $settings->background_image;

        $this->dispatch('theme-changed', color: $this->theme_color);
        session()->flash('message', 'Pengaturan teks & gambar disimpan!');
    }

    public function render()
    {
        return view('livewire.settings');
    }
}
