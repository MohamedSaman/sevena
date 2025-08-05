<?php

namespace App\Livewire\Staff;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

#[Title("Staff Dashboard")]
#[Layout("components.layouts.staff")]
class SettingManagement extends Component
{
    public function render()
    {
        return view('livewire.staff.setting-management');
    }
}
