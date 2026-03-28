<div x-data="displaySystem()" x-init="initSystem()"
    :class="themeMode === 'light' ? 'bg-slate-50 text-slate-800' : 'bg-slate-950 text-white'"
    class="relative w-full h-screen font-sans overflow-hidden flex flex-col transition-colors duration-500">

    @php
        $activeTheme = $settings->theme;

        $theme = [
            'main' => $activeTheme->main_color ?? '#10b981',
            'dark' => $activeTheme->dark_color ?? '#064e3b',
            'light' => $activeTheme->light_color ?? '#a7f3d0',
        ];

        $globalSpeed = $settings->running_text_speed ?? 5;
        $duration = max(10, 65 - $globalSpeed * 5);
        $apiHost = parse_url($settings->api_jadwal_sholat ?? 'https://api.myquran.com', PHP_URL_HOST);
    @endphp

    <style>
        :root {
            --theme-main: {{ $theme['main'] }};
            --theme-dark: {{ $theme['dark'] }};
            --theme-light: {{ $theme['light'] }};
        }

        .bg-theme-dark {
            background-color: var(--theme-dark);
        }

        .bg-theme-main {
            background-color: var(--theme-main);
        }

        .text-theme-main {
            color: var(--theme-main);
        }

        .text-theme-light {
            color: var(--theme-light);
        }

        .border-theme-main {
            border-color: var(--theme-main);
        }

        .shadow-theme-glow {
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.4), 0 0 15px var(--theme-main);
        }

        .shadow-theme-text {
            text-shadow: 0 0 20px var(--theme-main);
        }

        .islamic-pattern {
            background-image: url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.03"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');
        }

        .islamic-pattern-light {
            background-image: url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23000000" fill-opacity="0.03"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');
        }

        .font-arab {
            font-family: 'Amiri Quran', serif !important;
            font-weight: 400;
        }

        .marquee-preview {
            overflow: hidden;
            display: flex;
            align-items: center;
            position: relative;
            width: 100%;
            height: 100%;
        }

        .marquee-content {
            display: flex;
            gap: 5rem;
            width: max-content;
            animation: marquee linear infinite;
            padding-left: 100%;
        }

        .marquee-item {
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        @keyframes marquee {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-100%);
            }
        }

        @keyframes spin-slow {
            100% {
                transform: rotate(360deg);
            }
        }

        .animate-spin-slow {
            animation: spin-slow 8s linear infinite;
        }

        .dynamic-stack {
            display: grid;
        }

        .dynamic-stack>* {
            grid-area: 1 / 1;
        }

        .bg-frame {
            background-image: url('{{ asset('assets/images/frame.png') }}');
            background-size: cover;
            background-position: center;
        }
    </style>

    <div x-show="!started"
        class="absolute inset-0 z-[100] bg-slate-950 flex flex-col items-center justify-center backdrop-blur-3xl">
        <div class="text-center">
            <svg class="w-32 h-32 text-theme-main mx-auto mb-6 animate-pulse" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z">
                </path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h1 class="text-5xl font-black text-white uppercase tracking-widest mb-4">Display TV Ready</h1>
            <p class="text-slate-400 mb-8 font-bold tracking-widest">Mode: {{ $tipeTempat }}</p>
            <button @click="startDisplay()"
                class="bg-theme-main text-white px-12 py-[2vh] rounded-full font-black text-[2.5vh] uppercase tracking-[0.2em] shadow-theme-glow hover:scale-105 transition-all">
                Mulai
            </button>
            
            <div class="mt-8 flex items-center justify-center gap-4">
                <button @click="setTheme('dark')"
                    :class="themeMode === 'dark' ? 'ring-2 ring-theme-main bg-slate-800' : 'bg-slate-900/50 hover:bg-slate-800'"
                    class="px-6 py-3 rounded-xl font-bold text-white transition-all flex items-center gap-2 border border-white/10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                    Dark Mode
                </button>
                <button @click="setTheme('light')"
                    :class="themeMode === 'light' ? 'ring-2 ring-theme-main bg-white text-slate-900' : 'bg-slate-900/50 text-slate-300 hover:bg-slate-800'"
                    class="px-6 py-3 rounded-xl font-bold transition-all flex items-center gap-2 border border-white/10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    Light Mode
                </button>
            </div>
        </div>
    </div>

    <div class="absolute inset-0 z-0 transition-opacity duration-500">
        <div class="w-full h-full"
             :style="themeMode === 'light' 
                ? 'background: radial-gradient(ellipse at top right, var(--theme-light), #f8fafc, #f1f5f9); opacity: 0.3;' 
                : 'background: radial-gradient(ellipse at top right, var(--theme-dark), #0f172a, #000000);'">
        </div>
        <div class="absolute inset-0" :class="themeMode === 'light' ? 'islamic-pattern-light' : 'islamic-pattern'"></div>
    </div>

    <header x-show="mode === 'standby'"
        :class="themeMode === 'light' ? 'bg-white/80 border-slate-200' : 'bg-black/40 border-white/10'"
        class="relative w-full z-20 flex justify-between items-center px-[3vw] py-[1.5vh] backdrop-blur-xl border-b shadow-xl shrink-0 transition-colors duration-500">
        <div class="flex-1 flex items-center justify-start">
            <div class="flex flex-col justify-center">
                <h1 :class="themeMode === 'light' ? 'text-slate-900 shadow-none' : 'text-white shadow-theme-text'"
                    class="text-[3.5vh] font-black tracking-tighter uppercase leading-none transition-colors duration-500">
                    {{ $settings->nama_masjid ?? 'MASJID DIGITAL' }}
                </h1>
                <p :class="themeMode === 'light' ? 'text-slate-600' : 'text-slate-300'"
                   class="text-[1.5vh] font-medium mt-1 opacity-90 line-clamp-1 transition-colors duration-500">
                    {{ $settings->alamat ?? 'Alamat tempat belum dikonfigurasi' }}
                </p>
            </div>
        </div>
        <div class="flex-1 text-right">
            <div class="text-[2.5vh] font-bold uppercase tracking-wide leading-tight">
                <span x-text="dateGregorian" class="text-theme-main"></span> / <span :class="themeMode === 'light' ? 'text-slate-600' : 'text-slate-300'">{{ $jadwal->tanggal_hijriah ?? '' }}</span>
            </div>
        </div>
        <div class="flex-1 text-right">
            <div :class="themeMode === 'light' ? 'text-slate-900' : 'text-white'"
                 class="text-[5vh] font-black tracking-widest leading-none drop-shadow-2xl tabular-nums transition-colors duration-500"
                x-text="time">00:00:00</div>
        </div>
    </header>

    <main class="relative z-10 w-full flex-1 flex overflow-hidden p-[2vh] gap-[2vh]" 
      x-show="mode === 'standby'">
    
    <div class="w-full max-w-[1280px] min-w-[640px] flex flex-col gap-[2vh]">
        <div class="relative aspect-video w-full rounded-[2rem] md:rounded-[3rem] overflow-hidden shadow-2xl border border-white/10 bg-black flex flex-col">
            
            <div class="absolute inset-0 z-0">
            @if ($banners->isNotEmpty())
                @foreach ($banners as $index => $banner)
                    <div class="absolute inset-0 w-full h-full" x-show="activeSlide === {{ $index }}" x-transition.duration.1000ms>
                        <img src="{{ Storage::url($banner->image_path) }}" class="w-full h-full object-fit">
                    </div>
                @endforeach
            @endif
        </div>

        <div class="relative mt-auto w-full p-6 md:p-10 z-20 flex flex-col gap-4">
            </div>
        </div>

        <div class="relative w-full z-20 shrink-0">
            <div
                :class="themeMode === 'light' ? 'bg-white/90 border-slate-800 shadow-xl ring-slate-700' : 'bg-black/60 border-white/20 shadow-[0_15px_40px_rgba(0,0,0,0.6)] ring-white/10'"
                class="h-[8vh] backdrop-blur-2xl border rounded-[4vh] flex items-center overflow-hidden ring-1 transition-colors duration-500">
                <div
                    :class="themeMode === 'light' ? 'border-slate-200 shadow-[10px_0_30px_rgba(0,0,0,0.1)]' : 'border-white/20 shadow-[10px_0_30px_rgba(0,0,0,0.8)]'"
                    class="bg-theme-main h-full flex items-center px-[2vw] z-30 border-r relative transition-colors duration-500">
                    <span class="text-white font-black uppercase tracking-[0.1em] text-[2.5vh] relative z-10">Informasi</span>
                </div>
                <div class="marquee-preview flex-1">
                    <div class="marquee-content font-black text-[3.5vh] uppercase tracking-[0.05em]"
                        style="animation-duration: {{ $duration }}s;">
                        @forelse($runningTexts as $item)
                            @php
                                $marqueeTheme = match ($item->tipe) {
                                    'ayat' => ['text' => 'text-cyan-400', 'dot' => 'text-cyan-500'],
                                    'hadits' => ['text' => 'text-purple-400', 'dot' => 'text-purple-500'],
                                    'ucapan' => ['text' => 'text-amber-400', 'dot' => 'text-amber-500'],
                                    default => ['text' => 'text-theme-main', 'dot' => 'text-theme-main'],
                                };
                            @endphp
                            <div class="marquee-item {{ $marqueeTheme['text'] }} drop-shadow-md transition-colors duration-500">

                                <svg class="w-[4vh] h-[4vh] {{ $marqueeTheme['dot'] }} animate-spin-slow shrink-0 transition-colors duration-500"
                                    viewBox="0 0 24 24" fill="currentColor">
                                    <path
                                        d="M12,2L14.47,4.53L17.94,3.53L18.94,7.06L22.47,8.53L21.47,12L22.47,15.47L18.94,16.94L17.94,20.47L14.47,19.47L12,22L9.53,19.47L6.06,20.47L5.06,16.94L1.53,15.47L2.53,12L1.53,8.53L5.06,7.06L6.06,3.53L9.53,4.53L12,2Z" />
                                </svg>

                                <span class="px-4">{{ $item->teks }}</span>
                            </div>
                        @empty
                            <div :class="themeMode === 'light' ? 'text-slate-600' : 'text-slate-400'" class="marquee-item transition-colors duration-500">
                                <svg :class="themeMode === 'light' ? 'text-slate-400' : 'text-slate-500'" class="w-[4vh] h-[4vh] animate-spin-slow shrink-0 transition-colors duration-500" viewBox="0 0 24 24"
                                    fill="currentColor">
                                    <path
                                        d="M12,2L14.47,4.53L17.94,3.53L18.94,7.06L22.47,8.53L21.47,12L22.47,15.47L18.94,16.94L17.94,20.47L14.47,19.47L12,22L9.53,19.47L6.06,20.47L5.06,16.94L1.53,15.47L2.53,12L1.53,8.53L5.06,7.06L6.06,3.53L9.53,4.53L12,2Z" />
                                </svg>
                                <span class="px-4 normal-case font-bold">Selamat Datang di
                                    {{ $settings->nama_masjid ?? 'Masjid Digital' }}.</span>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="w-[30vw] flex flex-col gap-[2vh] h-full min-w-0">
    
    <div class="flex-1 flex flex-col gap-[1.5vh] min-h-0">
        @php $waktuSholat = ['Subuh', 'Isyraq', 'Dhuha', 'Dzuhur', 'Ashar', 'Maghrib', 'Isya']; @endphp
        @foreach ($waktuSholat as $waktu)
            @php
                $field = strtolower($waktu);
                $jamFormatted = ($waktu === 'Isyraq') 
                    ? \Carbon\Carbon::parse($jadwal->terbit ?? '00:00:00')->addMinutes(12)->format('H:i')
                    : \Carbon\Carbon::parse($jadwal->$field ?? '00:00:00')->format('H:i');
                $isSunnah = in_array($waktu, ['Isyraq', 'Dhuha']);

                $lightBorder = match($waktu) {
                    'Subuh' => 'border-cyan-500/50',
                    'Dzuhur' => 'border-emerald-500/50',
                    'Ashar' => 'border-orange-500/50',
                    'Maghrib' => 'border-rose-500/50',
                    'Isya' => 'border-indigo-500/50',
                    default => 'border-slate-200'
                };
                
                $darkBorder = match($waktu) {
                    'Subuh' => 'border-cyan-500/30',
                    'Dzuhur' => 'border-emerald-500/30',
                    'Ashar' => 'border-orange-500/30',
                    'Maghrib' => 'border-rose-500/30',
                    'Isya' => 'border-indigo-500/30',
                    default => 'border-white/10'
                };
            @endphp

            <div class="flex-1 flex justify-between items-center px-[2vw] rounded-[2vh] border-4 transition-all duration-500 relative overflow-hidden backdrop-blur-md"
                :class="[
                    nextPrayerName === '{{ $waktu }}' 
                        ? 'bg-theme-main border-theme-main shadow-theme-glow z-10 scale-[1.03]' 
                        : (themeMode === 'light' 
                            ? ('{{ $isSunnah }}' ? 'border-amber-500/30 bg-amber-50' : 'bg-white/80 {{ $lightBorder }}')
                            : ('{{ $isSunnah }}' ? 'border-amber-500/20 bg-amber-950/20' : 'bg-black/40 {{ $darkBorder }}'))
                ]">
                
                <span class="text-[3vh] font-bold uppercase tracking-wider transition-colors duration-500"
                    :class="[
                        nextPrayerName === '{{ $waktu }}' 
                            ? 'text-white' 
                            : (themeMode === 'light' 
                                ? ('{{ $isSunnah }}' ? 'text-amber-600' : 'text-slate-600')
                                : ('{{ $isSunnah }}' ? 'text-amber-600' : 'text-slate-300'))
                    ]">
                    {{ $waktu }}
                </span>

                <span class="text-[5vh] font-black tabular-nums transition-colors duration-500"
                    :class="[
                        nextPrayerName === '{{ $waktu }}' 
                            ? 'text-white' 
                            : (themeMode === 'light'
                                ? ('{{ $isSunnah }}' ? 'text-amber-500' : 'text-theme-dark')
                                : ('{{ $isSunnah }}' ? 'text-amber-400' : 'text-theme-main'))
                    ]">
                    {{ $jamFormatted }}
                </span>
            </div>
        @endforeach
    </div>

