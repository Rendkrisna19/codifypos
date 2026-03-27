<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Package;

#[Layout('layouts.admin')] // Sesuaikan jika layout Anda berbeda
class BillingPlans extends Component
{
    public $packages;
    
    // Form Properties
    public $planId;
    public $name = '';
    public $duration_days = '';
    public $price = '';
    public $features = '';

    // Modal State
    public $isModalOpen = false;
    public $isEditMode = false;

    public function render()
    {
        $this->packages = Package::orderBy('price', 'asc')->get();
        return view('livewire.admin.billing-plans');
    }

    public function openModal()
    {
        $this->resetInputFields();
        $this->isEditMode = false;
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->planId = null;
        $this->name = '';
        $this->duration_days = '';
        $this->price = '';
        $this->features = '';
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'duration_days' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'features' => 'nullable|string',
        ]);

        Package::create([
            'name' => $this->name,
            'duration_days' => $this->duration_days,
            'price' => $this->price,
            'features' => $this->features,
        ]);

        session()->flash('success', 'Paket Berlangganan berhasil ditambahkan.');
        $this->closeModal();
    }

    public function edit($id)
    {
        $plan = Package::findOrFail($id);
        $this->planId = $id;
        $this->name = $plan->name;
        $this->duration_days = $plan->duration_days;
        $this->price = round($plan->price); // Hilangkan .00 untuk edit
        $this->features = $plan->features;

        $this->isEditMode = true;
        $this->isModalOpen = true;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'duration_days' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'features' => 'nullable|string',
        ]);

        $plan = Package::findOrFail($this->planId);
        $plan->update([
            'name' => $this->name,
            'duration_days' => $this->duration_days,
            'price' => $this->price,
            'features' => $this->features,
        ]);

        session()->flash('success', 'Paket Berlangganan berhasil diperbarui.');
        $this->closeModal();
    }

    public function delete($id)
    {
        Package::findOrFail($id)->delete();
        session()->flash('success', 'Paket Berlangganan berhasil dihapus.');
    }
}