<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Transaction;
use Carbon\Carbon;
use App\Exports\TransactionsExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

#[Layout('layouts.admin')]
class TransactionHistory extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = 'all';
    public $dateFrom = '';
    public $dateTo = '';

    public function updatingSearch() { $this->resetPage(); }
    public function updatingStatusFilter() { $this->resetPage(); }
    public function updatingDateFrom() { $this->resetPage(); }
    public function updatingDateTo() { $this->resetPage(); }

    public function resetFilters()
    {
        $this->reset(['search', 'statusFilter', 'dateFrom', 'dateTo']);
        $this->resetPage();
    }

    // Kumpulan logika Query untuk mengambil data yang terfilter
    private function getFilteredQuery()
    {
        $query = Transaction::with(['tenant', 'package'])->latest();

        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('order_id', 'like', '%' . $this->search . '%')
                  ->orWhereHas('tenant', function($qTenant) {
                      $qTenant->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }
        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }
        if (!empty($this->dateFrom)) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }
        if (!empty($this->dateTo)) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }

        return $query;
    }

    // FITUR EXPORT EXCEL
    public function exportExcel()
    {
        $fileName = 'Laporan_Transaksi_CodifyPOS_' . now()->format('d_m_Y') . '.xlsx';
        
        // Memanggil class Export yang tadi kita buat, sambil melempar data filternya
        return Excel::download(
            new TransactionsExport($this->search, $this->statusFilter, $this->dateFrom, $this->dateTo), 
            $fileName
        );
    }

    // FITUR CETAK PDF
    public function exportPdf()
    {
        // 1. Ambil data transaksi yang sedang difilter (Tanpa pagination, ambil semua)
        $transactions = $this->getFilteredQuery()->get();

        // 2. Buat teks info filter untuk header PDF
        $filterText = 'Semua Data';
        if ($this->dateFrom || $this->dateTo) {
            $filterText = 'Periode ' . ($this->dateFrom ?: 'Awal') . ' s/d ' . ($this->dateTo ?: 'Sekarang');
        }

        // 3. Render file blade pdf yang kita buat tadi menjadi file PDF
        $pdf = Pdf::loadView('pdf.transactions', [
            'transactions' => $transactions,
            'filterText' => $filterText
        ]);

        // 4. Unduh filenya!
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'Laporan_Transaksi_CodifyPOS_' . now()->format('d_m_Y') . '.pdf');
    }

    public function render()
    {
        $query = $this->getFilteredQuery();
        
        // Ambil Data Ter-Filter & Pagination untuk UI Web
        $transactions = $query->paginate(15);

        // Kalkulasi Statistik Makro (Hanya berdasarkan filter saat ini)
        // Kita clone() agar kondisi filter tidak hilang/berubah saat mengambil count/sum
        $totalRevenue = (clone $query)->where('status', 'success')->sum('amount');
        $successCount = (clone $query)->where('status', 'success')->count();
        $pendingCount = (clone $query)->where('status', 'pending')->count();

        return view('livewire.admin.transaction-history', [
            'transactions' => $transactions,
            'totalRevenue' => $totalRevenue,
            'successCount' => $successCount,
            'pendingCount' => $pendingCount,
        ]);
    }
}