<?php

namespace App\Livewire\Staff;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

use Illuminate\Support\Facades\Auth;

#[Title("Staff Dashboard")]
#[Layout("components.layouts.staff")]
class StaffDashboard extends Component
{
    
    
    public function mount()
    {
     
    }

    public function render()
    {
        return view('livewire.staff.staff-dashboard');
    }
    public $sidebarOpen = false;

public function toggleSidebar()
{
    $this->sidebarOpen = !$this->sidebarOpen;
}
}
