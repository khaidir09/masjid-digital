<div class="bg-white rounded-[3.5rem] shadow-2xl overflow-hidden border border-white/20">

    <div class="h-2 w-full bg-gradient-to-r from-emerald-500 to-emerald-300"></div>

    <div class="p-10 md:p-12">
        <div class="text-center mb-10">
            <div class="inline-flex p-4 rounded-[2rem] bg-slate-50 mb-6 shadow-inner">
                @if($setting && $setting->logo_path)
                    <img src="{{ Storage::url($setting->logo_path) }}" class="w-16 h-16 object-contain" alt="Logo">
                @else
                    <svg class="w-16 h-16 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                @endif
            </div>

            <h2 class="text-2xl font-black text-slate-900 tracking-tighter uppercase leading-tight">
                {{ $setting->nama_masjid ?? 'Masjid Digital' }}
            </h2>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-2 px-4">
                {{ $setting->alamat ?? 'Silahkan Login Untuk Mengelola Display' }}
            </p>
        </div>

        <form wire:submit="login" class="space-y-6">
            <div class="relative">
                <input wire:model="email" type="email" id="email" required placeholder=" "
                    class="floating-input peer block w-full appearance-none rounded-2xl border border-slate-200 bg-slate-50/50 px-6 py-4 text-slate-900 focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-0 placeholder-transparent font-bold transition-all" />
                <label for="email" class="absolute top-4 left-6 text-slate-400 transition-all duration-200 ease-out pointer-events-none origin-[0]">
                    Email Address
                </label>
                @error('email') <span class="text-[10px] text-rose-500 font-bold mt-1 ml-2 uppercase">{{ $message }}</span> @enderror
            </div>

            <div class="relative">
                <input wire:model="password" type="password" id="password" required placeholder=" "
                    class="floating-input peer block w-full appearance-none rounded-2xl border border-slate-200 bg-slate-50/50 px-6 py-4 text-slate-900 focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-0 placeholder-transparent font-bold transition-all" />
                <label for="password" class="absolute top-4 left-6 text-slate-400 transition-all duration-200 ease-out pointer-events-none origin-[0]">
                    Password
                </label>
                @error('password') <span class="text-[10px] text-rose-500 font-bold mt-1 ml-2 uppercase">{{ $message }}</span> @enderror
            </div>

            <div class="flex items-center justify-between px-2">
                <label class="flex items-center group cursor-pointer">
                    <input type="checkbox" wire:model="remember" class="w-5 h-5 rounded-lg border-slate-300 text-emerald-500 focus:ring-emerald-500/20 transition-all cursor-pointer">
                    <span class="ml-3 text-[10px] font-black text-slate-400 uppercase tracking-widest group-hover:text-slate-600 transition-colors">Ingat Saya</span>
                </label>
            </div>

            <button type="submit"
                class="relative flex w-full justify-center rounded-2xl bg-slate-900 px-6 py-5 text-xs font-black uppercase tracking-[0.2em] text-white shadow-xl hover:bg-emerald-600 hover:-translate-y-1 active:scale-95 transition-all duration-300 overflow-hidden">
                <span wire:loading.remove>Masuk Dashboard</span>
                <div wire:loading class="flex items-center gap-2">
                    <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Memverifikasi...</span>
                </div>
            </button>
        </form>

        <p class="mt-10 text-center text-[9px] font-black text-slate-300 uppercase tracking-[0.3em]">
            &copy; 2026 {{ $setting->nama_masjid ?? 'Masjid Digital' }}
        </p>
    </div>
</div>
