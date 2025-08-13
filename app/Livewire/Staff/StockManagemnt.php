<?php

namespace App\Livewire\Staff;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;


#[Title("Attendance Management")]
#[Layout("components.layouts.staff")]
class StockManagemnt extends Component
{
    public function render()
    {
        return view('livewire.staff.stock-managemnt');
    }
}
