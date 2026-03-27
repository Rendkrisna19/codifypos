<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Tenant;
use App\Models\Transaction;
use App\Models\Package;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.admin')]
class BusinessAnalytics extends Component
{
    public function render()
    {
        // 1. KPI (Key Performance Indicators) Utama
        $totalRevenue = Transaction::where('status', 'success')->sum('amount');
        $totalTenants = Tenant::count();
        $activeTenants = Tenant::where('is_active', 1)->where('trial_until', '>=', now())->count();
        $expiredTenants = $totalTenants - $activeTenants;

        // 2. Data Grafik Pendapatan 6 Bulan Terakhir
        $sixMonthsAgo = now()->subMonths(5)->startOfMonth();
        $revenueData = Transaction::where('status', 'success')
            ->where('paid_at', '>=', $sixMonthsAgo)
            ->select(
                DB::raw('SUM(amount) as total'),
                DB::raw('DATE_FORMAT(paid_at, "%b %Y") as month_name'),
                DB::raw('MONTH(paid_at) as month'),
                DB::raw('YEAR(paid_at) as year')
            )
            ->groupBy('year', 'month', 'month_name')
            ->orderBy('year', 'ASC')
            ->orderBy('month', 'ASC')
            ->get();

        // Siapkan Array untuk ApexCharts
        $chartMonths = [];
        $chartRevenues = [];
        foreach ($revenueData as $data) {
            $chartMonths[] = $data->month_name;
            $chartRevenues[] = (int) $data->total;
        }

        // 3. Data Distribusi Paket (Pie Chart)
        $packages = Package::withCount(['transactions' => function ($query) {
            $query->where('status', 'success');
        }])->get();

        $packageNames = [];
        $packageCounts = [];
        foreach ($packages as $pkg) {
            $packageNames[] = $pkg->name;
            $packageCounts[] = $pkg->transactions_count;
        }

        return view('livewire.admin.business-analytics', [
            'totalRevenue' => $totalRevenue,
            'totalTenants' => $totalTenants,
            'activeTenants' => $activeTenants,
            'expiredTenants' => $expiredTenants,
            'chartMonths' => json_encode($chartMonths),
            'chartRevenues' => json_encode($chartRevenues),
            'packageNames' => json_encode($packageNames),
            'packageCounts' => json_encode($packageCounts),
        ]);
    }
}