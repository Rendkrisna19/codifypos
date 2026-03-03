<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\VerifyOtp;
use App\Livewire\Dashboard;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Pos; 
use App\Livewire\ProductInventory;

// 1. Landing Page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// 2. Akses untuk Guest (Belum Login)
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
});

Route::post('/logout', function (\Illuminate\Http\Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/verify-otp', VerifyOtp::class)->name('verify-otp');
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/pos', Pos::class)->name('pos');
    Route::get('/inventory', ProductInventory::class)->name('inventory');
});