<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public $email = '';
    public $password = '';
    public $remember = false;

    public function login()
    {
        $credentials = $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $this->remember)) {
            session()->regenerate();

            $user = Auth::user();

            // 1. JALUR KHUSUS SUPERADMIN
            if ($user->role === 'superadmin') {
                return redirect()->intended(route('admin.dashboard'));
            }

            // 2. CEK TENANT ID (KHUSUS OWNER/STAFF BARU)
            if ($user->tenant_id === null) {
                return redirect()->route('verify-otp');
            }

            // 3. CEK STATUS SUSPEND / BLOKIR (SATPAM BLOKIR)
            if ($user->tenant && !$user->tenant->is_active) {
                Auth::logout();
                session()->invalidate();
                session()->regenerateToken();

                $this->addError('email', 'Akses Ditolak! Akun bisnis Anda sedang ditangguhkan. Silakan hubungi Admin Pusat CodifyPOS.');
                return; 
            }

            // 4. CEK MASA TRIAL (MODIFIKASI BARU)
            // Jika masa trial habis, biarkan login, TAPI arahkan paksa ke halaman berlangganan
            if ($user->tenant && $user->tenant->trial_until && now()->greaterThan($user->tenant->trial_until)) {
                // Kita simpan pesan flash untuk dimunculkan di halaman berlangganan
                session()->flash('error', 'Masa aktif CodifyPOS Anda telah habis. Silakan pilih paket perpanjangan.');
                
                // Pastikan Anda membuat route 'subscription' (Halaman Pembayaran Owner) setelah ini
                return redirect()->route('subscription'); 
            }

            // 5. REDIRECT BERDASARKAN ROLE (JIKA AMAN)
            if ($user->role === 'staff') {
                return redirect()->intended(route('pos'));
            }

            return redirect()->intended(route('dashboard'));
        }

        $this->addError('email', 'Email atau password yang Anda masukkan salah.');
    }

    public function render()
    {
        return view('livewire.auth.login')->layout('layouts.guest');
    }
}