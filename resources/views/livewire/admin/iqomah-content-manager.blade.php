<div class="space-y-8 md:space-y-8 pb-20 work-sans-all">
    <style>
        .floating-input:focus ~ label, .floating-input:not(:placeholder-shown) ~ label {
            transform: translateY(-1.25rem) scale(0.8); background-color: white; padding-left: 4px; padding-right: 4px; color: #10b981; font-weight: 800; text-transform: uppercase;
        }
    </style>

    <div class="bg-white rounded-[3.5rem] border border-slate-100 shadow-xl p-8 md:p-12 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-50 rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tighter uppercase leading-none mb-2">Teks Iqomah</h1>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">Manajemen Himbauan Menuju Iqomah</p>
            </div>
            @if($canEdit)
            <button wire:click="create" class="bg-emerald-500 text-white px-8 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-900 transition-all shadow-xl active:scale-95 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Teks
            </button>
            @else
                <div class="flex items-center gap-3 bg-amber-50 border border-amber-100 px-5 py-3 rounded-2xl shadow-sm animate-fade-in w-full md:w-auto">
                    <div class="p-2 bg-amber-500 rounded-lg text-white shadow-sm shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m0-8V7m0 0v2m-9 1l1 1h16l1-1v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4z"></path><circle cx="12" cy="11" r="9" stroke="currentColor" stroke-width="2"></circle></svg>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[9px] font-black text-amber-600 uppercase tracking-widest leading-none mb-1">Mode Lihat Saja</span>
                        <p class="text-[10px] font-bold text-amber-700 leading-tight">
                            Role kamu <span class="underline uppercase decoration-amber-300 decoration-2">{{ auth()->user()->role }}</span> (Read-only).
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if (session()->has('message'))
        <div class="bg-emerald-500 text-white px-8 py-3 rounded-2xl font-black text-xs uppercase tracking-widest text-center shadow-lg animate-fade-in">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 gap-6">
        @forelse($iqomahContents as $content)
            <div class="group bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-lg hover:shadow-2xl transition-all duration-300 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-2 h-full {{ $content->is_active ? 'bg-emerald-500' : 'bg-rose-500' }}"></div>

                <div class="flex flex-col md:flex-row justify-between gap-6">
                    <div class="flex-1 space-y-4">
                        <div class="flex items-center gap-3">
                            @if ($canEdit)
                                <button wire:click="toggleStatus({{ $content->id }})" class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest transition-all {{ $content->is_active ? 'bg-emerald-100 text-emerald-600 hover:bg-rose-100 hover:text-rose-600' : 'bg-rose-100 text-rose-600 hover:bg-emerald-100 hover:text-emerald-600' }}">
                                    {{ $content->is_active ? 'Status: Aktif' : 'Status: Non-Aktif' }}
                                </button>
                            @else
                                <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest {{ $content->is_active ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-600' }}">
                                    {{ $content->is_active ? 'Status: Aktif' : 'Status: Non-Aktif' }}
                                </span>
                            @endif
                            <span class="text-slate-400 font-bold text-xs">Urutan: {{ $content->urutan }}</span>
                        </div>

                        <p class="text-slate-800 font-black text-2xl tracking-tight leading-relaxed">
                            {{ $content->teks }}
                        </p>
                    </div>
                    @if($canEdit)
                    <div class="flex md:flex-col gap-2 shrink-0">
                        <button wire:click="edit({{ $content->id }})" class="p-4 bg-slate-50 text-slate-400 rounded-2xl hover:bg-emerald-50 hover:text-emerald-500 transition-all"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg></button>
                        <button wire:click="deleteId({{ $content->id }})" class="p-4 bg-slate-50 text-slate-400 rounded-2xl hover:bg-rose-50 hover:text-rose-500 transition-all"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                    </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="py-20 text-center font-black text-slate-300 uppercase tracking-widest text-sm">Belum ada teks himbauan</div>
        @endforelse

        <div class="mt-8">
            {{ $iqomahContents->links() }}
        </div>
    </div>

    @if($isModalOpen && $canEdit)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/90 backdrop-blur-sm p-4 animate-fade-in">
        <div class="bg-white rounded-[3.5rem] p-10 w-full max-w-2xl shadow-2xl relative overflow-hidden overflow-y-auto max-h-[90vh] custom-scrollbar">
            <div class="absolute top-0 left-0 w-full h-2 bg-linear-to-r from-emerald-500 to-emerald-300"></div>

            <h3 class="text-2xl font-black text-slate-800 uppercase tracking-tight mb-8">{{ $isEditMode ? 'Edit' : 'Tambah' }} Teks Iqomah</h3>

            <div class="space-y-6">
                <div class="relative">
                    <textarea wire:model="teks" id="teks" rows="4" class="floating-input peer block w-full appearance-none rounded-2xl border border-slate-300 bg-white px-5 py-4 text-slate-900 focus:border-emerald-500 focus:outline-none focus:ring-0 placeholder-transparent font-bold" placeholder=" "></textarea>
                    <label for="teks" class="absolute top-4 left-5 text-slate-400 transition-all duration-200 ease-out pointer-events-none origin-left">Teks Himbauan</label>
                    @error('teks') <span class="text-rose-500 text-xs font-bold mt-2 block">{{ $message }}</span> @enderror
                </div>

                <div class="relative">
                    <input type="number" wire:model="urutan" id="urutan" class="floating-input peer block w-full appearance-none rounded-2xl border border-slate-300 bg-white px-5 py-4 text-slate-900 focus:border-emerald-500 focus:outline-none focus:ring-0 placeholder-transparent font-bold" placeholder=" " />
                    <label for="urutan" class="absolute top-4 left-5 text-slate-400 transition-all duration-200 ease-out pointer-events-none origin-left">Urutan Tampil</label>
                    @error('urutan') <span class="text-rose-500 text-xs font-bold mt-2 block">{{ $message }}</span> @enderror
                </div>

                <div class="flex items-center gap-3 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                    <input type="checkbox" wire:model="is_active" id="is_active" class="w-5 h-5 rounded text-emerald-500 border-slate-300 focus:ring-emerald-500 bg-white">
                    <label for="is_active" class="text-sm font-bold text-slate-700 select-none">Aktif (Tampil di Layar)</label>
                </div>
            </div>

            <div class="flex gap-4 mt-12 border-t border-slate-100 pt-8">
                <button wire:click="closeModal" class="flex-1 py-4 rounded-xl font-black text-slate-400 text-xs uppercase tracking-widest hover:bg-slate-50 transition-all">Batal</button>
                <button wire:click="{{ $isEditMode ? 'update' : 'store' }}" class="flex-1 py-4 bg-emerald-500 text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-emerald-600 shadow-lg shadow-emerald-200 transition-all">Simpan Teks</button>
            </div>
        </div>
    </div>
    @endif

    @if($isDeleteModalOpen && $canEdit)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-rose-900/90 backdrop-blur-sm p-4 animate-fade-in">
        <div class="bg-white rounded-[3rem] p-10 w-full max-w-md text-center shadow-2xl relative overflow-hidden">
            <div class="w-24 h-24 bg-rose-100 rounded-full flex items-center justify-center mx-auto mb-6 text-rose-500 shadow-inner">
                 <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            </div>
            <h3 class="text-2xl font-black text-slate-800 uppercase tracking-tight mb-2">Hapus Teks?</h3>
            <p class="text-slate-500 font-medium text-sm mb-8">Data ini tidak akan muncul lagi di display.</p>
            <div class="flex gap-4">
                <button wire:click="closeModal" class="flex-1 py-4 rounded-xl font-black text-slate-400 text-xs uppercase tracking-widest hover:bg-slate-50 transition-all">Batal</button>
                <button wire:click="delete" class="flex-1 py-4 bg-rose-500 text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-rose-600 shadow-lg shadow-rose-200 transition-all">Ya, Hapus</button>
            </div>
        </div>
    </div>
    @endif
</div>