</div>
</main>

    <div x-show="mode === 'menuju_adzan'" style="display: none;"
        :class="themeMode === 'light' ? 'bg-white/95' : 'bg-black/95'"
        class="bg-frame absolute inset-0 z-50 flex flex-col items-center justify-center backdrop-blur-3xl transition-colors duration-500">
        <h2 :class="themeMode === 'light' ? 'text-slate-600' : 'text-slate-400'" class="text-[6vh] md:text-[8vh] px-[4vw] text-center font-bold uppercase tracking-[0.5em] mb-[3vh] transition-colors duration-500">Menjelang <span
                x-text="currentPrayerName" :class="themeMode === 'light' ? 'text-slate-900' : 'text-white'" class="transition-colors duration-500"></span></h2>
        {{-- <p :class="themeMode === 'light' ? 'text-theme-dark' : 'text-theme-main'" class="text-[4vh] mb-[4vh] tracking-widest uppercase font-black animate-pulse transition-colors duration-500">Menuju Adzan</p> --}}
        <h1 :class="themeMode === 'light' ? 'text-slate-900 shadow-none' : 'text-white shadow-theme-text drop-shadow-[0_0_50px_rgba(16,185,129,0.5)]'" class="text-[30vh] font-black leading-none tabular-nums transition-colors duration-500"
            x-text="countdownAdzanDisplay">00:00</h1>
    </div>

    <div x-show="mode === 'adzan'" style="display: none;"
        :class="themeMode === 'light' ? 'bg-white/95' : 'bg-black/95'"
        class="bg-frame fixed inset-0 z-[100] flex flex-col items-center justify-center backdrop-blur-3xl transition-colors duration-500">
        <h2 :class="themeMode === 'light' ? 'text-slate-600' : 'text-slate-400'" class="text-[6vh] md:text-[8vh] px-[4vw] text-center font-bold uppercase tracking-[0.5em] mb-[3vh] transition-colors duration-500">Waktu <span
                x-text="currentPrayerName" :class="themeMode === 'light' ? 'text-slate-900' : 'text-white'" class="transition-colors duration-500"></span></h2>
        <p :class="themeMode === 'light' ? 'text-theme-dark' : 'text-theme-main'" class="text-[5vh] mb-[4vh] tracking-widest uppercase font-black animate-pulse transition-colors duration-500">Telah Masuk</p>
    </div>

    <div x-show="mode === 'waiting_iqomah'" style="display: none;"
        :class="themeMode === 'light' ? 'bg-white/95' : 'bg-black/95'"
        class="bg-frame fixed inset-0 z-[100] flex flex-col items-center justify-center backdrop-blur-3xl transition-colors duration-500">
        <p :class="themeMode === 'light' ? 'text-theme-dark' : 'text-theme-main'" class="text-[6vh] mb-[4vh] tracking-widest uppercase font-black transition-colors duration-500">Menuju Iqomah:</p>
        <h1 :class="themeMode === 'light' ? 'text-slate-900 shadow-none' : 'text-white shadow-theme-text'" class="text-[30vh] font-black leading-none tabular-nums transition-colors duration-500"
            x-text="countdownIqomahDisplay">00:00</h1>
        {{-- Himbauan Isi Shaf Kosong, Luruskan dan Rapatkan --}}
        <p :class="themeMode === 'light' ? 'text-theme-dark' : 'text-theme-main'" class="text-[3vh] mt-[4vh] tracking-wider font-black animate-pulse transition-colors duration-500">Mohon isi shaf kosong di depan dan senyapkan alat komunikasi</p>
    </div>

    <div x-show="mode === 'sholat'" style="display: none;"
        class="bg-black fixed inset-0 z-[100] flex flex-col items-center justify-center w-full h-full transition-colors duration-500 overflow-hidden">
        <p class="text-[6vh] font-black tracking-widest tabular-nums text-slate-600" x-text="time">00:00:00</p>
    </div>

    <audio id="audio-beep" src="{{ asset('sounds/beep.mp3') }}" preload="auto"></audio>
    <audio id="audio-adzan"
        src="{{ isset($settings->path_adzan) && $settings->path_adzan ? Storage::url($settings->path_adzan) : asset('sounds/adzan.mp3') }}"
        preload="auto"></audio>
    <audio id="audio-adzan-subuh"
        src="{{ isset($settings->path_adzan_subuh) && $settings->path_adzan_subuh ? Storage::url($settings->path_adzan_subuh) : asset('sounds/adzan_subuh.mp3') }}"
        preload="auto"></audio>

    <script>
        function displaySystem() {
            return {
                themeMode: localStorage.getItem('liveDisplayTheme') || 'dark',
                started: false,
                wakeLock: null,
                time: '00:00:00',
                dateGregorian: '',
                mode: 'standby',
                nextPrayerName: '',
                currentPrayerName: '',
                activeSlide: 0,
                activeContent: 0,
                activeCeramah: 0,
                activeRekening: 0,
                tipeTempat: '{{ $tipeTempat }}',
                jadwalDB: {{ Illuminate\Support\Js::from($jadwal) }},
                settings: {{ Illuminate\Support\Js::from($settings) }},
                bannersCount: Number("{{ $banners->count() }}"),
                contentsCount: Number("{{ $contents->count() }}"),
                ceramahCount: Number("{{ $ceramah->count() }}"),
                rekeningCount: Number("{{ $rekenings->count() }}"),
                countdownAdzanDisplay: '00:00',
                countdownIqomahDisplay: '00:00',
                countdownSholatDisplay: '00:00',
                durasiSholat: {
                    'Subuh': 10,
                    'Isyraq': 0,
                    'Dhuha': 0,
                    'Dzuhur': 10,
                    'Ashar': 10,
                    'Maghrib': 10,
                    'Isya': 10,
                    'Jumat': 45
                },

                startDisplay() {
                    this.started = true;
                    this.requestWakeLock();
                    let beep = document.getElementById('audio-beep');
                    let adzan = document.getElementById('audio-adzan');
                    if (beep) {
                        beep.play().then(() => beep.pause()).catch(() => {});
                    }
                    if (adzan) {
                        adzan.play().then(() => adzan.pause()).catch(() => {});
                    }
                    if (document.documentElement.requestFullscreen) {
                        document.documentElement.requestFullscreen().catch(() => {});
                    }
                },

                setTheme(mode) {
                    this.themeMode = mode;
                    localStorage.setItem('liveDisplayTheme', mode);
                },

                async requestWakeLock() {
                    if ('wakeLock' in navigator) {
                        try {
                            this.wakeLock = await navigator.wakeLock.request('screen');
                            this.wakeLock.addEventListener('release', () => {
                                console.log('Screen Wake Lock was released');
                            });
                            console.log('Screen Wake Lock is active');
                        } catch (err) {
                            console.error(`Wake Lock error: ${err.name}, ${err.message}`);
                        }
                    } else {
                        console.warn('Screen Wake Lock API not supported');
                    }
                },

                initSystem() {
                    document.addEventListener('visibilitychange', async () => {
                        if (this.wakeLock !== null && document.visibilityState === 'visible' && this.started) {
                            await this.requestWakeLock();
                        }
                    });

                    this.updateTime();
                    setInterval(() => this.updateTime(), 1000);

                    if (this.bannersCount > 1) {
                        setInterval(() => {
                            this.activeSlide = (this.activeSlide + 1) % this.bannersCount;
                        }, this.settings.durasi_slide_foto || 10000);
                    }
                    if (this.contentsCount > 1) {
                        setInterval(() => {
                            this.activeContent = (this.activeContent + 1) % this.contentsCount;
                        }, 15000);
                    }
                    if (this.ceramahCount > 1) {
                        setInterval(() => {
                            this.activeCeramah = (this.activeCeramah + 1) % this.ceramahCount;
                        }, 12000);
                    }

                    const totalKasSlides = 1 + this.rekeningCount;
                    if (totalKasSlides > 1) {
                        setInterval(() => {
                            this.activeRekening = (this.activeRekening + 1) % totalKasSlides;
                        }, 8000);
                    }

                    setInterval(() => {
                        let now = new Date();
                        if (now.getHours() === 0 && now.getMinutes() === 1 && now.getSeconds() === 0) window
                            .location.reload();
                    }, 1000);
                },

                updateTime() {
                    const now = new Date();
                    this.time = now.toTimeString().substring(0, 8);
                    const options = {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    };
                    this.dateGregorian = now.toLocaleDateString('id-ID', options);
                    this.checkDatabasePrayerTime(now);
                },

                checkDatabasePrayerTime(now) {
                    if (!this.jadwalDB || !this.started) return;

                    const currentSeconds = (now.getHours() * 3600) + (now.getMinutes() * 60) + now.getSeconds();
                    const prayers = [
                        {
                            name: 'Subuh',
                            jamDB: this.jadwalDB.subuh,
                            iqomah: this.settings.iqomah_subuh || 10
                        },
                        {
                            name: 'Isyraq',
                            jamDB: (() => {
                                if (!this.jadwalDB.terbit) return null;
                                let tParts = this.jadwalDB.terbit.split(':');
                                let dateObj = new Date();
                                dateObj.setHours(parseInt(tParts[0]), parseInt(tParts[1]) + 12, tParts[2] ? parseInt(tParts[2]) : 0);
                                return dateObj.toTimeString().substring(0, 8);
                            })(),
                            iqomah: 0
                        },
                            {
                                name: 'Dhuha',
                                jamDB: this.jadwalDB.dhuha,
                                iqomah: 0
                            },
                        {
                            name: 'Dzuhur',
                            jamDB: this.jadwalDB.dzuhur,
                            iqomah: this.settings.iqomah_dzuhur || 10
                        },
                        {
                            name: 'Ashar',
                            jamDB: this.jadwalDB.ashar,
                            iqomah: this.settings.iqomah_ashar || 10
                        },
                        {
                            name: 'Maghrib',
                            jamDB: this.jadwalDB.maghrib,
                            iqomah: this.settings.iqomah_maghrib || 10
                        },
                        {
                            name: 'Isya',
                            jamDB: this.jadwalDB.isya,
                            iqomah: this.settings.iqomah_isya || 10
                        }
                    ];

                    if (now.getDay() === 5 && this.tipeTempat !== 'Mushola') {
                        let dzuhurIndex = prayers.findIndex(p => p.name === 'Dzuhur');
                        if (dzuhurIndex !== -1) {
                            prayers[dzuhurIndex].name = 'Jumat';
                            prayers[dzuhurIndex].iqomah = 5;
                        }
                    }

                    let nextP = null;
                    let activeMode = 'standby';

                    for (let p of prayers) {
                        if (!p.jamDB) continue;

                        let timeParts = p.jamDB.split(':');
                        if (timeParts.length < 2) continue;

                        let h = parseInt(timeParts[0]);
                        let m = parseInt(timeParts[1]);
                        let s = timeParts[2] ? parseInt(timeParts[2]) : 0;

                        let adzanStartSeconds = (h * 3600) + (m * 60) + s;
                        let preAdzanSeconds = adzanStartSeconds - 300;
                        let adzanEndSeconds = adzanStartSeconds + ({{ $settings->durasi_adzan ?? 4 }} * 60);
                        let iqomahEndSeconds = adzanEndSeconds + (p.iqomah * 60);
                        let sholatEndSeconds = iqomahEndSeconds + (this.durasiSholat[p.name] * 60);

                        if (adzanStartSeconds > currentSeconds && !nextP) {
                            nextP = p.name;
                        }

                        if (p.name !== 'Isyraq' && p.name !== 'Dhuha') {
                            if (currentSeconds >= preAdzanSeconds && currentSeconds < adzanStartSeconds) {
                                activeMode = 'menuju_adzan';
                                this.currentPrayerName = p.name;
                                let sisaDetikAdzan = adzanStartSeconds - currentSeconds;
                                let sisaMenitAdzan = Math.floor(sisaDetikAdzan / 60).toString().padStart(2, '0');
                                let sisaDetikAdzanMod = (sisaDetikAdzan % 60).toString().padStart(2, '0');
                                this.countdownAdzanDisplay = `${sisaMenitAdzan}:${sisaDetikAdzanMod}`;
                                if ([3, 2, 1].includes(sisaDetikAdzan)) {
                                    this.playBeep();
                                }
                                break;
                            }

                            if (currentSeconds === adzanStartSeconds) {
                                this.playAdzan(p.name);
                            }

                            if (currentSeconds >= adzanStartSeconds && currentSeconds < adzanEndSeconds) {
                                activeMode = 'adzan';
                                this.currentPrayerName = p.name;
                                break;
                            }

                            if (currentSeconds >= adzanEndSeconds && currentSeconds < iqomahEndSeconds) {
                                activeMode = 'waiting_iqomah';
                                this.currentPrayerName = p.name;
                                let sisaDetikTotal = iqomahEndSeconds - currentSeconds;
                                let sisaMenit = Math.floor(sisaDetikTotal / 60).toString().padStart(2, '0');
                                let sisaDetik = (sisaDetikTotal % 60).toString().padStart(2, '0');
                                this.countdownIqomahDisplay = `${sisaMenit}:${sisaDetik}`;
                                // bunyikan 3 detik terakhir sebelum iqomah
                                if ([3, 2, 1].includes(sisaDetikTotal)) {
                                    this.playBeep();
                                }
                                break;
                            } else if (currentSeconds >= iqomahEndSeconds && currentSeconds < sholatEndSeconds) {
                                activeMode = 'sholat';
                                this.currentPrayerName = p.name;
                                let sisaDetikSholatTotal = sholatEndSeconds - currentSeconds;
                                let sisaMenitSholat = Math.floor(sisaDetikSholatTotal / 60).toString().padStart(2, '0');
                                let sisaDetikSholat = (sisaDetikSholatTotal % 60).toString().padStart(2, '0');
                                this.countdownSholatDisplay = `${sisaMenitSholat}:${sisaDetikSholat}`;
                                break;
                            }
                        }
                    }

                    this.mode = activeMode;
                    this.nextPrayerName = nextP || 'Subuh';
                },

                playBeep() {
                    let beep = document.getElementById('audio-beep');
                    if (beep) {
                        beep.volume = 1;
                        beep.currentTime = 0;
                        beep.play().catch(() => {});
                    }
                },

                playAdzan(prayerName) {
                    let adzanId = (prayerName === 'Subuh') ? 'audio-adzan-subuh' : 'audio-adzan';
                    let adzan = document.getElementById(adzanId);

                    if (!adzan || !adzan.src) {
                        adzan = document.getElementById('audio-adzan');
                    }

                    if (adzan) {
                        adzan.volume = 1;
                        adzan.currentTime = 0;
                        adzan.play().catch(() => {});
                    }
                }
            }
        }
    </script>
</div>
