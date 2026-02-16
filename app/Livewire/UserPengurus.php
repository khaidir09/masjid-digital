<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\User;
use App\Models\Pengurus;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.app')]
#[Title('Manajemen SDM Masjid')]
class UserPengurus extends Component
{
    use WithPagination, WithFileUploads;

    // --- STATE MANAGEMENT ---
    public $activeTab = 'pengurus'; // Opsi: 'pengurus' atau 'users'
    public $search = '';
    public $isModalOpen = false;
    public $isDeleteModalOpen = false;
    public $isEditMode = false;
    public $selectedId;

    // --- FORM PROPERTIES (USER ADMIN) ---
    public $name, $email, $password, $role = 'admin';

    // --- FORM PROPERTIES (PENGURUS) ---
    public $nama_pengurus, $jabatan, $no_hp, $is_active = true;

    // --- FILE UPLOAD ---
    public $foto;      // File object sementara (dari frontend)
    public $foto_path; // Path file di database

    // --- RENDER VIEW ---
    public function render()
    {
        $data = [];

        if ($this->activeTab == 'users') {
            // Tampilkan User yang murni Admin (tidak terhubung ke pengurus)
            $data = User::where('role', 'admin')
                        ->where(function($q) {
                            $q->where('name', 'like', '%'.$this->search.'%')
                              ->orWhere('email', 'like', '%'.$this->search.'%');
                        })
                        ->latest()
                        ->paginate(10);
        } else {
            // Tampilkan Pengurus + User Loginnya
            $data = Pengurus::with('user')
                        ->where('nama', 'like', '%'.$this->search.'%')
                        ->orWhere('jabatan', 'like', '%'.$this->search.'%')
                        ->latest()
                        ->paginate(10);
        }

        return view('livewire.user-pengurus', ['data' => $data]);
    }

