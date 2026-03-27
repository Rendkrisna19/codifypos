<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TransactionsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $search, $statusFilter, $dateFrom, $dateTo;

    // Menangkap filter dari Livewire
    public function __construct($search, $statusFilter, $dateFrom, $dateTo)
    {
        $this->search = $search;
        $this->statusFilter = $statusFilter;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
    }

    public function query()
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

    public function headings(): array
    {
        return ['Tanggal & Waktu', 'Order ID', 'Nama Bisnis (Tenant)', 'Paket Dibeli', 'Metode Pembayaran', 'Status', 'Nominal (Rp)'];
    }

    public function map($trx): array
    {
        return [
            $trx->created_at->format('d/m/Y H:i'),
            $trx->order_id,
            $trx->tenant->name ?? 'Tenant Dihapus',
            $trx->package->name ?? 'Paket Dihapus',
            strtoupper(str_replace('_', ' ', $trx->payment_type ?? '-')),
            strtoupper($trx->status),
            $trx->amount
        ];
    }

    // Styling Header Row Excel
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => ['fillType' => 'solid', 'color' => ['argb' => 'FF111111']]
            ],
        ];
    }
}