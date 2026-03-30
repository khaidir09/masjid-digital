<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\IqomahContent;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.app')]
#[Title('Manajemen Teks Iqomah')]
class IqomahContentManager extends Component
{
    use WithPagination;

    public $isModalOpen = false;
    public $isDeleteModalOpen = false;
    public $isEditMode = false;
    public $selectedId;

    public $teks, $urutan = 1, $is_active = true;
    public $canEdit = false;

    public function mount()
    {
        $this->canEdit = Auth::check() && in_array(Auth::user()->role, ['superadmin', 'admin']);
    }

    public function render()
    {
        return view('livewire.admin.iqomah-content-manager', [
            'iqomahContents' => IqomahContent::orderBy('urutan')->paginate(10)
        ]);
    }

    public function create()
    {
        if (!$this->canEdit) return;
        $this->resetInput();
        $this->isEditMode = false;
        $this->isModalOpen = true;
    }

    public function store()
    {
        if (!$this->canEdit) return;

        $this->validate([
            'teks' => 'required|string',
            'urutan' => 'required|integer',
        ]);

        IqomahContent::create([
            'teks' => $this->teks,
            'is_active' => $this->is_active,
            'urutan' => $this->urutan,
        ]);

        $this->closeModal();
        session()->flash('message', 'Teks Iqomah berhasil ditambahkan!');
    }

    public function edit($id)
    {
        if (!$this->canEdit) return;

        $ic = IqomahContent::findOrFail($id);
        $this->selectedId = $ic->id;
        $this->teks = $ic->teks;
        $this->urutan = $ic->urutan;
        $this->is_active = $ic->is_active;

        $this->isEditMode = true;
        $this->isModalOpen = true;
    }

    public function update()
    {
        if (!$this->canEdit) return;

        $this->validate([
            'teks' => 'required|string',
            'urutan' => 'required|integer',
        ]);

        $ic = IqomahContent::findOrFail($this->selectedId);
        $ic->update([
            'teks' => $this->teks,
            'is_active' => $this->is_active,
            'urutan' => $this->urutan,
        ]);

        $this->closeModal();
        session()->flash('message', 'Teks Iqomah berhasil diperbarui!');
    }

    public function deleteId($id)
    {
        if (!$this->canEdit) return;
        $this->selectedId = $id;
        $this->isDeleteModalOpen = true;
    }

    public function delete()
    {
        if (!$this->canEdit) return;
        IqomahContent::findOrFail($this->selectedId)->delete();
        $this->isDeleteModalOpen = false;
        session()->flash('message', 'Teks Iqomah telah dihapus!');
    }

    public function toggleStatus($id)
    {
        if (!$this->canEdit) return;
        $ic = IqomahContent::findOrFail($id);
        $ic->is_active = !$ic->is_active;
        $ic->save();
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->isDeleteModalOpen = false;
        $this->resetInput();
    }

    private function resetInput()
    {
        $this->selectedId = null;
        $this->teks = '';
        $this->urutan = 1;
        $this->is_active = true;
    }
}