    // --- LOGIC GANTI TAB & RESET ---
    public function switchTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
        $this->resetInput();
    }

    public function create()
    {
        $this->resetInput();
        $this->isEditMode = false;
        $this->isModalOpen = true;

        // Default role sesuai tab
        $this->role = ($this->activeTab == 'users') ? 'admin' : 'pengurus';
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->isDeleteModalOpen = false;
        $this->resetInput();
    }

    public function resetInput()
    {
        // Reset Admin Form
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->role = 'pengurus';

        // Reset Pengurus Form
        $this->nama_pengurus = '';
        $this->jabatan = '';
        $this->no_hp = '';
        $this->is_active = true;

        // Reset Foto
        $this->foto = null;
        $this->foto_path = null;
    }

    // --- PESAN VALIDASI BAHASA INDONESIA ---
    protected function messages()
    {
        return [
            'required' => 'Kolom ini wajib diisi ya, Pak/Bu.',
            'email' => 'Format email tidak valid (harus ada @).',
            'email.unique' => 'Email ini sudah terdaftar, gunakan email lain.',
            'min' => 'Isian terlalu pendek, minimal :min karakter.',
            'max' => 'Isian terlalu panjang/besar.',
            'image' => 'File harus berupa gambar (JPG/PNG).',
            'mimes' => 'Format file harus JPG, JPEG, atau PNG.',
            'in' => 'Pilihan role tidak valid.',
            'numeric' => 'Harus berupa angka.',
        ];
    }

    protected function validationAttributes()
    {
        return [
            'name' => 'Nama Admin',
            'nama_pengurus' => 'Nama Pengurus',
            'email' => 'Alamat Email',
            'password' => 'Kata Sandi',
            'jabatan' => 'Jabatan Masjid',
            'foto' => 'Foto Profil',
            'no_hp' => 'Nomor HP/WA',
        ];
    }

    // --- FUNGSI SIMPAN (STORE) ---
    public function store()
    {
        // 1. Tentukan Aturan Validasi
        $rules = [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ];

        if ($this->activeTab == 'users') {
            $rules['name'] = 'required';
            $rules['role'] = 'required';
        } else {
            $rules['nama_pengurus'] = 'required';
            $rules['jabatan'] = 'required';
            $rules['role'] = 'required|in:pengurus,bendahara,admin';
            $rules['foto'] = 'nullable|image|max:2048'; // Max 2MB (Safety net server)
        }

        // Jalankan Validasi dengan Pesan Indonesia
        $this->validate($rules, $this->messages(), $this->validationAttributes());

        DB::beginTransaction(); // Mulai Transaksi Database
        try {
            if ($this->activeTab == 'users') {
                // Simpan Admin
                User::create([
                    'name' => $this->name,
                    'email' => $this->email,
                    'password' => Hash::make($this->password),
                    'role' => 'admin',
                ]);
            } else {
                // Simpan Pengurus

                // A. Upload Foto ke Disk Public
                $path = null;
                if ($this->foto) {
                    // Simpan di: storage/app/public/pengurus
                    $path = $this->foto->store('pengurus', 'public');
                }

                // B. Buat Data Pengurus
                $pengurus = Pengurus::create([
                    'nama' => $this->nama_pengurus,
                    'jabatan' => $this->jabatan,
                    'no_hp' => $this->no_hp,
                    'foto_path' => $path,
                    'is_active' => $this->is_active,
                ]);

                // C. Buat Akun Login Otomatis
                User::create([
                    'name' => $this->nama_pengurus,
                    'email' => $this->email,
                    'password' => Hash::make($this->password),
                    'role' => $this->role,
                    'pengurus_id' => $pengurus->id,
                ]);
            }

            DB::commit(); // Simpan Permanen
            $this->closeModal();
            session()->flash('message', 'Alhamdulillah, data berhasil disimpan!');

        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan jika error
            session()->flash('message', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    // --- FUNGSI EDIT (LOAD DATA) ---
    public function edit($id)
    {
        $this->isEditMode = true;
        $this->selectedId = $id;

        if ($this->activeTab == 'users') {
            $user = User::find($id);
            $this->name = $user->name;
            $this->email = $user->email;
            $this->role = $user->role;
        } else {
            $p = Pengurus::with('user')->find($id);
            if(!$p) return;

            $this->nama_pengurus = $p->nama;
            $this->jabatan = $p->jabatan;
            $this->no_hp = $p->no_hp;
            $this->foto_path = $p->foto_path;
            $this->is_active = $p->is_active;

            // Load data login jika ada
            if ($p->user) {
                $this->email = $p->user->email;
                $this->role = $p->user->role;
            }
        }
        $this->isModalOpen = true;
    }

    // --- FUNGSI UPDATE ---
    public function update()
    {
        // Cek ID User untuk validasi Unique Email (Ignore self)
        $userIdToCheck = $this->activeTab == 'users'
            ? $this->selectedId
            : (Pengurus::find($this->selectedId)->user->id ?? null);

        $rules = [
            'email' => 'required|email|unique:users,email,' . $userIdToCheck,
        ];

        if ($this->activeTab == 'users') {
            $rules['name'] = 'required';
        } else {
            $rules['nama_pengurus'] = 'required';
            $rules['jabatan'] = 'required';
            $rules['role'] = 'required';
            $rules['foto'] = 'nullable|image|max:2048';
        }

        $this->validate($rules, $this->messages(), $this->validationAttributes());

        DB::beginTransaction();
        try {
            if ($this->activeTab == 'users') {
                // Update Admin
                $user = User::find($this->selectedId);
                $userData = ['name' => $this->name, 'email' => $this->email];

                if (!empty($this->password)) {
                    $userData['password'] = Hash::make($this->password);
                }
                $user->update($userData);

            } else {
                // Update Pengurus
                $pengurus = Pengurus::with('user')->find($this->selectedId);

                // A. Handle Foto Baru
                $path = $pengurus->foto_path;
                if ($this->foto) {
                    // Hapus foto lama jika ada
                    if ($path && Storage::disk('public')->exists($path)) {
                        Storage::disk('public')->delete($path);
                    }
                    // Simpan foto baru
                    $path = $this->foto->store('pengurus', 'public');
                }

                // B. Update Data Pengurus
                $pengurus->update([
                    'nama' => $this->nama_pengurus,
                    'jabatan' => $this->jabatan,
                    'no_hp' => $this->no_hp,
                    'foto_path' => $path,
                    'is_active' => $this->is_active,
                ]);

                // C. Update Akun Login
                if ($pengurus->user) {
                    $userData = [
                        'name' => $this->nama_pengurus,
                        'email' => $this->email,
                        'role' => $this->role,
                    ];
                    if (!empty($this->password)) {
                        $userData['password'] = Hash::make($this->password);
                    }
                    $pengurus->user->update($userData);
                } else {
                    // Buat user baru jika belum ada (safety)
                    User::create([
                        'name' => $this->nama_pengurus,
                        'email' => $this->email,
                        'password' => Hash::make($this->password ?? 'password123'),
                        'role' => $this->role,
                        'pengurus_id' => $pengurus->id,
                    ]);
                }
            }

            DB::commit();
            $this->closeModal();
            session()->flash('message', 'Data berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('message', 'Gagal update: ' . $e->getMessage());
        }
    }

    // --- FUNGSI DELETE ---
    public function deleteId($id)
    {
        $this->selectedId = $id;
        $this->isDeleteModalOpen = true;
    }

    public function delete()
    {
        DB::beginTransaction();
        try {
            if ($this->activeTab == 'users') {
                User::find($this->selectedId)->delete();
            } else {
                $pengurus = Pengurus::with('user')->find($this->selectedId);

                // Hapus Foto dari Storage
                if ($pengurus->foto_path && Storage::disk('public')->exists($pengurus->foto_path)) {
                    Storage::disk('public')->delete($pengurus->foto_path);
                }

                // Hapus Akun User Login
                if ($pengurus->user) {
                    $pengurus->user->delete();
                }

                // Hapus Data Pengurus
                $pengurus->delete();
            }

            DB::commit();
            $this->isDeleteModalOpen = false;
            session()->flash('message', 'Data berhasil dihapus permanen!');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('message', 'Gagal menghapus: ' . $e->getMessage());
        }
    }

    // --- TOGGLE STATUS ---
    public function toggleStatus($id)
    {
        $p = Pengurus::find($id);
        $p->is_active = !$p->is_active;
        $p->save();

        // Opsional: Disable login user jika pengurus non-aktif
        // if($p->user) { ... }
    }
}
