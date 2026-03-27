<?php

namespace App\Livewire\Admin;

use App\Models\Tenant;
use App\Models\Order; // Asumsi order langganan
use Carbon\Carbon;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        // Statistik Makro SaaS
        $totalTenants = Tenant::count();
        $activeTenants = Tenant::where('trial_until', '>', now())->count();
        $expiredTenants = Tenant::where('trial_until', '<=', now())->count();
        
        // Asumsi pendapatan dari langganan (Bukan transaksi cafe)
        $monthlyRevenue = 15000000; // Nanti hubungkan ke tabel payment/subscriptions
        
        // Data Grafik Pertumbuhan (7 Hari Terakhir)
        $chartLabels = [];
        $tenantGrowth = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $chartLabels[] = $date->format('d M');
            $tenantGrowth[] = Tenant::whereDate('created_at', $date)->count();
        }

        return view('livewire.admin.dashboard', [
            'totalTenants' => $totalTenants,
            'activeTenants' => $activeTenants,
            'expiredTenants' => $expiredTenants,
            'monthlyRevenue' => $monthlyRevenue,
            'chartLabels' => $chartLabels,
            'tenantGrowth' => $tenantGrowth,
        ])->layout('layouts.admin');
    }
}