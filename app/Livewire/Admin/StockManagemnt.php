<?php

namespace App\Livewire\Admin;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;


#[Title("Attendance Management")]
#[Layout("components.layouts.admin")]
class StockManagemnt extends Component
{
    public function render()
    {
        return view('livewire.admin.stock-managemnt');
    }
}
