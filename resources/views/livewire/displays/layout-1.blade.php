    <div class="absolute inset-0 z-0 transition-opacity duration-500">
        <div class="w-full h-full"
             :style="'background: radial-gradient(ellipse at top right, var(--theme-dark), #0f172a, #000000);'">
        </div>
        <div class="absolute inset-0 islamic-pattern"></div>
    </div>
    <header x-show="mode === 'standby'"
        class="bg-black/40 border-white/10 relative w-full z-20 grid grid-cols-12 items-center px-[3vw] py-[1.5vh] backdrop-blur-xl border-b shadow-xl shrink-0 transition-colors duration-500 gap-4">

        <div class="overflow-hidden flex items-center justify-start h-full w-full col-span-3">
            <div class="marquee-preview w-full">
                <div class="marquee-content font-bold uppercase tracking-wide leading-tight text-[3.5vh]"
                     style="animation-duration: 20s;">
                    <div class="marquee-item">
                        <span x-text="dateGregorian" class="text-slate-300 font-bold"></span>
                        <span class="text-slate-300">/ {{ $jadwal->tanggal_hijriah ?? '' }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex flex-col items-center justify-center text-center col-span-5">
            <h1 class="text-white shadow-theme-text text-[4.5vh] font-black tracking-tighter uppercase leading-none transition-colors duration-500">
                {{ $settings->nama_masjid ?? 'MASJID DIGITAL' }}
            </h1>
            <p class="text-slate-300 text-[3vh] font-medium mt-1 opacity-90 line-clamp-1 transition-colors duration-500">
                {{ $settings->alamat ?? 'Alamat tempat belum dikonfigurasi' }}
            </p>
        </div>
        <div class="flex items-center justify-end col-span-4">
            <div class="text-white text-[7vh] font-black tracking-widest leading-none drop-shadow-2xl tabular-nums transition-colors duration-500"
                x-text="time">00:00:00</div>
        </div>
    </header>
    <main class="relative z-10 w-full flex-1 flex overflow-hidden p-[2vh] gap-[2vh]"
      x-show="mode === 'standby'">

    <div class="w-full max-w-[1080px] min-w-[640px] flex flex-col gap-[2vh]">
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
                class="bg-black/60 border-white/20 shadow-[0_15px_40px_rgba(0,0,0,0.6)] ring-white/10 h-[8vh] backdrop-blur-2xl border rounded-[4vh] flex items-center overflow-hidden ring-1 transition-colors duration-500">
                <div
                    class="border-white/20 shadow-[10px_0_30px_rgba(0,0,0,0.8)] bg-theme-main h-full flex items-center px-[2vw] z-30 border-r relative transition-colors duration-500">
                    <span class="text-white font-black uppercase tracking-[0.1em] text-[2.5vh] relative z-10">Informasi</span>
                </div>
                <div class="marquee-preview flex-1">
                    <div class="marquee-content font-black text-[3.5vh] uppercase tracking-[0.05em]"
                        style="animation-duration: {{ $duration }}s;">
                        @forelse($runningTexts as $item)
                            @php
                                $marqueeTheme = match ($item->tipe) {
                                    'ayat'   => ['text' => 'text-cyan-700', 'dot' => 'text-cyan-500'],
                                    'hadits' => ['text' => 'text-purple-700', 'dot' => 'text-purple-500'],
                                    'ucapan' => ['text' => 'text-slate-700', 'dot' => 'text-slate-500'],
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
                            <div class="text-slate-400 marquee-item transition-colors duration-500">
                                <svg class="text-slate-500 w-[4vh] h-[4vh] animate-spin-slow shrink-0 transition-colors duration-500" viewBox="0 0 24 24"
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
    <div class="w-[35vw] flex flex-col gap-[2vh] h-full min-w-0">

    <div class="flex-1 flex flex-col min-h-0 rounded-[2vh] border-4 transition-colors duration-500 overflow-hidden border-white/20 shadow-2xl">
        @php $waktuSholat = ['Subuh', 'Isyraq', 'Dhuha', 'Dzuhur', 'Ashar', 'Maghrib', 'Isya']; @endphp
        @foreach ($waktuSholat as $index => $waktu)
            @php
                $field = strtolower($waktu);
                $jamFormatted = ($waktu === 'Isyraq')
                    ? \Carbon\Carbon::parse($jadwal->terbit ?? '00:00:00')->addMinutes(12)->format('H:i')
                    : \Carbon\Carbon::parse($jadwal->$field ?? '00:00:00')->format('H:i');
                $isSunnah = in_array($waktu, ['Isyraq', 'Dhuha']);
                $bgLight = match($waktu) {
                    'Subuh' => 'bg-cyan-600',
                    'Dzuhur' => 'bg-emerald-600',
                    'Ashar' => 'bg-orange-600',
                    'Maghrib' => 'bg-rose-600',
                    'Isya' => 'bg-indigo-600',
                    default => 'bg-slate-100'
                };

                $bgDark = match($waktu) {
                    'Subuh' => 'bg-cyan-900',
                    'Dzuhur' => 'bg-emerald-900',
                    'Ashar' => 'bg-orange-900',
                    'Maghrib' => 'bg-rose-900',
                    'Isya' => 'bg-indigo-900',
                    default => 'bg-slate-900'
                };
                $borderClass = $index < count($waktuSholat) - 1 ? "border-b-2" : "";
            @endphp
            <div class="flex-1 flex justify-between items-center px-[1vw] transition-all duration-500 relative {{ $borderClass }}"
                :class="[
                    nextPrayerName === '{{ $waktu }}'
                        ? 'ring-inset ring-4 ring-yellow-400 z-10'
                        : '',
                    '{{ $bgDark }} border-white/10'
                ]">

                <span class="text-[5vh] font-bold uppercase transition-colors duration-500"
                    :class="[
                        nextPrayerName === '{{ $waktu }}'
                            ? 'text-white drop-shadow-md'
                            : ('{{ $isSunnah }}' ? 'text-slate-300' : 'text-slate-200')
                    ]">
                    {{ $waktu }}
                </span>
                <span class="text-[5vh] font-black tabular-nums transition-colors duration-500"
                    :class="[
                        nextPrayerName === '{{ $waktu }}'
                            ? 'text-white drop-shadow-md'
                            : ('{{ $isSunnah }}' ? 'text-slate-200' : 'text-slate-100')
                    ]">
                    {{ $jamFormatted }}
                </span>
            </div>
        @endforeach
    </div>
