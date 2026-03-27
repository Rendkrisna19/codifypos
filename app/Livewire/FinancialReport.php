<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\FinancialReportExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class FinancialReport extends Component
{
    use WithPagination;

    public $search = '';
    public $startDate = '';
    public $endDate = '';
    public $categoryId = '';
    public $staffId = '';

    public function updating($property)
    {
        if (in_array($property, ['search', 'startDate', 'endDate', 'categoryId', 'staffId'])) {
            $this->resetPage();
        }
    }

    public function resetFilters()
    {
        $this->reset(['search', 'startDate', 'endDate', 'categoryId', 'staffId']);
        $this->resetPage();
    }

    // Fungsi Utama Pembentuk Query Berdasarkan Filter
    private function getFilteredQuery()
    {
        $query = OrderItem::with(['order.cashier', 'product.category'])
            ->whereHas('order', function ($q) {
                $q->where('tenant_id', auth()->user()->tenant_id);
            });

        if ($this->startDate) {
            $query->whereHas('order', fn($q) => $q->whereDate('created_at', '>=', $this->startDate));
        }
        if ($this->endDate) {
            $query->whereHas('order', fn($q) => $q->whereDate('created_at', '<=', $this->endDate));
        }
        if ($this->staffId) {
            $query->whereHas('order', fn($q) => $q->where('user_id', $this->staffId));
        }
        if ($this->categoryId) {
            $query->whereHas('product', fn($q) => $q->where('category_id', $this->categoryId));
        }
        if ($this->search) {
            $query->where(function ($q) {
                $q->whereHas('order', fn($q2) => $q2->where('order_number', 'like', '%' . $this->search . '%'))
                  ->orWhereHas('product', fn($q2) => $q2->where('name', 'like', '%' . $this->search . '%'));
            });
        }

        return $query;
    }

    private function getSummaryData($query)
    {
        return [
            'revenue' => (clone $query)->sum(DB::raw('qty * unit_selling_price')),
            'cogs' => (clone $query)->sum(DB::raw('qty * unit_cost_price')),
            'profit' => (clone $query)->sum(DB::raw('qty * unit_selling_price')) - (clone $query)->sum(DB::raw('qty * unit_cost_price')),
            'items' => (clone $query)->sum('qty')
        ];
    }

    // FITUR EXPORT EXCEL
    public function exportExcel()
    {
        $query = $this->getFilteredQuery();
        $summary = $this->getSummaryData($query);
        $tenantName = auth()->user()->tenant->name ?? 'CodifyPOS_Tenant';
        
        $fileName = 'Laporan_Keuangan_' . Str::slug($tenantName, '_') . '_' . date('d-M-Y') . '.xlsx';

        return Excel::download(new FinancialReportExport($query->get(), $summary, $tenantName), $fileName);
    }

    // FITUR EXPORT PDF
    public function exportPdf()
    {
        $query = $this->getFilteredQuery();
        $summary = $this->getSummaryData($query);
        $tenantName = auth()->user()->tenant->name ?? 'CodifyPOS_Tenant';

        $fileName = 'Laporan_Keuangan_' . Str::slug($tenantName, '_') . '_' . date('d-M-Y') . '.pdf';

        // Render PDF menggunakan file view exports/financial-report.blade.php
        $pdf = Pdf::loadView('exports.financial-report', [
            'orderItems' => $query->get(),
            'summary' => $summary,
            'tenantName' => $tenantName,
            'isPdf' => true // Penanda untuk memunculkan CSS
        ])->setPaper('a4', 'landscape'); // Format landscape agar tabel lega

        // Melakukan force download PDF
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, $fileName);
    }

    public function render()
    {
        $query = $this->getFilteredQuery();
        $summary = $this->getSummaryData($query);

        return view('livewire.financial-report', [
            'orderItems' => $query->latest()->paginate(10),
            'categories' => Category::all(),
            'staffs' => User::where('tenant_id', auth()->user()->tenant_id)->get(),
            'summary' => $summary
        ])->layout('components.layouts.app');
    }
}