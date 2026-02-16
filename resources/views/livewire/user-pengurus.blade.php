<div class="space-y-8 work-sans-all">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Work+Sans:wght@100..900&display=swap');

        .work-sans-all,
        .work-sans-all * {
            font-family: 'Work Sans', sans-serif !important;
            font-variant-numeric: tabular-nums;
        }
    </style>

    <div
        class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm flex flex-col md:flex-row justify-between items-center gap-8">
        <div class="flex items-center gap-6">
            <div
                class="w-20 h-20 bg-emerald-50 rounded-4xl flex items-center justify-center border border-emerald-100 text-emerald-600 shadow-inner">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                    </path>
                </svg>
            </div>
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tighter uppercase leading-none">Manajemen SDM
                </h1>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mt-2 flex items-center gap-2">
                    <span class="w-2 h-2 bg-emerald-500 rounded-full animate-ping"></span>
                    Pengurus & Akun Sistem
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2 bg-slate-50 p-2 rounded-4xl border border-slate-200 shadow-inner">
            <button wire:click="switchTab('pengurus')"
                class="px-8 py-3 rounded-3xl font-black text-[10px] uppercase tracking-widest transition-all {{ $activeTab == 'pengurus' ? 'bg-emerald-500 text-white shadow-lg' : 'text-slate-400 hover:text-slate-600' }}">
                Struktur Pengurus
            </button>
            <button wire:click="switchTab('users')"
                class="px-8 py-3 rounded-3xl font-black text-[10px] uppercase tracking-widest transition-all {{ $activeTab == 'users' ? 'bg-emerald-500 text-white shadow-lg' : 'text-slate-400 hover:text-slate-600' }}">
                Akun Admin
            </button>
        </div>
    </div>

    <div
        class="bg-white rounded-[3.5rem] border border-slate-100 shadow-2xl shadow-slate-200/40 overflow-hidden min-h-150 relative">

        <div
            class="px-12 py-10 border-b border-slate-50 flex flex-col md:flex-row justify-between items-center bg-slate-50/30 gap-4">
            <div class="relative w-full md:w-auto">
                <input wire:model.live="search" type="text" placeholder="Cari data..."
                    class="pl-12 pr-6 py-3 bg-white border border-slate-200 rounded-2xl text-sm font-bold text-slate-700 focus:ring-emerald-500 focus:border-emerald-500 w-full md:w-80 shadow-sm transition-all">
                <svg class="w-5 h-5 text-slate-400 absolute left-4 top-3.5" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <button wire:click="create"
                class="bg-slate-900 text-white px-8 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-emerald-600 transition-all shadow-xl active:scale-95 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah {{ $activeTab == 'users' ? 'Admin' : 'Pengurus' }}
            </button>
        </div>

        @if (session()->has('message'))
            <div
                class="absolute top-4 right-1/2 translate-x-1/2 bg-emerald-500 text-white px-8 py-4 rounded-2xl font-black text-xs shadow-xl z-50 animate-bounce">
                {{ session('message') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr
                        class="text-slate-400 text-[9px] uppercase tracking-[0.2em] font-black border-b border-slate-50 bg-slate-50/40">
                        @if ($activeTab == 'pengurus')
                            <th class="px-12 py-8">Profil</th>
                            <th class="px-6 py-8">Jabatan</th>
                            <th class="px-6 py-8">Kontak</th>
                            <th class="px-6 py-8 text-center">Status</th>
                        @else
                            <th class="px-12 py-8">Nama Lengkap</th>
                            <th class="px-6 py-8">Email Login</th>
                            <th class="px-6 py-8">Terdaftar</th>
                        @endif
                        <th class="px-12 py-8 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($data as $item)
                        <tr class="hover:bg-slate-50 transition-all duration-300 group">
                            @if ($activeTab == 'pengurus')
                                <td class="px-12 py-6">
                                    <div class="flex items-center gap-5">
                                        <div
                                            class="w-14 h-14 rounded-2xl bg-slate-100 overflow-hidden border border-slate-200">
                                            @if ($item->foto_path)
                                                <img src="{{ Storage::url($item->foto_path) }}"
                                                    class="w-full h-full object-cover">
                                            @else
                                                <div
                                                    class="w-full h-full flex items-center justify-center text-slate-300">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                                        </path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="font-black text-slate-800 text-sm">{{ $item->nama }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-6 font-bold text-xs text-slate-500 tracking-wide">
                                    {{ $item->jabatan }}</td>
                                <td class="px-6 py-6 font-bold text-xs text-slate-500">{{ $item->no_hp ?? '-' }}</td>
                                <td class="px-6 py-6 text-center">
                                    <button wire:click="toggleStatus({{ $item->id }})"
                                        class="px-4 py-2 rounded-xl text-[10px] font-black tracking-widest transition-all {{ $item->is_active ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-500' }}">
                                        {{ $item->is_active ? 'Aktif' : 'Non-Aktif' }}
                                    </button>
                                </td>
                            @else
                                <td class="px-12 py-6 font-black text-slate-800 text-sm">{{ $item->name }}</td>
                                <td class="px-6 py-6 font-bold text-xs text-slate-500">{{ $item->email }}</td>
                                <td class="px-6 py-6 font-bold text-xs text-slate-400">
                                    {{ $item->created_at->format('d M Y') }}</td>
                            @endif

                            <td class="px-12 py-6 text-right">
                                <div
                                    class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-all">
                                    <button wire:click="edit({{ $item->id }})"
                                        class="p-2 bg-slate-100 rounded-xl text-slate-600 hover:bg-emerald-500 hover:text-white transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                            </path>
                                        </svg>
                                    </button>
                                    <button wire:click="deleteId({{ $item->id }})"
                                        class="p-2 bg-rose-50 rounded-xl text-rose-500 hover:bg-rose-500 hover:text-white transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5"
                                class="px-12 py-20 text-center font-black text-slate-300 tracking-widest text-xs">Tidak
                                ada data ditemukan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-10 border-t border-slate-50 bg-slate-50/20 flex justify-center">{{ $data->links() }}</div>
    </div>

    @if ($isModalOpen)
        <div
            class="fixed inset-0 z-[9999] flex items-center justify-center bg-slate-900/90 backdrop-blur-sm p-4 overflow-y-auto">
            <div
                class="bg-white rounded-[2.5rem] p-8 md:p-10 w-full max-w-2xl shadow-2xl relative overflow-hidden animate-fade-in">
                <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-emerald-500 to-emerald-300"></div>

                <div class="flex justify-between items-center mb-8">
                    <h3 class="text-2xl font-black text-slate-800 uppercase tracking-tight">
                        {{ $isEditMode ? 'Edit' : 'Tambah' }} {{ $activeTab == 'users' ? 'Admin' : 'Pengurus' }}
                    </h3>
                    <button wire:click="closeModal"
                        class="bg-slate-100 p-2 rounded-full hover:bg-rose-100 hover:text-rose-500 transition-all">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="space-y-6">
                    @if ($activeTab == 'pengurus')
                        <div class="flex flex-col md:flex-row gap-8 items-start">

                            <div class="w-full md:w-1/3 flex flex-col gap-4" x-data="{
                                isCompressing: false,
                                progress: 0,
                                async compressImage(event) {
                                    const file = event.target.files[0];
                                    if (!file) return;
                                    this.isCompressing = true;
                                    this.progress = 20;

                                    const reader = new FileReader();
                                    reader.readAsDataURL(file);
                                    reader.onload = (e) => {
                                        const img = new Image();
                                        img.src = e.target.result;
                                        img.onload = () => {
                                            const canvas = document.createElement('canvas');
                                            let width = img.width;
                                            let height = img.height;
                                            const maxSize = 800;
                                            if (width > height) { if (width > maxSize) { height *= maxSize / width;
                                                    width = maxSize; } } else { if (height > maxSize) { width *= maxSize / height;
                                                    height = maxSize; } }
                                            canvas.width = width;
                                            canvas.height = height;
                                            const ctx = canvas.getContext('2d');
                                            ctx.drawImage(img, 0, 0, width, height);
                                            this.progress = 60;
                                            canvas.toBlob((blob) => {
                                                const fileNew = new File([blob], file.name, { type: 'image/jpeg' });
                                                @this.upload('foto', fileNew, () => {
                                                    this.isCompressing = false;
                                                    this.progress = 100;
                                                });
                                            }, 'image/jpeg', 0.7);
                                        }
                                    }
                                }
                            }">

                                <div
                                    class="relative w-full aspect-square bg-slate-50 rounded-[2.5rem] border-2 border-dashed border-slate-200 overflow-hidden group">
                                    @if ($foto)
                                        <img src="{{ $foto->temporaryUrl() }}" class="w-full h-full object-cover">
                                    @elseif($foto_path)
                                        <img src="{{ Storage::url($foto_path) }}" class="w-full h-full object-cover">
                                    @else
                                        <div
                                            class="w-full h-full flex flex-col items-center justify-center text-slate-300">
                                            <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                            <span class="text-[9px] font-black uppercase tracking-widest">Upload
                                                Foto</span>
                                        </div>
                                    @endif
                                    <input type="file" accept="image/*" @change="compressImage($event)"
                                        class="absolute inset-0 opacity-0 cursor-pointer z-10">

                                    <div x-show="isCompressing"
                                        class="absolute inset-0 bg-black/60 flex flex-col items-center justify-center text-white z-20">
                                        <svg class="w-8 h-8 animate-spin mb-2 text-emerald-400"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                        <div class="text-[9px] font-black uppercase tracking-widest"
                                            x-text="progress + '%'"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="w-full md:w-2/3 grid grid-cols-1 gap-6">
                                <div class="relative">
                                    <input type="text" wire:model="nama_pengurus" id="nama_pengurus"
                                        class="floating-input peer block w-full appearance-none rounded-2xl border border-slate-300 bg-transparent px-5 py-4 text-slate-900 focus:border-emerald-500 focus:outline-none focus:ring-0 placeholder-transparent font-bold"
                                        placeholder=" " />
                                    <label for="nama_pengurus"
                                        class="absolute top-4 left-5 text-slate-400 transition-all duration-200 ease-out pointer-events-none origin-[0]">Nama
                                        Lengkap</label>
                                    @error('nama_pengurus')
                                        <span
                                            class="text-[10px] text-rose-500 font-bold uppercase mt-1 ml-2">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="relative">
                                    <select wire:model="jabatan" id="jabatan"
                                        class="floating-input peer block w-full appearance-none rounded-2xl border border-slate-300 bg-transparent px-5 py-4 text-slate-900 focus:border-emerald-500 focus:outline-none focus:ring-0 font-bold cursor-pointer bg-white">
                                        <option value="" hidden></option>
                                        <option value="Ketua">Ketua</option>
                                        <option value="Wakil Ketua">Wakil Ketua</option>
                                        <option value="Sekertaris">Sekertaris</option>
                                        <option value="Bendahara">Bendahara</option>
                                        <option value="Penasehat">Penasehat</option>
                                        <option value="Humas">Humas</option>
                                        <option value="Sosial">Sosial</option>
                                        <option value="Operator">Operator</option>
                                        <option value="Marbot">Marbot</option>
                                        <option value="Lainnya">Lainnya...</option>
                                    </select>
                                    <label for="jabatan"
                                        class="absolute top-4 left-5 text-slate-400 transition-all duration-200 ease-out pointer-events-none origin-[0]">Pilih
                                        Jabatan</label>
                                    @error('jabatan')
                                        <span
                                            class="text-[10px] text-rose-500 font-bold uppercase mt-1 ml-2">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="relative">
                                    <input type="text" wire:model="no_hp" id="no_hp"
                                        class="floating-input peer block w-full appearance-none rounded-2xl border border-slate-300 bg-transparent px-5 py-4 text-slate-900 focus:border-emerald-500 focus:outline-none focus:ring-0 placeholder-transparent font-bold"
                                        placeholder=" " />
                                    <label for="no_hp"
                                        class="absolute top-4 left-5 text-slate-400 transition-all duration-200 ease-out pointer-events-none origin-[0]">No.
                                        HP / WhatsApp</label>
                                </div>
                            </div>
                        </div>

                        <div class="bg-emerald-50/50 p-6 rounded-[2.5rem] border border-emerald-100 mt-4 space-y-5">
                            <h4
                                class="text-[10px] font-black text-emerald-600 uppercase tracking-[0.2em] flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                    </path>
                                </svg>
                                Akses Login Pengurus
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div class="relative">
                                    <input type="email" wire:model="email" id="email"
                                        class="floating-input peer block w-full appearance-none rounded-2xl border border-slate-300 bg-white px-5 py-4 text-slate-900 focus:border-emerald-500 focus:outline-none focus:ring-0 placeholder-transparent font-bold"
                                        placeholder=" " />
                                    <label for="email"
                                        class="absolute top-4 left-5 text-slate-400 transition-all duration-200 ease-out pointer-events-none origin-[0]">Alamat
                                        Email</label>
                                    @error('email')
                                        <span
                                            class="text-[10px] text-rose-500 font-bold mt-1 ml-2">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="relative">
                                    <input type="password" wire:model="password" id="password"
                                        class="floating-input peer block w-full appearance-none rounded-2xl border border-slate-300 bg-white px-5 py-4 text-slate-900 focus:border-emerald-500 focus:outline-none focus:ring-0 placeholder-transparent font-bold"
                                        placeholder=" " />
                                    <label for="password"
                                        class="absolute top-4 left-5 text-slate-400 transition-all duration-200 ease-out pointer-events-none origin-[0]">
                                        {{ $isEditMode ? 'Password Baru (Opsional)' : 'Password' }}
                                    </label>

                                    @if ($isEditMode)
                                        <p class="text-[9px] text-slate-400 font-medium mt-1 ml-2 italic">
                                            *Kosongkan jika tidak ingin mengubah password
                                        </p>
                                    @endif

                                    @error('password')
                                        <span
                                            class="text-[10px] text-rose-500 font-bold mt-1 ml-2">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="space-y-6">
                            <div class="relative">
                                <input type="text" wire:model="name" id="name"
                                    class="floating-input peer block w-full appearance-none rounded-2xl border border-slate-300 bg-transparent px-5 py-4 text-slate-900 focus:border-emerald-500 focus:outline-none focus:ring-0 placeholder-transparent font-bold"
                                    placeholder=" " />
                                <label for="name"
                                    class="absolute top-4 left-5 text-slate-400 transition-all duration-200 ease-out pointer-events-none origin-[0]">Nama
                                    Admin</label>
                                @error('name')
                                    <span class="text-[10px] text-rose-500 font-bold mt-1 ml-2">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div class="relative">
                                    <input type="email" wire:model="email" id="email_admin"
                                        class="floating-input peer block w-full appearance-none rounded-2xl border border-slate-300 bg-transparent px-5 py-4 text-slate-900 focus:border-emerald-500 focus:outline-none focus:ring-0 placeholder-transparent font-bold"
                                        placeholder=" " />
                                    <label for="email_admin"
                                        class="absolute top-4 left-5 text-slate-400 transition-all duration-200 ease-out pointer-events-none origin-[0]">Email
                                        Login</label>
                                    @error('email')
                                        <span
                                            class="text-[10px] text-rose-500 font-bold mt-1 ml-2">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="relative">
                                    <input type="password" wire:model="password" id="password_admin"
                                        class="floating-input peer block w-full appearance-none rounded-2xl border border-slate-300 bg-transparent px-5 py-4 text-slate-900 focus:border-emerald-500 focus:outline-none focus:ring-0 placeholder-transparent font-bold"
                                        placeholder=" " />
                                    <label for="password_admin"
                                        class="absolute top-4 left-5 text-slate-400 transition-all duration-200 ease-out pointer-events-none origin-[0]">Password</label>
                                    @error('password')
                                        <span
                                            class="text-[10px] text-rose-500 font-bold mt-1 ml-2">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="flex gap-4 mt-10 pt-6 border-t border-slate-100">
                    <button wire:click="closeModal"
                        class="flex-1 py-4 rounded-2xl font-black text-slate-400 text-xs uppercase tracking-widest hover:bg-slate-50 transition-all">
                        Batal
                    </button>
                    <button wire:click="{{ $isEditMode ? 'update' : 'store' }}" wire:loading.attr="disabled"
                        class="flex-1 py-4 bg-emerald-500 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-emerald-600 shadow-lg shadow-emerald-200 transition-all flex items-center justify-center gap-3">

                        <svg wire:loading wire:target="{{ $isEditMode ? 'update' : 'store' }}"
                            class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>

                        <span wire:loading.remove wire:target="{{ $isEditMode ? 'update' : 'store' }}">
                            Simpan Perubahan
                        </span>
                        <span wire:loading wire:target="{{ $isEditMode ? 'update' : 'store' }}">
                            Memproses...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if ($isDeleteModalOpen)
        <div class="fixed inset-0 z-9999 flex items-center justify-center bg-rose-900/90 backdrop-blur-sm p-4">
            <div class="bg-white rounded-[2.5rem] p-10 w-full max-w-md text-center shadow-2xl">
                <div
                    class="w-20 h-20 bg-rose-100 rounded-full flex items-center justify-center mx-auto mb-6 text-rose-500">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                        </path>
                    </svg>
                </div>
                <h3 class="text-2xl font-black text-slate-800 uppercase tracking-tight mb-2">Hapus Data?</h3>
                <p class="text-slate-500 font-medium text-sm mb-8">Data yang dihapus tidak dapat dikembalikan lagi.</p>
                <div class="flex gap-4">
                    <button wire:click="closeModal"
                        class="flex-1 py-4 rounded-xl font-black text-slate-400 text-xs uppercase tracking-widest hover:bg-slate-50">Batal</button>
                    <button wire:click="delete"
                        class="flex-1 py-4 bg-rose-500 text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-rose-600 shadow-lg shadow-rose-200">Ya,
                        Hapus</button>
                </div>
            </div>
        </div>
    @endif
</div>
