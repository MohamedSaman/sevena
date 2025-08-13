<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

#[Title("admin setting management")]
#[Layout("components.layouts.admin")]
class SettingManagement extends Component
{
    public function render()
    {
        return view('livewire.admin.setting-management');
    }
}
