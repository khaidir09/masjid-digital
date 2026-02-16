<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ThemeColor;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.app')]
#[Title('Manajemen Warna Tema')]
class ThemeColorManager extends Component
{
    public $colors, $color_id;
    public $name, $label, $main_color, $dark_color, $light_color;
    public $is_edit = false;

    protected $rules = [
        'name' => 'required|alpha_dash|unique:theme_colors,name',
        'label' => 'required|string|max:50',
        'main_color' => 'required|string|max:7',
        'dark_color' => 'required|string|max:7',
        'light_color' => 'required|string|max:7',
    ];

    public function render()
    {
        $this->colors = ThemeColor::all();
        return view('livewire.theme-color-manager');
    }

    public function resetInput()
    {
        $this->name = '';
        $this->label = '';
        $this->main_color = '#10b981';
        $this->dark_color = '#064e3b';
        $this->light_color = '#a7f3d0';
        $this->is_edit = false;
        $this->color_id = null;
    }

    public function store()
    {
        $this->validate();

        ThemeColor::create([
            'name' => strtolower($this->name),
            'label' => $this->label,
            'main_color' => $this->main_color,
            'dark_color' => $this->dark_color,
            'light_color' => $this->light_color,
        ]);

        session()->flash('message', 'Warna tema baru berhasil ditambahkan!');
        $this->resetInput();
    }

    public function edit($id)
    {
        $color = ThemeColor::findOrFail($id);
        $this->color_id = $id;
        $this->name = $color->name;
        $this->label = $color->label;
        $this->main_color = $color->main_color;
        $this->dark_color = $color->dark_color;
        $this->light_color = $color->light_color;
        $this->is_edit = true;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|alpha_dash|unique:theme_colors,name,' . $this->color_id,
            'label' => 'required|string',
            'main_color' => 'required',
            'dark_color' => 'required',
            'light_color' => 'required',
        ]);

        $color = ThemeColor::findOrFail($this->color_id);
        $color->update([
            'name' => $this->name,
            'label' => $this->label,
            'main_color' => $this->main_color,
            'dark_color' => $this->dark_color,
            'light_color' => $this->light_color,
        ]);

        session()->flash('message', 'Warna tema berhasil diperbarui!');
        $this->resetInput();
    }

    public function delete($id)
    {
        ThemeColor::find($id)->delete();
        session()->flash('message', 'Warna tema dihapus.');
    }
}
