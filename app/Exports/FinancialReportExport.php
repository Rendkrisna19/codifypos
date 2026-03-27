<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class FinancialReportExport implements FromView, ShouldAutoSize, WithStyles
{
    protected $orderItems;
    protected $summary;
    protected $tenantName;

    public function __construct($orderItems, $summary, $tenantName)
    {
        $this->orderItems = $orderItems;
        $this->summary = $summary;
        $this->tenantName = $tenantName;
    }

    public function view(): View
    {
        return view('exports.financial-report', [
            'orderItems' => $this->orderItems,
            'summary' => $this->summary,
            'tenantName' => $this->tenantName,
            'isPdf' => false // Penanda untuk menyembunyikan style khusus PDF di Excel
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 16]], // Judul Utama
        ];
    }
}