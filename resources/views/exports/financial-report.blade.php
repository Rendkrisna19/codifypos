@if($isPdf)
<!DOCTYPE html>
<html>
<head>
    <title>Laporan Keuangan - {{ $tenantName }}</title>
    <style>
        body { font-family: 'Helvetica', Arial, sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 20px; color: #111; }
        .header p { margin: 5px 0; font-size: 12px; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #111111; color: #ffffff; font-weight: bold; font-size: 11px; text-transform: uppercase; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .profit { color: #059669; font-weight: bold; }
        .summary-table { margin-bottom: 20px; border: none; width: 100%; }
        .summary-table td { border: none; font-size: 12px; font-weight: bold; background-color: #f9f9f9; }
    </style>
</head>
<body>

    <div class="header">
        <h1>{{ $tenantName }}</h1>
        <p>Laporan Pendapatan & Keuntungan Bersih</p>
        <p>Dicetak pada: {{ now()->format('d M Y H:i') }}</p>
    </div>

    <table class="summary-table">
        <tr>
            <td>Total Pendapatan: Rp {{ number_format($summary['revenue'], 0, ',', '.') }}</td>
            <td>Total HPP (Modal): Rp {{ number_format($summary['cogs'], 0, ',', '.') }}</td>
            <td class="profit">Laba Bersih: Rp {{ number_format($summary['profit'], 0, ',', '.') }}</td>
            <td>Item Terjual: {{ $summary['items'] }}</td>
        </tr>
    </table>
@endif

<table>
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>No. Order</th>
            <th>Staff Kasir</th>
            <th>Produk</th>
            <th>Kategori</th>
            <th class="text-center">Qty</th>
            <th class="text-right">Harga Modal</th>
            <th class="text-right">Harga Jual</th>
            <th class="text-right">Laba Bersih</th>
        </tr>
    </thead>
    <tbody>
        @foreach($orderItems as $item)
            @php
                $profitPerItem = $item->unit_selling_price - $item->unit_cost_price;
                $totalProfit = $profitPerItem * $item->qty;
            @endphp
        <tr>
            <td>{{ $item->order->created_at->format('d/m/Y H:i') }}</td>
            <td>{{ $item->order->order_number }}</td>
            <td>{{ $item->order->cashier->name ?? '-' }}</td>
            <td>{{ $item->product->name ?? 'Produk Dihapus' }}</td>
            <td>{{ $item->product->category->name ?? '-' }}</td>
            <td class="text-center">{{ $item->qty }}</td>
            <td class="text-right">{{ number_format($item->unit_cost_price, 0, ',', '.') }}</td>
            <td class="text-right">{{ number_format($item->unit_selling_price, 0, ',', '.') }}</td>
            <td class="text-right">{{ number_format($totalProfit, 0, ',', '.') }}</td>
        </tr>
        @endforeach
        
        @if(!$isPdf)
        <tr>
            <td colspan="8" style="font-weight: bold; text-align: right;">TOTAL LABA BERSIH:</td>
            <td style="font-weight: bold; text-align: right;">{{ number_format($summary['profit'], 0, ',', '.') }}</td>
        </tr>
        @endif
    </tbody>
</table>

@if($isPdf)
</body>
</html>
@endif