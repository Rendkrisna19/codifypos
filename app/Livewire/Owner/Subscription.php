<?php

namespace App\Livewire\Owner;

use Livewire\Component;
use App\Models\Package;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Midtrans\Config;
use Midtrans\Snap;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')] // Pastikan layout ini sesuai dengan struktur folder Anda
class Subscription extends Component
{
    public function checkout($packageId)
    {
        $user = Auth::user();
        $tenant = $user->tenant;

        $package = Package::findOrFail($packageId);
        $orderId = 'INV-' . $tenant->id . '-' . time();

        // Konfigurasi Midtrans
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        
        // PENTING: Jika di localhost, pastikan .env Anda MIDTRANS_IS_PRODUCTION=false
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false); 
        
        Config::$isSanitized = env('MIDTRANS_IS_SANITIZED', true);
        Config::$is3ds = env('MIDTRANS_IS_3DS', true);

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $package->price,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone ?? '08123456789', // Midtrans butuh no HP
            ],
            'item_details' => [
                [
                    'id' => $package->id,
                    'price' => (int) $package->price,
                    'quantity' => 1,
                    'name' => 'Paket Berlangganan ' . $package->name
                ]
            ]
        ];

        try {
            // Minta Snap Token ke Midtrans
            $snapToken = Snap::getSnapToken($params);

            Transaction::create([
                'tenant_id' => $tenant->id,
                'package_id' => $package->id,
                'order_id' => $orderId,
                'amount' => $package->price,
                'snap_token' => $snapToken,
                'status' => 'pending'
            ]);

            // Dispatch event ke Javascript Frontend
            $this->dispatch('pay-with-snap', token: $snapToken);

        } catch (\Exception $e) {
            // Menangkap error jika API Key salah
            session()->flash('error', 'Gagal memproses Midtrans: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $tenant = Auth::user()->tenant;
        
        // Menghitung Sisa Hari
        $now = Carbon::now()->startOfDay();
        $expiryDate = Carbon::parse($tenant->trial_until)->startOfDay();
        $daysLeft = (int) $now->diffInDays($expiryDate, false);
        $isExpired = $daysLeft < 0;

        // Cek Paket Terakhir
        $latestTransaction = Transaction::with('package')
            ->where('tenant_id', $tenant->id)
            ->where('status', 'success')
            ->latest('paid_at')
            ->first();

        $activePackageName = $latestTransaction ? $latestTransaction->package->name : 'Free Trial 14 Hari';
        $packages = Package::orderBy('price', 'asc')->get();
        
        return view('livewire.owner.subscription', [
            'packages' => $packages,
            'daysLeft' => $daysLeft,
            'isExpired' => $isExpired,
            'activePackageName' => $activePackageName,
            'tenantName' => $tenant->name
        ]);
    }
}