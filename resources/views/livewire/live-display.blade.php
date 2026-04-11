<div x-data="displaySystem()" x-init="initSystem()"
    :class="layoutMode === 'layout-2' ? 'bg-slate-50 text-slate-800' : 'bg-slate-950 text-white'"
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
                <button @click="setLayout('layout-1')"
                    :class="layoutMode === 'layout-1' ? 'ring-2 ring-theme-main bg-slate-800' : 'bg-slate-900/50 hover:bg-slate-800'"
                    class="px-6 py-3 rounded-xl font-bold text-white transition-all flex items-center gap-2 border border-white/10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                    Layout 1
                </button>
                <button @click="setLayout('layout-2')"
                    :class="layoutMode === 'layout-2' ? 'ring-2 ring-theme-main bg-white text-slate-900' : 'bg-slate-900/50 text-slate-300 hover:bg-slate-800'"
                    class="px-6 py-3 rounded-xl font-bold transition-all flex items-center gap-2 border border-white/10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    Layout 2
                </button>
            </div>
        </div>
    </div>


    <template x-if="layoutMode === 'layout-1'">
        <div class="contents">
            @include('livewire.displays.layout-1')
        </div>
    </template>
    
    <template x-if="layoutMode === 'layout-2'">
        <div class="contents">
            @include('livewire.displays.layout-2')
        </div>
    </template>

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
                layoutMode: localStorage.getItem('liveDisplayLayout') || 'layout-1',
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
                iqomahContents: @js($iqomahContents),
                activeIqomahContentIndex: 0,
                iqomahContentInterval: null,
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

                setLayout(mode) {
                    this.layoutMode = mode;
                    localStorage.setItem('liveDisplayLayout', mode);
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
                    this.$watch('mode', value => {
                        if (value === 'waiting_iqomah') {
                            if (!this.iqomahContentInterval && this.iqomahContents.length > 1) {
                                this.iqomahContentInterval = setInterval(() => {
                                    this.activeIqomahContentIndex = (this.activeIqomahContentIndex + 1) % this.iqomahContents.length;
                                }, 10000); // Ganti tiap 10 detik
                            }
                        } else {
                            if (this.iqomahContentInterval) {
                                clearInterval(this.iqomahContentInterval);
                                this.iqomahContentInterval = null;
                            }
                            this.activeIqomahContentIndex = 0;
                        }
                    });

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
