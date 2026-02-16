<div class="space-y-8">

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 text-xs font-bold uppercase tracking-wider">
                    Panel Admin
                </span>
                <span class="text-xs text-slate-400">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">
                Assalamualaikum, <span class="text-{{ $theme_color }}-600">{{ Auth::user()->name }}</span>
            </h1>
            <p class="text-slate-500 text-sm mt-1">Semoga hari ini penuh keberkahan.</p>
        </div>

        <div class="flex items-center gap-3 bg-slate-50 px-4 py-3 rounded-2xl border border-slate-200">
            <span class="text-xs font-semibold text-slate-500 uppercase">Tema Warna:</span>
            <div class="flex gap-2">
                @foreach(['emerald', 'blue', 'violet', 'rose', 'amber'] as $color)
                    <button wire:click="changeTheme('{{ $color }}')"
                            class="w-6 h-6 rounded-full border-2 transition-all hover:scale-110 {{ $theme_color === $color ? 'border-slate-600 scale-110 shadow-md' : 'border-transparent' }} bg-{{ $color }}-500"
                            title="{{ ucfirst($color) }}">
                    </button>
                @endforeach
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <div class="relative overflow-hidden bg-white p-6 rounded-3xl border border-slate-100 shadow-sm group hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="absolute -right-6 -top-6 w-32 h-32 bg-{{ $theme_color }}-50 rounded-full group-hover:bg-{{ $theme_color }}-100 transition-colors"></div>
            <div class="relative">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-3 bg-{{ $theme_color }}-100 text-{{ $theme_color }}-600 rounded-2xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="font-semibold text-slate-600">Total Kas</span>
                </div>
                <h3 class="text-3xl font-bold text-slate-900 tracking-tight">Rp {{ number_format($saldo, 0, ',', '.') }}</h3>
                <p class="text-sm text-slate-400 mt-2 flex items-center gap-1">
                    <span class="text-emerald-500 font-medium flex items-center">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                        Update
                    </span>
                    Realtime
                </p>
            </div>
        </div>

        <div class="relative overflow-hidden bg-white p-6 rounded-3xl border border-slate-100 shadow-sm group hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="absolute -right-6 -top-6 w-32 h-32 bg-slate-50 rounded-full group-hover:bg-slate-100 transition-colors"></div>
            <div class="relative">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-3 bg-slate-100 text-slate-600 rounded-2xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="font-semibold text-slate-600">Jadwal Hari Ini</span>
                </div>
                @if($jadwal)
                    <div class="flex justify-between items-end">
                        <div>
                            <p class="text-xs text-slate-400 uppercase font-bold">Dzuhur</p>
                            <p class="text-xl font-bold text-slate-800">{{ \Carbon\Carbon::parse($jadwal->dzuhur)->format('H:i') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-slate-400 uppercase font-bold">Ashar</p>
                            <p class="text-xl font-bold text-slate-800">{{ \Carbon\Carbon::parse($jadwal->ashar)->format('H:i') }}</p>
                        </div>
                    </div>
                @else
                    <div class="text-center py-2">
                        <span class="text-red-500 font-bold bg-red-50 px-3 py-1 rounded-full text-xs">Data Kosong</span>
                    </div>
                @endif
            </div>
        </div>

        <div class="relative overflow-hidden bg-white p-6 rounded-3xl border border-slate-100 shadow-sm group hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="absolute -right-6 -top-6 w-32 h-32 bg-{{ $theme_color }}-50 rounded-full group-hover:bg-{{ $theme_color }}-100 transition-colors"></div>
            <div class="relative">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-3 bg-{{ $theme_color }}-100 text-{{ $theme_color }}-600 rounded-2xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <span class="font-semibold text-slate-600">Display TV</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="relative flex h-3 w-3">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-{{ $theme_color }}-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-3 w-3 bg-{{ $theme_color }}-500"></span>
                    </span>
                    <h3 class="text-2xl font-bold text-slate-900">Aktif</h3>
                </div>
                <p class="text-sm text-slate-400 mt-2">{{ $total_info }} Informasi ditampilkan.</p>
            </div>
        </div>

    </div>

    <div>
        <h3 class="font-bold text-lg text-slate-800 mb-4 px-1">Aksi Cepat</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

            <button class="flex flex-col items-center justify-center p-6 bg-white border border-slate-200 rounded-2xl hover:border-{{ $theme_color }}-500 hover:bg-{{ $theme_color }}-50 transition-all group">
                <div class="w-12 h-12 bg-slate-100 text-slate-500 rounded-2xl flex items-center justify-center mb-3 group-hover:bg-white group-hover:text-{{ $theme_color }}-600 transition-colors shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                </div>
                <span class="font-semibold text-slate-600 group-hover:text-{{ $theme_color }}-700">Input Kas</span>
            </button>

            <button class="flex flex-col items-center justify-center p-6 bg-white border border-slate-200 rounded-2xl hover:border-{{ $theme_color }}-500 hover:bg-{{ $theme_color }}-50 transition-all group">
                <div class="w-12 h-12 bg-slate-100 text-slate-500 rounded-2xl flex items-center justify-center mb-3 group-hover:bg-white group-hover:text-{{ $theme_color }}-600 transition-colors shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                </div>
                <span class="font-semibold text-slate-600 group-hover:text-{{ $theme_color }}-700">Ganti Teks</span>
            </button>

            <button class="flex flex-col items-center justify-center p-6 bg-white border border-slate-200 rounded-2xl hover:border-{{ $theme_color }}-500 hover:bg-{{ $theme_color }}-50 transition-all group">
                <div class="w-12 h-12 bg-slate-100 text-slate-500 rounded-2xl flex items-center justify-center mb-3 group-hover:bg-white group-hover:text-{{ $theme_color }}-600 transition-colors shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <span class="font-semibold text-slate-600 group-hover:text-{{ $theme_color }}-700">Upload Foto</span>
            </button>

            <button class="flex flex-col items-center justify-center p-6 bg-white border border-slate-200 rounded-2xl hover:border-{{ $theme_color }}-500 hover:bg-{{ $theme_color }}-50 transition-all group">
                <div class="w-12 h-12 bg-slate-100 text-slate-500 rounded-2xl flex items-center justify-center mb-3 group-hover:bg-white group-hover:text-{{ $theme_color }}-600 transition-colors shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
                <span class="font-semibold text-slate-600 group-hover:text-{{ $theme_color }}-700">Pengaturan</span>
            </button>
        </div>
    </div>
</div>
