<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class StaffManagement extends Component
{
    // Default role sekarang adalah 'staff'
    public $staffId, $name, $email, $password, $role = 'staff';
    public $showModal = false;

    public function mount()
    {
        if (auth()->user()->role !== 'owner') {
            abort(403, 'Akses Ditolak. Anda bukan Owner.');
        }
    }

    public function save()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->staffId,
            'role' => 'required|in:staff,admin', // Sesuaikan dengan DB kamu
        ];

        if (!$this->staffId) {
            $rules['password'] = 'required|min:6';
        }

        $this->validate($rules);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'tenant_id' => auth()->user()->tenant_id,
            'email_verified_at' => now(),
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        $isUpdate = $this->staffId ? true : false;
        User::updateOrCreate(['id' => $this->staffId], $data);

        $this->resetForm();
        $this->showModal = false; 
        
        // Alert dinamis tergantung aksi
        $message = $isUpdate ? 'Data pegawai berhasil diperbarui!' : 'Pegawai baru berhasil ditambahkan!';
        session()->flash('success', $message);
    }

    public function edit($id)
    {
        $staff = User::where('tenant_id', auth()->user()->tenant_id)->findOrFail($id);
        $this->staffId = $staff->id;
        $this->name = $staff->name;
        $this->email = $staff->email;
        $this->role = $staff->role;
        $this->showModal = true;
    }

    public function delete($id)
    {
        $staff = User::where('tenant_id', auth()->user()->tenant_id)->findOrFail($id);
        
        if ($staff->id === auth()->id()) {
            session()->flash('error', 'Anda tidak bisa menghapus akun Anda sendiri!');
            return;
        }

        $staff->delete();
        session()->flash('success', 'Akses pegawai berhasil dicabut secara permanen!');
    }

    public function resetForm()
    {
        $this->reset(['staffId', 'name', 'email', 'password', 'role']);
        $this->role = 'staff'; // Kembalikan ke default
    }

    public function render()
    {
        $staffList = User::where('tenant_id', auth()->user()->tenant_id)->latest()->get();

        return view('livewire.staff-management', [
            'staffList' => $staffList
        ])->layout('components.layouts.app');
    }
}