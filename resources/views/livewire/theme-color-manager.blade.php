<div class="space-y-6 pb-10">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Manajemen Warna Tema</h1>
            <p class="text-slate-500 text-sm">Sesuaikan palet warna untuk tampilan Display TV.</p>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="bg-emerald-50 text-emerald-700 p-4 rounded-xl border border-emerald-200">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm h-fit">
            <h3 class="font-bold text-lg mb-6">{{ $is_edit ? 'Edit Warna' : 'Tambah Warna Baru' }}</h3>

            <form wire:submit.prevent="{{ $is_edit ? 'update' : 'store' }}" class="space-y-4">
                <div>
                    <label class="block text-xs font-black uppercase text-slate-400 mb-1">ID Nama (Tanpa Spasi)</label>
                    <input type="text" wire:model="name" class="w-full rounded-xl border-slate-300 focus:ring-emerald-500" placeholder="contoh: ocean-blue">
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-black uppercase text-slate-400 mb-1">Label Tampilan</label>
                    <input type="text" wire:model="label" class="w-full rounded-xl border-slate-300" placeholder="contoh: Biru Samudra">
                </div>

                <div class="grid grid-cols-3 gap-2">
                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-400 mb-1">Utama</label>
                        <input type="color" wire:model="main_color" class="w-full h-12 rounded-lg cursor-pointer">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-400 mb-1">Gelap</label>
                        <input type="color" wire:model="dark_color" class="w-full h-12 rounded-lg cursor-pointer">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-400 mb-1">Terang</label>
                        <input type="color" wire:model="light_color" class="w-full h-12 rounded-lg cursor-pointer">
                    </div>
                </div>

                <div class="flex gap-2 pt-4">
                    <button type="submit" class="flex-1 bg-slate-800 text-white py-3 rounded-xl font-bold hover:bg-slate-900 transition">
                        {{ $is_edit ? 'Simpan Perubahan' : 'Tambah Tema' }}
                    </button>
                    @if($is_edit)
                        <button type="button" wire:click="resetInput" class="bg-slate-200 text-slate-600 px-4 rounded-xl">Batal</button>
                    @endif
                </div>
            </form>
        </div>

        <div class="lg:col-span-2 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($colors as $color)
                <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-sm flex items-center justify-between group">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl shadow-inner flex flex-col overflow-hidden">
                            <div class="flex-1" style="background-color: {{ $color->main_color }}"></div>
                            <div class="h-1/3 flex">
                                <div class="flex-1" style="background-color: {{ $color->dark_color }}"></div>
                                <div class="flex-1" style="background-color: {{ $color->light_color }}"></div>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800">{{ $color->label }}</h4>
                            <p class="text-xs text-slate-400 font-mono">{{ $color->main_color }}</p>
                        </div>
                    </div>
                    <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button wire:click="edit({{ $color->id }})" class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </button>
                        <button onclick="confirm('Hapus tema ini?') || event.stopImmediatePropagation()" wire:click="delete({{ $color->id }})" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
