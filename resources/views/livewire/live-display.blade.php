<div x-data="displaySystem()" x-init="initSystem()" class="relative w-full h-full font-sans bg-slate-950 text-white overflow-hidden">

    @php
        $themeColor = $settings->theme_color ?? 'emerald';
        $theme = match($themeColor) {
            'blue'   => ['main' => '#3b82f6', 'dark' => '#1e3a8a', 'light' => '#bfdbfe'],
            'violet' => ['main' => '#8b5cf6', 'dark' => '#4c1d95', 'light' => '#ddd6fe'],
            'rose'   => ['main' => '#f43f5e', 'dark' => '#881337', 'light' => '#fecdd3'],
            'amber'  => ['main' => '#f59e0b', 'dark' => '#78350f', 'light' => '#fde68a'],
            default  => ['main' => '#10b981', 'dark' => '#064e3b', 'light' => '#a7f3d0'],
        };

        $globalSpeed = $settings->running_text_speed ?? 5;
        $duration = max(10, 65 - ($globalSpeed * 5));
        $apiHost = parse_url($settings->api_jadwal_sholat ?? 'https://api.myquran.com', PHP_URL_HOST);
    @endphp

    <style>
        :root {
            --theme-main: {{ $theme['main'] }};
            --theme-dark: {{ $theme['dark'] }};
            --theme-light: {{ $theme['light'] }};
        }
        .bg-theme-dark { background-color: var(--theme-dark); }
        .bg-theme-main { background-color: var(--theme-main); }
        .text-theme-main { color: var(--theme-main); }
        .text-theme-light { color: var(--theme-light); }
        .border-theme-main { border-color: var(--theme-main); }
        .shadow-theme-glow { box-shadow: 0 0 40px rgba(0,0,0,0.5), 0 0 20px var(--theme-main); }
        .shadow-theme-text { text-shadow: 0 0 20px var(--theme-main); }

        .islamic-pattern { background-image: url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.03"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E'); }

        .marquee-preview { overflow: hidden; display: flex; align-items: center; position: relative; width: 100%; height: 100%; }
        .marquee-content { display: flex; gap: 4rem; width: max-content; animation: marquee linear infinite; padding-left: 100%; }
        .marquee-item { white-space: nowrap; display: flex; align-items: center; gap: 1rem; }
        @keyframes marquee { 0% { transform: translateX(0); } 100% { transform: translateX(-100%); } }
    </style>

    <div x-show="!started" class="absolute inset-0 z-[100] bg-slate-950 flex flex-col items-center justify-center backdrop-blur-3xl">
        <div class="text-center">
            <svg class="w-32 h-32 text-theme-main mx-auto mb-6 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <h1 class="text-5xl font-black text-white uppercase tracking-widest mb-4">Masjid Digital Ready</h1>
            <button @click="startDisplay()" class="bg-theme-main text-white px-12 py-6 rounded-full font-black text-2xl uppercase tracking-[0.2em] shadow-theme-glow hover:scale-105 transition-all">
                Mulai Display TV
            </button>
        </div>
    </div>

    <div class="absolute inset-0 z-0">
        @if($settings && $settings->background_image)
            <img src="{{ Storage::url($settings->background_image) }}" class="w-full h-full object-cover opacity-30">
        @else
            <div class="w-full h-full" style="background: radial-gradient(ellipse at top right, var(--theme-dark), #0f172a, #000000);"></div>
        @endif
        <div class="absolute inset-0 islamic-pattern"></div>
    </div>

    <header x-show="mode === 'standby'" class="absolute top-0 w-full z-20 flex justify-between items-center px-10 py-6 bg-black/40 backdrop-blur-xl border-b border-white/10 shadow-xl">
        <div class="flex items-center gap-6">
            @if($settings && $settings->logo_path)
                <img src="{{ Storage::url($settings->logo_path) }}" class="w-24 h-24 rounded-full object-cover border-4 border-theme-main shadow-theme-glow">
            @endif
            <div class="flex flex-col justify-center">
                <h1 class="text-4xl font-black tracking-tighter uppercase text-white shadow-theme-text mb-1">
                    {{ $settings->nama_masjid ?? 'MASJID DIGITAL' }}
                </h1>
                <div class="flex items-center gap-3">
                    <span class="text-xs font-bold text-slate-300 tracking-widest uppercase flex items-center gap-1">
                        <svg class="w-4 h-4 text-theme-main" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        {{ $settings->kota_nama ?? 'Pekanbaru' }}
                    </span>
                    <span class="text-slate-500">•</span>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest bg-white/10 px-3 py-1 rounded-full border border-white/10">
                        API: {{ $apiHost }}
                    </span>
                </div>
            </div>
        </div>
        <div class="text-right">
            <div class="text-6xl font-black text-white tracking-widest leading-none drop-shadow-2xl tabular-nums" x-text="time">00:00:00</div>
            <div class="text-xl font-medium text-theme-main mt-2 uppercase tracking-wide">
                <span x-text="dateGregorian"></span> &bull; <span class="text-white">{{ $jadwal->tanggal_hijriah ?? '' }}</span>
            </div>
        </div>
    </header>

    <main class="relative z-10 w-full h-full flex justify-center items-center overflow-hidden" :class="mode === 'standby' ? 'pt-36 pb-24 p-8' : 'p-0'">

        <div x-show="mode === 'standby'" class="w-full h-full flex gap-8">
            <div class="flex-[2.5] relative rounded-[3rem] overflow-hidden shadow-2xl border border-white/10 bg-black">

                @if($banners->count() > 0)
                    @foreach($banners as $index => $banner)
                    <div class="absolute inset-0" x-show="activeSlide === {{ $index }}" x-transition.duration.1000ms>
                        <img src="{{ Storage::url($banner->image_path) }}" class="w-full h-full object-cover">
                    </div>
                    @endforeach
                @endif

                <div class="absolute inset-0 bg-gradient-to-t from-black via-black/40 to-transparent pointer-events-none"></div>

                <div class="absolute bottom-0 w-full p-8 grid grid-cols-2 gap-6">

                    <div class="bg-black/60 backdrop-blur-2xl rounded-[2rem] p-6 border border-white/20 shadow-2xl flex flex-col relative h-[210px]">
                        <div class="flex items-center gap-2 mb-3 relative z-10 shrink-0">
                            <span class="w-3 h-3 rounded-full bg-theme-main animate-pulse shadow-theme-glow"></span>
                            <h3 class="text-sm font-black text-theme-main uppercase tracking-widest">Mutiara Hikmah</h3>
                        </div>
                        <div class="flex-1 relative w-full overflow-hidden">
                            @if($contents->count() > 0)
                                @foreach($contents as $idx => $content)
                                <div class="absolute inset-0 flex flex-col justify-center" x-show="activeContent === {{ $idx }}" x-transition.duration.800ms>
                                    <h4 class="text-base font-bold text-white mb-1.5 line-clamp-1">{{ $content->judul }}</h4>
                                    @if($content->teks_arab)
                                        <p class="font-arab text-xl text-theme-light text-right mb-2 leading-relaxed line-clamp-3">{{ $content->teks_arab }}</p>
                                    @endif
                                    <p class="text-slate-300 text-xs italic border-l-2 border-theme-main pl-3 line-clamp-3 leading-tight">"{{ $content->teks_indo }}"</p>
                                </div>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <div class="bg-black/60 backdrop-blur-2xl rounded-[2rem] p-6 border border-white/20 shadow-2xl flex flex-col relative h-[210px]">
    <div class="flex items-center gap-2 mb-3 relative z-10 shrink-0">
        <span class="w-3 h-3 rounded-full bg-theme-main animate-pulse shadow-theme-glow"></span>
        <h3 class="text-sm font-black text-theme-main uppercase tracking-widest">Info Petugas & Kajian</h3>
    </div>
    <div class="flex-1 relative w-full overflow-hidden">
        @if($ceramah->count() > 0)
            @foreach($ceramah as $idx => $kajian)
            @php
                // Logika Warna Dinamis berdasarkan Kategori
                $kat = $kajian->kategori;
                if (str_contains($kat, 'Jumat')) {
                    $badgeStyle = 'text-emerald-400 bg-emerald-500/20 border-emerald-500/30';
                    $nameStyle  = 'text-emerald-300';
                } elseif (str_contains($kat, 'Rutin')) {
                    $badgeStyle = 'text-blue-400 bg-blue-500/20 border-blue-500/30';
                    $nameStyle  = 'text-blue-300';
                } elseif (str_contains($kat, 'Tarawih')) {
                    $badgeStyle = 'text-purple-400 bg-purple-500/20 border-purple-500/30';
                    $nameStyle  = 'text-purple-300';
                } elseif (str_contains($kat, 'Idul')) {
                    $badgeStyle = 'text-amber-400 bg-amber-500/20 border-amber-500/30';
                    $nameStyle  = 'text-amber-300';
                } else {
                    $badgeStyle = 'text-theme-main bg-theme-main/20 border-theme-main/30';
                    $nameStyle  = 'text-white';
                }
            @endphp

            <div class="absolute inset-0 flex flex-col justify-center items-center text-center w-full" x-show="activeCeramah === {{ $idx }}" x-transition.duration.800ms>

                <div class="{{ $badgeStyle }} px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest mb-2 border">
                    {{ \Carbon\Carbon::parse($kajian->tanggal)->translatedFormat('l, d M Y') }} • {{ $kajian->kategori }}
                </div>

                <p class="text-[1.3rem] font-black {{ $nameStyle }} leading-tight mb-0.5 px-2 line-clamp-1">{{ $kajian->tokoh }}</p>
                <p class="text-xs font-medium text-slate-300 px-2 mb-2 line-clamp-1">{{ $kajian->judul ?? 'Penceramah / Khatib' }}</p>

                @if($kajian->imam || $kajian->muadzin || $kajian->bilal)
                    <div class="flex flex-wrap justify-center gap-2 mt-1 px-2">
                        @if($kajian->imam)
                            <span class="text-[10px] bg-white/10 px-2 py-0.5 rounded border border-white/20"><span class="text-slate-400">Imam:</span> {{ $kajian->imam }}</span>
                        @endif
                        @if($kajian->muadzin)
                            <span class="text-[10px] bg-white/10 px-2 py-0.5 rounded border border-white/20"><span class="text-slate-400">Muadz:</span> {{ $kajian->muadzin }}</span>
                        @endif
                        @if($kajian->bilal)
                            <span class="text-[10px] bg-white/10 px-2 py-0.5 rounded border border-white/20"><span class="text-slate-400">Bilal:</span> {{ $kajian->bilal }}</span>
                        @endif
                    </div>
                @endif

            </div>
            @endforeach
        @else
            <p class="text-slate-500 text-sm absolute inset-0 flex items-center justify-center">Belum ada jadwal tersimpan.</p>
        @endif
    </div>
</div>

                </div>
            </div>

            <div class="w-full max-w-[420px] flex flex-col gap-6 h-full">
                <div class="flex-1 flex flex-col gap-3">
                    @php $waktuSholat = ['Subuh', 'Terbit', 'Dhuha', 'Dzuhur', 'Ashar', 'Maghrib', 'Isya']; @endphp
                    @foreach($waktuSholat as $waktu)
                    @php
                        $field = strtolower($waktu);
                        $jamFormatted = \Carbon\Carbon::parse($jadwal->$field ?? '00:00:00')->format('H:i');
                    @endphp
                    <div class="relative overflow-hidden rounded-[2rem] p-4 flex justify-between items-center border transition-all duration-500"
                         :class="nextPrayerName === '{{ $waktu }}' ? 'bg-theme-main border-theme-main scale-105 shadow-theme-glow z-10' : 'bg-black/50 backdrop-blur-md border-white/10'">
                        <span class="text-xl font-bold uppercase tracking-widest" :class="nextPrayerName === '{{ $waktu }}' ? 'text-white' : 'text-slate-400'">{{ $waktu }}</span>
                        <span class="text-4xl font-black tracking-tighter tabular-nums" :class="nextPrayerName === '{{ $waktu }}' ? 'text-white' : 'text-theme-main'">{{ $jamFormatted }}</span>
                        <div x-show="nextPrayerName === '{{ $waktu }}'" class="absolute -bottom-4 -right-4 text-8xl text-white opacity-20 rotate-12">
                            <svg class="w-20 h-20" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14H9V8h2v8zm4 0h-2V8h2v8z"/></svg>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="bg-theme-dark rounded-[2rem] p-6 border border-theme-main/50 shadow-2xl shrink-0 relative overflow-hidden h-[150px] flex items-center justify-center">
                    <div class="absolute -right-6 -top-6 text-white/10 w-40 h-40 rotate-12">
                        <svg fill="currentColor" viewBox="0 0 24 24"><path d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    </div>

                    <div x-show="activeRekening === 0" x-transition.duration.800ms class="absolute inset-0 flex flex-col items-center justify-center w-full px-6 text-center">
                        <h3 class="text-[11px] font-black text-theme-light uppercase tracking-[0.2em] mb-2 relative z-10">Total Saldo Kas</h3>
                        <p class="text-[2.2rem] font-black text-white tracking-tighter relative z-10 drop-shadow-lg leading-none">
                            Rp {{ number_format($totalSaldo, 0, ',', '.') }}
                        </p>
                    </div>

                    @if($rekenings->count() > 0)
                        @foreach($rekenings as $idx => $rek)
                        <div x-show="activeRekening === {{ $idx + 1 }}" style="display:none;" x-transition.duration.800ms class="absolute inset-0 flex flex-col items-center justify-center w-full px-6 text-center">
                            <h3 class="text-sm font-black text-theme-main uppercase tracking-widest mb-1 relative z-10">{{ $rek->nama_bank }}</h3>
                            <p class="text-[1.8rem] font-black text-white tracking-widest relative z-10 drop-shadow-lg leading-none mb-2">
                                {{ $rek->nomor_rekening }}
                            </p>
                            <p class="text-[11px] text-slate-300 uppercase font-bold tracking-wider relative z-10">A/N: {{ $rek->nama_akun }}</p>
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        <div x-show="mode === 'menuju_adzan'" style="display: none;" class="absolute inset-0 z-50 flex flex-col items-center justify-center bg-black/95 backdrop-blur-3xl">
            <h2 class="text-[4rem] font-bold text-slate-400 uppercase tracking-[0.5em] mb-6">Waktu <span x-text="currentPrayerName" class="text-white"></span></h2>
            <p class="text-4xl text-theme-main mb-8 tracking-widest uppercase font-black animate-pulse">Menuju Adzan</p>
            <h1 class="text-[25rem] font-black text-white leading-none tabular-nums shadow-theme-text" x-text="countdownAdzanDisplay">00:00</h1>
        </div>

        <div x-show="mode === 'waiting_iqomah'" style="display: none;" class="absolute inset-0 z-50 flex flex-col items-center justify-center bg-black/95 backdrop-blur-3xl">
            <h2 class="text-[4rem] font-bold text-slate-400 uppercase tracking-[0.5em] mb-6 animate-pulse">Waktu <span x-text="currentPrayerName" class="text-white"></span> Telah Masuk</h2>
            <p class="text-4xl text-theme-main mb-8 tracking-widest uppercase font-black">Iqomah Dalam:</p>
            <h1 class="text-[25rem] font-black text-white leading-none tabular-nums shadow-theme-text" x-text="countdownIqomahDisplay">00:00</h1>
        </div>

        <div x-show="mode === 'sholat'" style="display: none;" class="absolute inset-0 z-50 flex flex-col items-center justify-center bg-black w-full h-full">
            <div class="relative w-96 h-96 mb-16 animate-pulse">
                 <div class="absolute inset-0 border-[12px] border-rose-600 rounded-full shadow-[0_0_100px_rgba(225,29,72,0.5)]"></div>
                 <div class="absolute inset-0 flex items-center justify-center">
                    <svg class="w-48 h-48 text-rose-600" fill="currentColor" viewBox="0 0 24 24"><path d="M17 1.01L7 1c-1.1 0-2 .9-2 2v18c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V3c0-1.1-.9-1.99-2-1.99zM17 19H7V5h10v14z"/></svg>
                 </div>
                 <div class="absolute top-1/2 left-1/2 w-full h-8 bg-rose-600 -translate-x-1/2 -translate-y-1/2 -rotate-45 rounded-full shadow-[0_0_20px_rgba(0,0,0,0.5)]"></div>
            </div>
            <h1 class="text-[7rem] font-black text-white uppercase tracking-[0.2em] mb-8 shadow-2xl">Mohon Tenang</h1>
            <p class="text-[4rem] text-rose-500 font-black uppercase tracking-[0.3em] text-center leading-tight mb-16">Matikan / Silent<br>Ponsel Anda</p>
            <div class="flex items-center gap-12 bg-white/10 px-12 py-6 rounded-[3rem] border border-white/20 backdrop-blur-md">
                <div class="text-center">
                    <p class="text-2xl text-slate-400 font-bold uppercase tracking-widest mb-2">Jam Saat Ini</p>
                    <p class="text-5xl font-black text-white tracking-widest tabular-nums" x-text="time">00:00:00</p>
                </div>
                <div class="w-1 h-20 bg-white/20 rounded-full"></div>
                <div class="text-center">
                    <p class="text-2xl text-theme-main font-bold uppercase tracking-widest mb-2">Sisa Waktu</p>
                    <p class="text-5xl font-black text-white tracking-widest tabular-nums" x-text="countdownSholatDisplay">00:00</p>
                </div>
            </div>
        </div>
    </main>

    <footer x-show="mode === 'standby'" class="absolute bottom-0 w-full h-24 bg-black border-t border-white/10 flex items-center overflow-hidden z-20 shadow-[0_-10px_40px_rgba(0,0,0,0.5)]">
        <div class="bg-theme-main h-full flex items-center px-10 z-30 shadow-[10px_0_30px_rgba(0,0,0,0.8)] border-r border-white/20">
            <span class="text-black font-black uppercase tracking-widest text-2xl">Informasi</span>
        </div>
        <div class="marquee-preview">
            <div class="marquee-content font-bold text-3xl uppercase tracking-wider" style="animation-duration: {{ $duration }}s;">
                @forelse($runningTexts as $item)
                    @php
                        $marqueeTheme = match($item->tipe) {
                            'ayat'   => ['text' => 'text-cyan-400', 'dot' => 'bg-cyan-500'],
                            'hadits' => ['text' => 'text-purple-400', 'dot' => 'bg-purple-500'],
                            'ucapan' => ['text' => 'text-amber-400', 'dot' => 'bg-amber-500'],
                            default  => ['text' => 'text-theme-main', 'dot' => 'bg-theme-main'],
                        };
                    @endphp
                    <div class="marquee-item {{ $marqueeTheme['text'] }}">
                        <span class="w-4 h-4 rounded-full {{ $marqueeTheme['dot'] }} shadow-theme-glow"></span>
                        <span>{{ $item->teks }}</span>
                    </div>
                @empty
                    <div class="marquee-item text-slate-500">
                        <span class="w-4 h-4 rounded-full bg-slate-500"></span>
                        <span class="normal-case">Selamat Datang di Masjid Digital.</span>
                    </div>
                @endforelse
            </div>
        </div>
    </footer>

    <audio id="audio-beep" src="{{ asset('sounds/beep.mp3') }}" preload="auto"></audio>
    <audio id="audio-adzan" src="{{ isset($settings->path_adzan) && $settings->path_adzan ? Storage::url($settings->path_adzan) : asset('sounds/adzan.mp3') }}" preload="auto"></audio>

    <script>
        function displaySystem() {
            return {
                started: false,
                time: '00:00:00',
                dateGregorian: '',
                mode: 'standby',
                nextPrayerName: '',
                currentPrayerName: '',

                activeSlide: 0,
                activeContent: 0,
                activeCeramah: 0,
                activeRekening: 0,

                jadwalDB: {{ Illuminate\Support\Js::from($jadwal) }},
                settings: {{ Illuminate\Support\Js::from($settings) }},

                bannersCount: Number("{{ $banners->count() }}"),
                contentsCount: Number("{{ $contents->count() }}"),
                ceramahCount: Number("{{ $ceramah->count() }}"),
                rekeningCount: Number("{{ $rekenings->count() }}"),

                countdownAdzanDisplay: '00:00',
                countdownIqomahDisplay: '00:00',
                countdownSholatDisplay: '00:00',

                durasiSholat: { 'Subuh': 20, 'Dzuhur': 20, 'Ashar': 20, 'Maghrib': 20, 'Isya': 20, 'Jumat': 45 },

                startDisplay() {
                    this.started = true;
                    let beep = document.getElementById('audio-beep');
                    let adzan = document.getElementById('audio-adzan');
                    if(beep) { beep.play().then(() => beep.pause()).catch(()=>{}); }
                    if(adzan) { adzan.play().then(() => adzan.pause()).catch(()=>{}); }
                    if (document.documentElement.requestFullscreen) { document.documentElement.requestFullscreen().catch(()=>{}); }
                },

                initSystem() {
                    this.updateTime();
                    setInterval(() => this.updateTime(), 1000);

                    if(this.bannersCount > 1) {
                        setInterval(() => { this.activeSlide = (this.activeSlide + 1) % this.bannersCount; }, this.settings.durasi_slide_foto || 10000);
                    }
                    if(this.contentsCount > 1) {
                        setInterval(() => { this.activeContent = (this.activeContent + 1) % this.contentsCount; }, 15000);
                    }
                    if(this.ceramahCount > 1) {
                        setInterval(() => { this.activeCeramah = (this.activeCeramah + 1) % this.ceramahCount; }, 12000);
                    }

                    const totalKasSlides = 1 + this.rekeningCount;
                    if(totalKasSlides > 1) {
                        setInterval(() => { this.activeRekening = (this.activeRekening + 1) % totalKasSlides; }, 8000);
                    }

                    setInterval(() => {
                        let now = new Date();
                        if(now.getHours() === 0 && now.getMinutes() === 1 && now.getSeconds() === 0) window.location.reload();
                    }, 1000);
                },

                updateTime() {
                    const now = new Date();
                    this.time = now.toTimeString().substring(0, 8);
                    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                    this.dateGregorian = now.toLocaleDateString('id-ID', options);
                    this.checkDatabasePrayerTime(now);
                },

                checkDatabasePrayerTime(now) {
                    if(!this.jadwalDB || !this.started) return;

                    const currentSeconds = (now.getHours() * 3600) + (now.getMinutes() * 60) + now.getSeconds();
                    const prayers = [
                        { name: 'Subuh',   jamDB: this.jadwalDB.subuh,   iqomah: this.settings.iqomah_subuh || 10 },
                        { name: 'Dzuhur',  jamDB: this.jadwalDB.dzuhur,  iqomah: this.settings.iqomah_dzuhur || 10 },
                        { name: 'Ashar',   jamDB: this.jadwalDB.ashar,   iqomah: this.settings.iqomah_ashar || 10 },
                        { name: 'Maghrib', jamDB: this.jadwalDB.maghrib, iqomah: this.settings.iqomah_maghrib || 10 },
                        { name: 'Isya',    jamDB: this.jadwalDB.isya,    iqomah: this.settings.iqomah_isya || 10 }
                    ];

                    if (now.getDay() === 5) {
                        let dzuhurIndex = prayers.findIndex(p => p.name === 'Dzuhur');
                        if(dzuhurIndex !== -1) { prayers[dzuhurIndex].name = 'Jumat'; prayers[dzuhurIndex].iqomah = 5; }
                    }

                    let nextP = null;
                    let activeMode = 'standby';

                    for (let p of prayers) {
                        if(!p.jamDB) continue;

                        let timeParts = p.jamDB.split(':');
                        if (timeParts.length < 2) continue;

                        let h = parseInt(timeParts[0]);
                        let m = parseInt(timeParts[1]);
                        let s = timeParts[2] ? parseInt(timeParts[2]) : 0;

                        let adzanStartSeconds = (h * 3600) + (m * 60) + s;
                        let preAdzanSeconds   = adzanStartSeconds - 30;
                        let iqomahEndSeconds  = adzanStartSeconds + (p.iqomah * 60);
                        let sholatEndSeconds  = iqomahEndSeconds + (this.durasiSholat[p.name] * 60);

                        if (adzanStartSeconds > currentSeconds && !nextP) { nextP = p.name; }

                        if (currentSeconds >= preAdzanSeconds && currentSeconds < adzanStartSeconds) {
                            activeMode = 'menuju_adzan';
                            this.currentPrayerName = p.name;
                            let sisaDetikAdzan = adzanStartSeconds - currentSeconds;
                            this.countdownAdzanDisplay = `00:${sisaDetikAdzan.toString().padStart(2, '0')}`;
                            if ([10, 5, 4, 3, 2, 1].includes(sisaDetikAdzan)) { this.playBeep(); }
                            break;
                        }

                        if (currentSeconds === adzanStartSeconds) { this.playAdzan(); }

                        if (currentSeconds >= adzanStartSeconds && currentSeconds < iqomahEndSeconds) {
                            activeMode = 'waiting_iqomah';
                            this.currentPrayerName = p.name;
                            let sisaDetikTotal = iqomahEndSeconds - currentSeconds;
                            let sisaMenit = Math.floor(sisaDetikTotal / 60).toString().padStart(2, '0');
                            let sisaDetik = (sisaDetikTotal % 60).toString().padStart(2, '0');
                            this.countdownIqomahDisplay = `${sisaMenit}:${sisaDetik}`;
                            if (sisaDetikTotal <= 10 && sisaDetikTotal > 0) { this.playBeep(); }
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

                    this.mode = activeMode;
                    this.nextPrayerName = nextP || 'Subuh';
                },

                playBeep() {
                    let beep = document.getElementById('audio-beep');
                    if(beep) { beep.volume = 1; beep.currentTime = 0; beep.play().catch(()=>{}); }
                },

                playAdzan() {
                    let adzan = document.getElementById('audio-adzan');
                    if(adzan) { adzan.volume = 1; adzan.currentTime = 0; adzan.play().catch(()=>{}); }
                }
            }
        }
    </script>
</div>
