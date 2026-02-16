
<div class="space-y-8 pb-20">

    <div class="flex justify-between items-start">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Pengaturan Masjid</h1>
            <p class="text-slate-500 text-sm mt-1">Konfigurasi identitas masjid, lokasi, dan parameter waktu sholat.</p>
        </div>

        <button wire:click="save" wire:loading.attr="disabled"
            class="fixed bottom-6 right-6 md:static z-50 flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-full shadow-lg hover:shadow-emerald-500/30 transition-all transform active:scale-95">
            <svg wire:loading.remove class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4">
                </path>
            </svg>
            <svg wire:loading class="animate-spin w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                </circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
            <span class="font-semibold">Simpan Perubahan</span>
        </button>
    </div>

    @if (session()->has('message'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
            class="bg-emerald-50 text-emerald-700 px-4 py-3 rounded-xl border border-emerald-200 flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">

        <div class="xl:col-span-2 space-y-8">

            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 left-0 w-1 h-full bg-emerald-500"></div>
                <h3 class="font-bold text-lg text-slate-800 mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                    Identitas Masjid
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="relative">
                        <input type="text" wire:model="nama_masjid" id="nama_masjid"
                            class="floating-input peer block w-full appearance-none rounded-xl border border-slate-300 bg-transparent px-4 py-3 text-slate-900 focus:border-emerald-500 focus:outline-none focus:ring-0 placeholder-transparent"
                            placeholder="Nama Masjid" />
                        <label for="nama_masjid"
                            class="absolute top-3 left-4 text-slate-500 transition-all duration-200 ease-out">Nama
                            Masjid</label>
                        @error('nama_masjid')
                            <span class="text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="relative">
                        <select wire:model="zona_waktu" id="zona_waktu"
                            class="floating-input peer block w-full appearance-none rounded-xl border border-slate-300 bg-transparent px-4 py-3 text-slate-900 focus:border-emerald-500 focus:outline-none focus:ring-0">
                            <option value="Asia/Jakarta">WIB (Asia/Jakarta)</option>
                            <option value="Asia/Makassar">WITA (Asia/Makassar)</option>
                            <option value="Asia/Jayapura">WIT (Asia/Jayapura)</option>
                        </select>
                        <label for="zona_waktu"
                            class="absolute top-3 left-4 text-slate-500 transition-all duration-200 ease-out">Zona
                            Waktu</label>
                    </div>

                    <div class="md:col-span-2 relative">
                        <textarea wire:model="alamat" id="alamat" rows="2"
                            class="floating-input peer block w-full appearance-none rounded-xl border border-slate-300 bg-transparent px-4 py-3 text-slate-900 focus:border-emerald-500 focus:outline-none focus:ring-0 placeholder-transparent"
                            placeholder="Alamat Lengkap"></textarea>
                        <label for="alamat"
                            class="absolute top-3 left-4 text-slate-500 transition-all duration-200 ease-out">Alamat
                            Lengkap</label>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm relative overflow-hidden" wire:ignore
                x-data="mapComponent({
                    lat: @entangle('latitude'),
                    lng: @entangle('longitude')
                })">

                <div class="absolute top-0 left-0 w-1 h-full bg-blue-500"></div>

                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="font-bold text-lg text-slate-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Lokasi Masjid
                        </h3>
                        <p class="text-sm text-slate-500 mt-1">Geser pin atau paste koordinat dari Google Maps.</p>
                    </div>
                </div>

                <div class="relative mb-4">
                    <input type="text" x-model="coordString" @input.debounce.500ms="updateMapFromInput()"
                        id="koordinat"
                        class="floating-input peer block w-full appearance-none rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-0 placeholder-transparent font-mono"
                        placeholder="0.51788, 101.44737" />
                    <label for="koordinat"
                        class="absolute top-3 left-4 text-slate-500 transition-all duration-200 ease-out">Koordinat
                        (Latitude, Longitude)</label>

                    <div class="absolute right-3 top-3 text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                    </div>
                </div>

                <div id="map" class="w-full h-80 rounded-2xl z-0 border border-slate-200 shadow-inner"></div>

                <script>
                    document.addEventListener('alpine:init', () => {
                        Alpine.data('mapComponent', ({
                            lat,
                            lng
                        }) => ({
                            map: null,
                            marker: null,
                            coordString: '',
                            currentLat: lat || 0.51788,
                            currentLng: lng || 101.44737,

                            init() {
                                // Set initial string
                                this.coordString = `${this.currentLat}, ${this.currentLng}`;

                                // Init Leaflet
                                this.map = L.map('map').setView([this.currentLat, this.currentLng], 15);

                                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                    attribution: '&copy; OpenStreetMap contributors'
                                }).addTo(this.map);

                                // Buat Marker (Draggable)
                                this.marker = L.marker([this.currentLat, this.currentLng], {
                                    draggable: true
                                }).addTo(this.map);

                                // Event: Marker Ditarik
                                this.marker.on('dragend', (event) => {
                                    const position = this.marker.getLatLng();
                                    this.updateCoords(position.lat, position.lng);
                                });

                                // Event: Klik di Peta
                                this.map.on('click', (e) => {
                                    this.marker.setLatLng(e.latlng);
                                    this.updateCoords(e.latlng.lat, e.latlng.lng);
                                    // Animasi smooth pan ke lokasi klik
                                    this.map.panTo(e.latlng);
                                });
                            },

                            // Fungsi update data internal & Livewire
                            updateCoords(lat, lng) {
                                // Format 6 desimal biar rapi
                                lat = parseFloat(lat).toFixed(6);
                                lng = parseFloat(lng).toFixed(6);

                                this.coordString = `${lat}, ${lng}`;

                                // Update property Livewire (via entangle)
                                this.currentLat = lat;
                                this.currentLng = lng;
                            },

                            // Fungsi saat user mengetik/paste di input
                            updateMapFromInput() {
                                // Pisahkan Lat & Lng berdasarkan koma
                                const parts = this.coordString.split(',');

                                if (parts.length === 2) {
                                    const lat = parseFloat(parts[0]);
                                    const lng = parseFloat(parts[1]);

                                    if (!isNaN(lat) && !isNaN(lng)) {
                                        // Update Marker
                                        this.marker.setLatLng([lat, lng]);

                                        // Animasi TERBANG (FlyTo) yang keren!
                                        this.map.flyTo([lat, lng], 16, {
                                            animate: true,
                                            duration: 1.5 // Durasi terbang (detik)
                                        });

                                        // Sync ke Livewire
                                        this.currentLat = lat;
                                        this.currentLng = lng;
                                    }
                                }
                            }
                        }))
                    })
                </script>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 left-0 w-1 h-full bg-purple-500"></div>
                <h3 class="font-bold text-lg text-slate-800 mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Parameter Waktu Sholat
                </h3>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-100">
                            <tr>
                                <th class="px-4 py-3 rounded-tl-xl">Waktu</th>
                                <th class="px-4 py-3">Iqomah (Menit)</th>
                                <th class="px-4 py-3 rounded-tr-xl">Koreksi (+/- Menit)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (['subuh', 'dzuhur', 'ashar', 'maghrib', 'isya'] as $waktu)
                                <tr class="border-b border-slate-50 hover:bg-slate-50/50">
                                    <td class="px-4 py-3 font-medium text-slate-700 capitalize">{{ $waktu }}
                                    </td>
                                    <td class="px-4 py-2">
                                        <div class="flex items-center gap-2">
                                            <input type="number" wire:model="iqomah.{{ $waktu }}"
                                                class="w-20 px-3 py-2 rounded-lg border border-slate-300 text-center focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                                            <span class="text-slate-400 text-xs">Menit</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-2">
                                        <input type="number" wire:model="koreksi.{{ $waktu }}"
                                            class="w-20 px-3 py-2 rounded-lg border border-slate-300 text-center focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                            placeholder="0">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <p class="text-xs text-slate-400 mt-3">*Gunakan nilai negatif (contoh: -2) pada Koreksi untuk
                    memundurkan waktu.</p>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm relative overflow-hidden work-sans-all mt-6">
    <div class="absolute top-0 left-0 w-1 h-full bg-emerald-500"></div>

    <h3 class="font-bold text-lg text-slate-800 mb-6 flex items-center gap-2">
        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
        </svg>
        Konfigurasi API & Lokasi
    </h3>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="md:col-span-2 space-y-1">
            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Pilih Kota / Kabupaten (MyQuran)</label>
            <div wire:ignore>
                <select id="kota-select" class="w-full"></select>
            </div>
            <div class="flex items-center gap-2 mt-2 ml-1">
                <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-tight">
                    Kota Aktif: <span class="text-emerald-600 font-black">{{ $kota_nama }} ({{ $kota_id }})</span>
                </p>
            </div>
        </div>

        <div class="relative">
            <input type="text" wire:model="api_cari_lokasi" id="api_cari_lokasi"
                class="floating-input peer block w-full appearance-none rounded-xl border border-slate-300 bg-transparent px-4 py-3 text-slate-900 focus:border-emerald-500 focus:outline-none focus:ring-0 placeholder-transparent"
                placeholder="API Cari Lokasi" />
            <label for="api_cari_lokasi"
                class="absolute top-3 left-4 text-slate-500 transition-all duration-200 ease-out pointer-events-none">API Cari Lokasi</label>
        </div>

        <div class="relative">
            <input type="text" wire:model="api_jadwal_sholat" id="api_jadwal_sholat"
                class="floating-input peer block w-full appearance-none rounded-xl border border-slate-300 bg-transparent px-4 py-3 text-slate-900 focus:border-emerald-500 focus:outline-none focus:ring-0 placeholder-transparent"
                placeholder="API Jadwal Sholat" />
            <label for="api_jadwal_sholat"
                class="absolute top-3 left-4 text-slate-500 transition-all duration-200 ease-out pointer-events-none">API Jadwal Sholat / Hari</label>
        </div>

        <div class="md:col-span-2 relative">
            <input type="text" wire:model="api_hijriah" id="api_hijriah"
                class="floating-input peer block w-full appearance-none rounded-xl border border-slate-300 bg-transparent px-4 py-3 text-slate-900 focus:border-emerald-500 focus:outline-none focus:ring-0 placeholder-transparent"
                placeholder="API Masehi -> Hijriah" />
            <label for="api_hijriah"
                class="absolute top-3 left-4 text-slate-500 transition-all duration-200 ease-out pointer-events-none">API Masehi -> Hijriah</label>
        </div>
    </div>
