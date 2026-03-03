<?php

namespace App\Livewire\Auth;

use App\Models\Tenant;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Support\Str;
use App\Mail\SendOtpMail;
use Illuminate\Support\Facades\Mail;

class VerifyOtp extends Component
{
    public $otp = '';

    // Fungsi ini sekarang hanya akan dipanggil oleh Alpine.js setiap 60 detik
    public function autoResend()
    {
        $user = Auth::user();
        
        // Pastikan user ada sebelum memproses
        if($user) {
            $newOtp = rand(100000, 999999);
            
            $user->update([
                'otp_code' => $newOtp,
                'otp_expires_at' => now()->addMinutes(5),
            ]);

            Mail::to($user->email)->send(new SendOtpMail($newOtp));
            
            $this->otp = ''; // Kosongkan inputan otomatis
        }
    }

    public function verify()
    {
        // Pastikan inputan bersih dari spasi tidak sengaja
        $this->otp = trim($this->otp);
        $this->validate(['otp' => 'required|numeric|digits:6']);
        
        $user = Auth::user();

        // Validasi utama
        if ($user->otp_code == $this->otp && $user->otp_expires_at >= now()) {
            $cafeName = session('pending_cafe_name', $user->name . ' Cafe');
            
            $tenant = Tenant::create([
                'name' => $cafeName,
                'slug' => Str::slug($cafeName) . '-' . uniqid(),
                'is_active' => true,
                'trial_until' => now()->addDays(14),
            ]);

            $user->update([
                'tenant_id' => $tenant->id,
                'email_verified_at' => now(),
                'otp_code' => null,
                'otp_expires_at' => null,
            ]);

            session()->forget('pending_cafe_name');
            return redirect()->route('dashboard');
        }

        $this->addError('otp', 'Kode OTP salah atau sudah kedaluwarsa.');
    }

    public function render()
    {
        // Pastikan layout targetnya benar sesuai file layout utama kamu
        return view('livewire.auth.verify-otp')->layout('components.layouts.app');
    }
}