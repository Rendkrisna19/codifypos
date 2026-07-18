<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\VerifyOtp;
use App\Livewire\Dashboard;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Pos;
use App\Livewire\ProductInventory;
use App\Livewire\StaffManagement;
use App\Livewire\FinancialReport;
use App\Livewire\PrinterManager;
use App\Livewire\PaymentProcess;
use App\Livewire\OutletSettings;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\TenantManager;
use App\Livewire\Admin\BillingPlans;
use App\Models\Package;
use App\Livewire\Admin\TransactionHistory;
use App\Livewire\Admin\BusinessAnalytics;
use App\Livewire\Admin\Helpdesk;



// 1. Landing Page
Route::get('/', function () {
    $packages = Package::orderBy('price', 'asc')->get();
    return view('welcome', compact('packages'));
});
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
    Route::get('/staff', StaffManagement::class)->name('staff');
    Route::get('/report', FinancialReport::class)->name('report');
    Route::get('/printer-manager', PrinterManager::class)->name('printer-manager');
    Route::get('/payment', PaymentProcess::class)->name('payment');
    Route::get('/outlet-settings', OutletSettings::class)->name('outlet-settings');
    Route::get('/berlangganan', App\Livewire\Owner\Subscription::class)->name('subscription');
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', AdminDashboard::class)->name('dashboard');
    Route::get('/tenants', TenantManager::class)->name('tenants');
    Route::get('/plans', BillingPlans::class)->name('plans');
    Route::get('/transactions', TransactionHistory::class)->name('transactions');
    Route::get('/analytics', BusinessAnalytics::class)->name('analytics');
    Route::get('/support', Helpdesk::class)->name('support');
});
