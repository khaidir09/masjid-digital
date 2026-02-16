<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\RunningText; // Pastikan model ini sudah ada
use Livewire\WithPagination;
use App\Models\AppSetting;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.app')]
#[Title('Manajemen Running Text')]
class RunningTextManager extends Component
{
    use WithPagination;

    public $isModalOpen = false;
    public $isDeleteModalOpen = false;
    public $isEditMode = false;
    public $selectedId;

    // Form Properties
    public $teks, $tipe = 'info', $is_active = true, $urutan = 0;
    public $kecepatan = 5;

    public function render()
    {
        // Ambil Data Running Text
        $data = RunningText::orderBy('urutan', 'asc')->latest()->paginate(10);

        // Ambil Global Speed dari AppSetting
        // Pastikan ada default value (misal 5) jika tabel kosong
        $setting = AppSetting::first();
        $globalSpeed = $setting->running_text_speed ?? 5;

        return view('livewire.running-text-manager', [
            'data' => $data,
            'globalSpeed' => $globalSpeed // Kirim ke View
        ]);
    }

    public function create()
    {
        $this->resetInput();
        $this->isEditMode = false;
        $this->isModalOpen = true;
    }

    public function store()
    {
        $this->validate([
            'teks' => 'required|min:5',
            'tipe' => 'required',
            'kecepatan' => 'required|numeric|min:1|max:10',
        ]);

        RunningText::create([
            'teks' => $this->teks,
            'tipe' => $this->tipe,
            'is_active' => $this->is_active,
            'urutan' => RunningText::max('urutan') + 1,
            'kecepatan' => $this->kecepatan,
        ]);

        $this->closeModal();
        session()->flash('message', 'Teks berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $this->isEditMode = true;
        $this->selectedId = $id;
        $rt = RunningText::find($id);
        $this->teks = $rt->teks;
        $this->tipe = $rt->tipe;
        $this->is_active = $rt->is_active;
        $this->urutan = $rt->urutan;
        $this->kecepatan = $rt->kecepatan;
        $this->isModalOpen = true;
    }

    public function update()
    {
        $this->validate(['teks' => 'required', 'tipe' => 'required']);

        RunningText::find($this->selectedId)->update([
            'teks' => $this->teks,
            'tipe' => $this->tipe,
            'is_active' => $this->is_active,
            'urutan' => $this->urutan,
            'kecepatan' => $this->kecepatan,
        ]);

        $this->closeModal();
        session()->flash('message', 'Teks diperbarui!');
    }

    public function toggleStatus($id)
    {
        $rt = RunningText::find($id);
        $rt->is_active = !$rt->is_active;
        $rt->save();
    }

    public function deleteId($id)
    {
        $this->selectedId = $id;
        $this->isDeleteModalOpen = true;
    }

    public function delete()
    {
        RunningText::find($this->selectedId)->delete();
        $this->isDeleteModalOpen = false;
        session()->flash('message', 'Teks dihapus!');
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->isDeleteModalOpen = false;
        $this->resetInput();
    }

    private function resetInput()
    {
        $this->teks = '';
        $this->tipe = 'info';
        $this->is_active = true;
        $this->urutan = 0;
        $this->kecepatan = 5;
    }
}