</div>

        </div>

        <div class="space-y-8">

            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 left-0 w-1 h-full bg-orange-500"></div>
                <h3 class="font-bold text-lg text-slate-800 mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    Logo & Background
                </h3>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Logo Masjid</label>
                    <div class="flex items-center gap-4">
                        <div
                            class="relative w-20 h-20 rounded-full bg-slate-100 border-2 border-slate-200 overflow-hidden shrink-0">
                            @if ($logo)
                                <img src="{{ $logo->temporaryUrl() }}" class="w-full h-full object-cover">
                            @elseif($old_logo)
                                <img src="{{ asset('storage/' . $old_logo) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-300">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                            @endif

                            <div wire:loading wire:target="logo"
                                class="absolute inset-0 bg-black/50 flex items-center justify-center">
                                <svg class="animate-spin w-6 h-6 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <input type="file" wire:model="logo" id="logo_upload" class="hidden"
                                accept="image/jpg, image/jpeg, image/png">
                            <label for="logo_upload"
                                class="cursor-pointer inline-flex px-4 py-2 bg-white border border-slate-300 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50 transition">
                                Ganti Logo
                            </label>
                            <p class="text-xs text-slate-400 mt-2">PNG/JPG Max 2MB.</p>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Background Layar TV</label>

                    <div
                        class="relative w-full h-40 rounded-xl bg-slate-100 border-2 border-dashed border-slate-300 overflow-hidden hover:border-emerald-400 transition group cursor-pointer">
                        <input type="file" wire:model="background_image"
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                            accept="image/jpg, image/jpeg, image/png">

                        @if ($background_image)
                            <img src="{{ $background_image->temporaryUrl() }}" class="w-full h-full object-cover">
                        @elseif($old_background)
                            <img src="{{ asset('storage/' . $old_background) }}"
                                class="w-full h-full object-cover opacity-80">
                        @else
                            <div class="absolute inset-0 flex flex-col items-center justify-center text-slate-400">
                                <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                    </path>
                                </svg>
                                <span class="text-sm font-medium">Klik untuk upload background</span>
                            </div>
                        @endif

                        <div x-data="{ uploading: false, progress: 0 }" x-on:livewire-upload-start="uploading = true"
                            x-on:livewire-upload-finish="uploading = false"
                            x-on:livewire-upload-error="uploading = false"
                            x-on:livewire-upload-progress="progress = $event.detail.progress">
                            <div x-show="uploading" class="absolute bottom-0 left-0 w-full h-1 bg-slate-200">
                                <div class="h-full bg-emerald-500 transition-all duration-200"
                                    :style="'width: ' + progress + '%'"></div>
                            </div>
                            <div x-show="uploading"
                                class="absolute inset-0 bg-black/20 flex items-center justify-center text-white text-sm font-bold backdrop-blur-sm">
                                <span x-text="progress + '%'"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pt-2 border-t border-slate-100 mt-4">
                    <div class="flex justify-between items-center mb-4">
                        <label class="text-sm font-medium text-slate-700">Video Profil / Kajian</label>
                        <span class="text-xs text-slate-400">Format: MP4, Max 200MB</span>
                    </div>

                    @if ($old_video && !$video)
                        <div class="mb-4 bg-slate-50 p-3 rounded-xl border border-slate-200">
                            <p class="text-xs text-slate-500 font-bold mb-2 flex items-center gap-2">
                                <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                                Video Aktif Saat Ini
                            </p>
                            <video class="w-full rounded-lg shadow-sm max-h-64 bg-black" controls>
                                <source src="{{ asset('storage/' . $old_video) }}" type="video/mp4">
                                Browser anda tidak support video tag.
                            </video>
                        </div>
                    @endif

                    @if (session()->has('video_success'))
                        <div
                            class="mb-4 p-3 bg-emerald-50 text-emerald-700 text-sm rounded-lg flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ session('video_success') }}
                        </div>
                    @endif

                    <div class="relative w-full rounded-xl bg-white border-2 border-dashed border-slate-300 overflow-hidden hover:border-emerald-400 transition group"
                        x-data="{
                            uploading: false,
                            progress: 0,
                            fileSize: 0,
                            uploadedSize: 0,
                            isSaving: @entangle('isVideoSaving')
                        }"
                        x-on:livewire-upload-start="
            uploading = true;
            fileSize = $refs.videoInput.files[0].size;
         "
                        x-on:livewire-upload-finish="uploading = false" x-on:livewire-upload-error="uploading = false"
                        x-on:livewire-upload-progress="
            progress = $event.detail.progress;
            uploadedSize = (progress / 100) * fileSize;
         ">

                        <input type="file" wire:model="video" id="video_upload" x-ref="videoInput"
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20" {{-- Disable input saat sedang saving agar user tidak double upload --}}
                            :disabled="uploading || isSaving">

                        <div class="min-h-30 flex flex-col items-center justify-center p-4 text-center">
                            <div
                                class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-slate-400 group-hover:text-emerald-500 transition-colors"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                    </path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-slate-600">
                                {{ $old_video ? 'Ganti Video Profil' : 'Upload Video Profil' }}
                            </span>
                            <p class="text-xs text-slate-400 mt-1">Klik atau drag file MP4 ke sini</p>
                        </div>

                        <div x-show="uploading || isSaving" x-transition
                            class="absolute inset-0 z-30 bg-white/95 backdrop-blur-sm flex flex-col items-center justify-center">

                            <div class="w-64 text-center">

                                <div class="flex justify-between mb-2">
                                    <span class="text-sm font-bold text-emerald-600"
                                        x-text="progress === 100 ? 'Menyimpan ke Database...' : 'Mengupload...'"></span>

                                    <span class="text-sm font-bold text-emerald-600" x-show="progress < 100"
                                        x-text="progress + '%'"></span>
                                    <span class="text-sm font-bold text-emerald-600 animate-pulse"
                                        x-show="progress === 100">100%</span>
                                </div>

                                <div class="w-full bg-slate-200 rounded-full h-3 mb-2 overflow-hidden">
                                    <div class="bg-emerald-500 h-full rounded-full transition-all duration-200 ease-out flex items-center justify-end"
                                        :class="progress === 100 ? 'animate-pulse' : ''"
                                        :style="'width: ' + progress + '%'">
                                    </div>
                                </div>

                                <p class="text-xs text-slate-600 font-mono" x-show="progress < 100">
                                    <span x-text="(uploadedSize / (1024 * 1024)).toFixed(2) + ' MB'"></span> /
                                    <span x-text="(fileSize / (1024 * 1024)).toFixed(2) + ' MB'"></span>
                                </p>

                                <p class="text-xs text-emerald-600 font-semibold animate-pulse mt-2"
                                    x-show="progress === 100">
                                    Mohon tunggu, sedang memproses file...
                                </p>
                            </div>
                        </div>
                    </div>

                    @error('video')
                        <div
                            class="mt-2 p-3 bg-red-50 border border-red-200 rounded-lg text-red-600 text-sm flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 left-0 w-1 h-full bg-slate-500"></div>
                <h3 class="font-bold text-lg text-slate-800 mb-6">Pengaturan Layar</h3>

                <div class="space-y-5">
                    <div>
                        <div class="flex justify-between mb-1">
                            <label class="text-sm font-medium text-slate-700">Kecepatan Teks Berjalan</label>
                            <span class="text-sm font-bold text-emerald-600">{{ $running_text_speed }}</span>
                        </div>
                        <input type="range" wire:model="running_text_speed" min="1" max="20"
                            class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-emerald-600">
                    </div>

                    <div>
                        <div class="flex justify-between mb-1">
                            <label class="text-sm font-medium text-slate-700">Durasi Slide Foto</label>
                            <span class="text-sm font-bold text-emerald-600">{{ $durasi_slide_foto / 1000 }}
                                Detik</span>
                        </div>
                        <input type="range" wire:model="durasi_slide_foto" min="1000" max="10000"
                            step="1000"
                            class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-emerald-600">
                    </div>
                    <div class="mb-6 pb-6 border-b border-slate-100">
                        <label class="text-sm font-medium text-slate-700 mb-3 block">Tema Warna Aplikasi</label>

                        <div class="flex items-center gap-4">
                            @foreach (['emerald', 'blue', 'violet', 'rose', 'amber'] as $color)
                                <label class="cursor-pointer group relative">
                                    <input type="radio" wire:model="theme_color" value="{{ $color }}"
                                        class="sr-only peer">

                                    <div
                                        class="w-10 h-10 rounded-full bg-{{ $color }}-500 peer-checked:ring-4 peer-checked:ring-{{ $color }}-200 peer-checked:scale-110 transition-all shadow-sm flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white opacity-0 peer-checked:opacity-100 transition-opacity"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>

                                    <span
                                        class="text-xs text-slate-500 mt-1 block text-center capitalize opacity-0 group-hover:opacity-100 transition-opacity absolute -bottom-5 left-1/2 -translate-x-1/2">{{ $color }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
@push('scripts')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

<script>
    document.addEventListener('livewire:initialized', () => {
        const select = new TomSelect('#kota-select', {
            valueField: 'id',
            labelField: 'lokasi',
            searchField: 'lokasi',
            placeholder: 'Ketik nama kota (misal: Pekanbaru)...',
            load: function(query, callback) {
                if (!query.length) return callback();
                // Panggil method di Livewire untuk ambil data kota
                @this.searchCity(query).then(data => {
                    callback(data);
                });
            },
            onChange: function(value) {
                let item = this.options[value];
                if (item) {
                    @this.set('kota_id', item.id);
                    @this.set('kota_nama', item.lokasi);
                }
            }
        });

        // Set value awal jika sudah ada
        if ("{{ $kota_id }}") {
            select.addOption({id: "{{ $kota_id }}", lokasi: "{{ $kota_nama }}"});
            select.setValue("{{ $kota_id }}");
        }
    });
</script>
@endpush