</div>
</main>
    <div x-show="mode === 'menuju_adzan'" style="display: none;"
        class="bg-black/95 bg-frame absolute inset-0 z-50 flex flex-col items-center justify-center backdrop-blur-3xl transition-colors duration-500">
        <h2 class="text-slate-400 text-[6vh] md:text-[8vh] text-center font-bold uppercase tracking-[0.5em] mb-[1vh] transition-colors duration-500">Menjelang</h2>
        <p class="text-theme-main text-[6vh] md:text-[8vh] mb-[3vh] tracking-widest uppercase font-black animate-pulse transition-colors duration-500">Adzan <span
                x-text="currentPrayerName" class="text-white transition-colors duration-500"></span></p>
        <h1 class="text-white shadow-theme-text drop-shadow-[0_0_50px_rgba(16,185,129,0.5)] text-[25vh] font-black leading-none tabular-nums transition-colors duration-500"
            x-text="countdownAdzanDisplay">00:00</h1>
    </div>
    <div x-show="mode === 'adzan'" style="display: none;"
        class="bg-black/95 bg-frame fixed inset-0 z-[100] flex flex-col items-center justify-center backdrop-blur-3xl transition-colors duration-500">
        <h2 class="text-slate-400 text-[6vh] md:text-[8vh] text-center font-bold uppercase tracking-[0.5em] mb-[3vh] transition-colors duration-500">Adzan <span
                x-text="currentPrayerName" class="text-white transition-colors duration-500"></span></h2>
        <p class="text-theme-main text-[6vh] md:text-[8vh] tracking-widest uppercase font-black animate-pulse transition-colors duration-500">Telah Masuk</p>
    </div>
    <div x-show="mode === 'waiting_iqomah'" style="display: none;"
        class="bg-black/95 bg-frame fixed inset-0 z-[100] flex flex-col items-center justify-center backdrop-blur-3xl transition-colors duration-500">
        <h2 class="text-theme-main text-[6vh] mb-[4vh] tracking-[0.5em] text-center uppercase font-bold transition-colors duration-500">Menuju Iqomah:</h2>
        <h1 class="text-white shadow-theme-text text-[20vh] mb-[4vh] font-black leading-none tabular-nums transition-colors duration-500"
            x-text="countdownIqomahDisplay">00:00</h1>
        {{-- Konten Iqomah --}}
        <div class="h-[10vh] mt-[4vh] px-[10vw] relative w-full flex items-center justify-center">
            <template x-if="iqomahContents.length > 0">
                <template x-for="(content, index) in iqomahContents" :key="index">
                    <p x-show="activeIqomahContentIndex === index"
                       x-transition:enter="transition ease-out duration-1000"
                       x-transition:enter-start="opacity-0 translate-y-full"
                       x-transition:enter-end="opacity-100 translate-y-0"
                       x-transition:leave="transition ease-in duration-1000 absolute"
                       x-transition:leave-start="opacity-100 translate-y-0"
                       x-transition:leave-end="opacity-0 -translate-y-full"
                       class="text-theme-dark text-[3vh] md:text-[4vh] px-[10vw] text-center uppercase font-black transition-colors duration-500 absolute w-full"
                       x-text="content.teks"></p>
                </template>
            </template>
            <template x-if="iqomahContents.length === 0">
                <p class="text-theme-dark text-[3vh] md:text-[4vh] text-center uppercase tracking-wider font-black transition-colors duration-500 w-full animate-pulse">LURUSKAN DAN RAPATKAN SHAF</p>
            </template>
        </div>
    </div>
    <div x-show="mode === 'sholat'" style="display: none;"
        class="bg-black fixed inset-0 z-[100] flex flex-col items-center justify-center w-full h-full transition-colors duration-500 overflow-hidden">
        <p class="text-[6vh] font-black tracking-widest tabular-nums text-slate-600" x-text="time">00:00:00</p>
    </div>