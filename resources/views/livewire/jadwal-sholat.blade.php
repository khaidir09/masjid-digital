<div class="work-sans-all relative" style="font-family: 'Work Sans', sans-serif !important;">

    @php
        $setting = \App\Models\AppSetting::first();
    @endphp

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Work+Sans:wght@100..900&display=swap');

        .work-sans-all, .work-sans-all * {
            font-family: 'Work Sans', sans-serif !important;
            font-variant-numeric: tabular-nums;
            font-style: normal !important;
        }

        /* Loading Orbit Animation */
        .orbit-container { position: relative; width: 80px; height: 80px; }
        .orbit-ring { position: absolute; width: 100%; height: 100%; border: 4px solid transparent; border-top-color: #10b981; border-radius: 50%; animation: orbit-rotate 1.2s linear infinite; }
        .orbit-ring-inner { position: absolute; width: 70%; height: 70%; top: 15%; left: 15%; border: 4px solid transparent; border-bottom-color: #34d399; border-radius: 50%; animation: orbit-rotate-rev 2s linear infinite; }
        @keyframes orbit-rotate { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
        @keyframes orbit-rotate-rev { from { transform: rotate(360deg); } to { transform: rotate(0deg); } }
        @keyframes shimmer { 0% { transform: translateX(-100%); } 100% { transform: translateX(100%); } }
        .animate-shimmer { animation: shimmer 2s infinite; }
    </style>

    <div x-show="$wire.isGenerating"
        x-transition:enter="transition ease-out duration-500"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        style="display: none;"
        class="fixed inset-0 z-9999 flex flex-col items-center justify-center bg-slate-900/98 backdrop-blur-2xl p-4 md:p-10 overflow-y-auto">

        <div class="w-full max-w-2xl mx-auto my-auto flex flex-col items-center justify-center space-y-8">

            <div class="orbit-container">
                <div class="orbit-ring"></div>
                <div class="orbit-ring-inner"></div>
            </div>

            <div class="text-center w-full space-y-4">
                <h2 class="text-2xl md:text-3xl font-black uppercase tracking-tighter text-white">Sinkronisasi Jadwal</h2>

                <div class="bg-white/5 p-6 md:p-8 rounded-[2.5rem] border border-white/10 shadow-2xl w-full">
                    <p class="text-emerald-400 font-black text-lg md:text-xl uppercase tracking-tight mb-1">
                        {{ $setting->nama_masjid }}
                    </p>
                    <p class="text-slate-400 text-xs md:text-sm font-medium">
                        {{ $setting->alamat }}
                    </p>
                    <div class="h-px w-full bg-white/10 my-4"></div>
                    <p class="text-white text-sm md:text-base font-bold uppercase tracking-wide">
                        Periode: <span class="text-emerald-400">1 - {{ $totalDaysInMonth }}
                        {{ \Carbon\Carbon::create()->month((int)$bulan_generate)->translatedFormat('F') }}
                        {{ $tahun_generate }}</span>
                    </p>

                    <div class="mt-4 pt-4 border-t border-white/5 space-y-3">
                        <div class="flex flex-col items-center">
                            <span class="text-[8px] font-black text-slate-500 uppercase tracking-widest">Target Location:</span>
                            <span class="text-[10px] text-white font-bold uppercase tracking-tight">
                                {{ $setting->kota_nama ?? 'KOTA PEKANBARU' }} <span class="text-emerald-500">—</span> {{ $setting->kota_id }}
                            </span>
                        </div>
                        <div class="flex flex-col items-center">
                            <span class="text-[8px] font-black text-slate-500 uppercase tracking-widest mb-1">Schedule API:</span>
                            <span class="text-[9px] text-emerald-500/70 font-mono break-all leading-tight">
                                {{ rtrim($setting->api_jadwal_sholat, '/') }}/{{ $setting->kota_id }}/{{ $currentDate }}
                            </span>
                        </div>
                        <div class="flex flex-col items-center">
                            <span class="text-[8px] font-black text-slate-500 uppercase tracking-widest mb-1">Hijri API:</span>
                            <span class="text-[9px] text-emerald-500/70 font-mono break-all leading-tight">
                                {{ rtrim($setting->api_hijriah, '/') }}/{{ $currentDate }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-slate-800/40 p-6 md:p-10 rounded-[3rem] border border-white/5 shadow-inner w-full relative overflow-hidden">
                <div class="flex justify-between items-center mb-4 text-white">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                        <span class="text-emerald-400 text-xs font-black uppercase tracking-widest" x-text="$wire.statusText"></span>
                    </div>
                    <span class="text-xs font-black font-mono bg-white/10 px-2 py-1 rounded" x-text="$wire.currentDay + ' / ' + $wire.totalDaysInMonth"></span>
                </div>

                <div class="w-full bg-slate-950 rounded-full h-6 p-1 shadow-inner border border-white/5 overflow-hidden mb-4">
                    <div class="bg-linear-to-r from-emerald-600 to-emerald-400 h-full rounded-full transition-all duration-300 ease-out relative"
                         :style="'width: ' + $wire.progress + '%'">
                         <div class="absolute inset-0 bg-linear-to-r from-transparent via-white/20 to-transparent w-full animate-shimmer"></div>
                    </div>
                </div>

                <div class="text-4xl md:text-5xl font-black text-white tracking-tighter" x-text="$wire.progress + '%'"></div>
            </div>

            <div class="flex flex-col items-center gap-2">
                <div class="flex items-center gap-3 text-rose-500 bg-rose-500/10 px-4 py-2 rounded-full border border-rose-500/10">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <p class="text-[9px] font-black uppercase tracking-widest">Jangan menutup aplikasi hingga proses selesai</p>
                </div>
            </div>
        </div>
    </div>

    <div class="p-4 md:p-8 space-y-8">
        <div class="bg-white p-6 md:p-8 rounded-[2.5rem] border border-slate-100 shadow-sm flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-6 text-center md:text-left">
                <div class="w-16 h-16 md:w-20 md:h-20 bg-emerald-50 rounded-4xl flex items-center justify-center text-emerald-600 shadow-inner shrink-0">
                    <svg class="w-8 h-8 md:w-10 md:h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <div>
                    <h1 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tighter uppercase leading-none">Jadwal Sholat</h1>
                    <p class="text-slate-400 text-[10px] md:text-xs font-bold uppercase tracking-widest mt-2 flex items-center justify-center md:justify-start gap-2">
                        <span class="w-2 h-2 bg-emerald-500 rounded-full animate-ping"></span>
                        Sinkronisasi Data MyQuran / Kemenag RI
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3 bg-slate-50 p-2 rounded-3xl border border-slate-200">
                <div class="flex flex-col px-3 border-r border-slate-200">
                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-tighter">Bulan</span>
                    <select wire:model="bulan_generate" class="bg-transparent border-none focus:ring-0 font-black text-slate-800 text-sm py-0">
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}">{{ \Carbon\Carbon::create()->month((int)$m)->translatedFormat('F') }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex flex-col px-3">
                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-tighter">Tahun</span>
                    <input type="number" wire:model="tahun_generate" class="w-20 bg-transparent border-none focus:ring-0 font-black text-slate-800 text-sm py-0" />
                </div>
                <button wire:click="startGenerate" class="bg-slate-950 text-white px-6 md:px-10 py-3 md:py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-emerald-600 transition-all shadow-xl active:scale-95">
                    Sinkronkan
                </button>
            </div>
        </div>

        <div class="bg-white rounded-[3rem] border border-slate-100 shadow-2xl shadow-slate-200/40 overflow-hidden min-h-125">
            <div class="px-8 md:px-12 py-8 md:py-10 border-b border-slate-50 flex flex-col md:flex-row justify-between items-center bg-slate-50/30 gap-4">
                <h3 class="font-black text-xl md:text-2xl text-slate-800 uppercase tracking-tight">Tabel Jadwal {{ $tahun_generate }}</h3>
                <div class="bg-white px-4 py-2 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-2">
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Filter:</span>
                    <select wire:model.live="bulan_filter" class="border-none bg-transparent font-black text-slate-800 focus:ring-0 py-1 text-xs md:text-sm">
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}">{{ \Carbon\Carbon::create()->month((int)$m)->translatedFormat('F') }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-slate-400 text-[9px] uppercase tracking-[0.2em] font-black border-b border-slate-50 bg-slate-50/40">
                            <th class="px-8 md:px-12 py-8">Hari & Tanggal</th>
                            <th class="px-4 py-8 text-center">Imsak</th>
                            <th class="px-4 py-8 text-center">Subuh</th>
                            <th class="px-4 py-8 text-center text-emerald-600">Dzuhur</th>
                            <th class="px-4 py-8 text-center">Ashar</th>
                            <th class="px-4 py-8 text-center text-rose-500">Maghrib</th>
                            <th class="px-4 py-8 text-center">Isya</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($data_jadwal as $item)
                            @php $d = \Carbon\Carbon::parse($item->tanggal); @endphp
                            <tr class="{{ $d->isToday() ? 'bg-emerald-50/60' : '' }} hover:bg-slate-50 transition-all duration-300">
                                <td class="px-8 md:px-12 py-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-14 h-14 md:w-16 md:h-16 rounded-2xl bg-white border border-slate-100 flex flex-col items-center justify-center shadow-sm border-b-4 border-b-emerald-500 shrink-0">
                                            <span class="text-lg md:text-xl font-black text-slate-900 leading-none">{{ $d->format('d') }}</span>
                                            <span class="text-[9px] font-black text-slate-400 uppercase mt-1 tracking-tighter">{{ $d->format('M') }}</span>
                                        </div>
                                        <div>
                                            <div class="text-sm md:text-base font-black text-slate-800 uppercase tracking-tight">{{ $d->translatedFormat('l') }}</div>
                                            <div class="text-[10px] font-bold text-emerald-600 mt-1 uppercase">{{ $item->tanggal_hijriah }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-6 text-center font-black text-xs text-slate-400 font-mono">{{ substr($item->imsak, 0, 5) }}</td>
                                <td class="px-4 py-6 text-center font-black text-lg md:text-xl text-slate-900 font-mono">{{ substr($item->subuh, 0, 5) }}</td>
                                <td class="px-4 py-6 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <span class="font-black text-lg md:text-xl text-emerald-600 font-mono">{{ substr($item->dzuhur, 0, 5) }}</span>
                                        @if($d->isFriday())
                                            <span class="mt-1 bg-emerald-500 text-white text-[8px] font-black px-2 py-1 rounded-md uppercase">Jum'at</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-6 text-center font-black text-base text-slate-700 font-mono">{{ substr($item->ashar, 0, 5) }}</td>
                                <td class="px-4 py-6 text-center font-black text-lg md:text-xl text-rose-600 font-mono">{{ substr($item->maghrib, 0, 5) }}</td>
                                <td class="px-4 py-6 text-center font-black text-base text-slate-700 font-mono">{{ substr($item->isya, 0, 5) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="px-10 py-20 text-center font-black text-slate-300 uppercase tracking-widest text-sm">Data Kosong</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-10 flex justify-center bg-slate-50/20 border-t border-slate-50">{{ $data_jadwal->links() }}</div>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('process-next-day', () => {
                setTimeout(() => {
                    @this.generateNextDay();
                }, 50);
            });
        });
    </script>
</div>
