<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi CodifyPOS</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 12px; color: #111; margin: 0; padding: 0; }
        .header { text-align: center; padding-bottom: 20px; border-bottom: 2px solid #111; margin-bottom: 20px; }
        .logo { font-size: 28px; font-weight: bold; margin: 0; letter-spacing: -1px; text-transform: uppercase; }
        .logo-sub { color: #666; font-weight: normal; font-style: italic; }
        .subtitle { font-size: 11px; color: #666; margin-top: 5px; text-transform: uppercase; letter-spacing: 1px; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #111; color: #fff; text-align: left; padding: 12px 10px; font-size: 10px; text-transform: uppercase; letter-spacing: 1px; }
        td { padding: 10px; border-bottom: 1px solid #ddd; font-size: 11px; vertical-align: middle; }
        
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        
        .status-success { color: #059669; font-weight: bold; }
        .status-pending { color: #d97706; font-weight: bold; }
        .status-failed { color: #dc2626; font-weight: bold; }
        
        .total-row td { font-weight: bold; font-size: 12px; background-color: #f8f9fa; border-top: 2px solid #111; border-bottom: 2px solid #111; }
        
        .footer { position: fixed; bottom: -30px; left: 0; right: 0; font-size: 9px; color: #999; text-align: center; border-top: 1px solid #eee; padding-top: 10px; }
    </style>
</head>
<body>

    <div class="header">
        <h1 class="logo">Codify<span class="logo-sub">Admin</span></h1>
        <p class="subtitle">Rekapitulasi Transaksi Pembayaran Sistem</p>
        <p style="font-size: 10px; color: #666; margin-top: 5px;">Dicetak pada: {{ now()->format('d F Y, H:i') }} | Filter: {{ $filterText }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Waktu</th>
                <th>Order ID</th>
                <th>Tenant (Bisnis)</th>
                <th>Paket</th>
                <th class="text-right">Nominal</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @php $totalSukses = 0; @endphp
            @foreach($transactions as $trx)
                @if($trx->status === 'success') 
                    @php $totalSukses += $trx->amount; @endphp 
                @endif
                <tr>
                    <td>{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $trx->order_id }}</td>
                    <td>{{ $trx->tenant->name ?? 'Dihapus' }}</td>
                    <td>{{ $trx->package->name ?? 'Dihapus' }}</td>
                    <td class="text-right">Rp {{ number_format($trx->amount, 0, ',', '.') }}</td>
                    <td class="text-center">
                        @if($trx->status === 'success') <span class="status-success">SUKSES</span>
                        @elseif($trx->status === 'pending') <span class="status-pending">PENDING</span>
                        @else <span class="status-failed">GAGAL</span>
                        @endif
                    </td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="4" class="text-right">TOTAL PENDAPATAN (TRANSAKSI SUKSES SAJA):</td>
                <td class="text-right">Rp {{ number_format($totalSukses, 0, ',', '.') }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        Dokumen ini di-generate secara otomatis oleh sistem CodifyPOS. Dokumen ini sah dan tidak memerlukan tanda tangan basah.
    </div>

</body>
</html>