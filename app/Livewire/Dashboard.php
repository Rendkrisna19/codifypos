<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $tenantId = auth()->user()->tenant_id;
        $today = Carbon::today();

        // 1. DATA KARTU RINGKASAN
        $todayOrders = Order::where('tenant_id', $tenantId)
            ->where('payment_status', 'paid')
            ->whereDate('created_at', $today);

        $totalSales = $todayOrders->sum('grand_total');
        $totalOrdersCount = $todayOrders->count();

        // Hitung Margin Laba Bersih
        $todayOrderIds = $todayOrders->pluck('id');
        $margin = OrderItem::whereIn('order_id', $todayOrderIds)
            ->get()
            ->sum(function($item) {
                return ($item->unit_selling_price - $item->unit_cost_price) * $item->qty;
            });

        // 2. DATA GRAFIK PENJUALAN
        $chartLabels = [];
        $chartData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $chartLabels[] = $date->translatedFormat('l'); 

            $dailySales = Order::where('tenant_id', $tenantId)
                ->where('payment_status', 'paid')
                ->whereDate('created_at', $date)
                ->sum('grand_total');

            $chartData[] = $dailySales;
        }

        return view('livewire.dashboard', [
            'totalSales' => $totalSales,
            'totalOrders' => $totalOrdersCount,
            'margin' => $margin,
            'chartLabels' => $chartLabels,
            'chartData' => $chartData
        ])->layout('components.layouts.app');
    }
}