<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use App\Mail\SendOtpMail;
use Illuminate\Support\Facades\Mail;

class Register extends Component
{
    public $name = '';
    public $cafe_name = '';
    public $email = '';
    public $phone = ''; // Tambahkan properti nomor telepon
    public $password = '';
    public $password_confirmation = '';

    public function register()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'cafe_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            // Wajib isi, harus angka, panjang 10-15 digit
            'phone' => 'required|numeric|digits_between:10,15|unique:users', 
            'password' => 'required|string|min:8|confirmed',
        ], [
            // Kustomisasi pesan error agar lebih ramah dibaca
            'phone.required' => 'Nomor WhatsApp wajib diisi untuk notifikasi sistem.',
            'phone.numeric' => 'Nomor WhatsApp hanya boleh berisi angka.',
            'phone.digits_between' => 'Nomor WhatsApp tidak valid (10 - 15 digit).',
            'phone.unique' => 'Nomor WhatsApp ini sudah terdaftar.',
        ]);

        $otpCode = rand(100000, 999999);
        
        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone, // Masukkan data nomor telepon ke tabel users
            'password' => Hash::make($this->password),
            'role' => 'owner',
            'otp_code' => $otpCode,
            'otp_expires_at' => now()->addMinutes(5),
        ]);

        Mail::to($user->email)->send(new SendOtpMail($otpCode));
        
        // Login otomatis sebagai guest/unverified
        Auth::login($user);
        session()->put('pending_cafe_name', $this->cafe_name);
        
        return redirect()->route('verify-otp');
    }

    public function render()
    {
        return view('livewire.auth.register')->layout('layouts.app');
    }
}