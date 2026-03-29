<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\IqomahContent;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;

class IqomahContentManager extends Component
{
    public $iqomahContents;
    public $teks, $urutan, $is_active = true;
    public $editId = null;
    public $showModal = false;

    public function mount()
    {
        $this->loadIqomahContents();
    }

    public function loadIqomahContents()
    {
        $this->iqomahContents = IqomahContent::orderBy('urutan')->get();
    }

    #[Computed]
    public function canEdit()
    {
        return Auth::check() && Auth::user()->role === 'admin';
    }

    public function create()
    {
        if (!$this->canEdit()) return;
        $this->resetFields();
        $this->showModal = true;
    }

    public function store()
    {
        if (!$this->canEdit()) return;

        $this->validate([
            'teks' => 'required|string',
            'urutan' => 'nullable|integer',
        ]);

        IqomahContent::create([
            'teks' => $this->teks,
            'is_active' => $this->is_active,
            'urutan' => $this->urutan ?? 0,
        ]);

        $this->showModal = false;
        $this->loadIqomahContents();
        session()->flash('message', 'Konten Iqomah berhasil ditambahkan.');
    }

    public function edit($id)
    {
        if (!$this->canEdit()) return;

        $ic = IqomahContent::findOrFail($id);
        $this->editId = $ic->id;
        $this->teks = $ic->teks;
        $this->urutan = $ic->urutan;
        $this->is_active = $ic->is_active;
        $this->showModal = true;
    }

    public function update()
    {
        if (!$this->canEdit()) return;

        $this->validate([
            'teks' => 'required|string',
            'urutan' => 'nullable|integer',
        ]);

        $ic = IqomahContent::findOrFail($this->editId);
        $ic->update([
            'teks' => $this->teks,
            'is_active' => $this->is_active,
            'urutan' => $this->urutan ?? 0,
        ]);

        $this->showModal = false;
        $this->loadIqomahContents();
        session()->flash('message', 'Konten Iqomah berhasil diupdate.');
    }

    public function delete($id)
    {
        if (!$this->canEdit()) return;
        IqomahContent::findOrFail($id)->delete();
        $this->loadIqomahContents();
        session()->flash('message', 'Konten Iqomah berhasil dihapus.');
    }

    public function toggleStatus($id)
    {
        if (!$this->canEdit()) return;
        $ic = IqomahContent::findOrFail($id);
        $ic->is_active = !$ic->is_active;
        $ic->save();
        $this->loadIqomahContents();
    }

    public function resetFields()
    {
        $this->editId = null;
        $this->teks = '';
        $this->urutan = 0;
        $this->is_active = true;
    }

    public function render()
    {
        return view('livewire.admin.iqomah-content-manager')->layout('layouts.app');
    }
}
