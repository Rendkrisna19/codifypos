<?php

namespace App\Livewire;

use Livewire\Component;

class Dashboard extends Component
{
    public $totalSales = 2450000;
    public $totalOrders = 84;
    public $margin = 850000;

    // Fitur Live Update Dummy
    public function updateDummyData()
    {
        $this->totalSales += rand(50000, 150000);
        $this->totalOrders += rand(1, 3);
        $this->margin += rand(15000, 45000);
    }

    public function render()
    {
        return view('livewire.dashboard')->layout('components.layouts.app');
    }
}