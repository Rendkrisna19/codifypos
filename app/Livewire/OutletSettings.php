<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class OutletSettings extends Component
{
    use WithFileUploads;

    // Profil & Outlet
    public $tenantName;
    public $name;
    public $email;
    public $photo; // Untuk menampung file upload sementara
    
    // Keamanan
    public $currentPassword;
    public $newPassword;
    public $newPassword_confirmation;

    public function mount()
    {
        $user = auth()->user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->tenantName = $user->tenant->name ?? '';
    }

    public function updateProfile()
    {
        $user = auth()->user();

        $this->validate([
            'tenantName' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'photo' => 'nullable|image|max:2048', // Maksimal 2MB
        ]);

        // Update Nama Outlet (Tenant)
        if ($user->role === 'owner' && $user->tenant) {
            $user->tenant->update(['name' => $this->tenantName]);
        }

        // Proses Upload Foto Baru
        if ($this->photo) {
            // Hapus foto lama jika ada
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            $photoPath = $this->photo->store('profile-photos', 'public');
            $user->profile_photo = $photoPath;
        }

        $user->name = $this->name;
        $user->email = $this->email;
        $user->save();

        session()->flash('success_profile', 'Profil dan Outlet berhasil diperbarui!');
    }

    public function updatePassword()
    {
        $this->validate([
            'currentPassword' => 'required|current_password',
            'newPassword' => ['required', 'confirmed', Password::min(8)],
        ]);

        auth()->user()->update([
            'password' => Hash::make($this->newPassword)
        ]);

        $this->reset(['currentPassword', 'newPassword', 'newPassword_confirmation']);
        session()->flash('success_password', 'Password berhasil diubah!');
    }

    public function render()
    {
        return view('livewire.outlet-settings')->layout('components.layouts.app');
    }
}