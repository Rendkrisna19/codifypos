<div>
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#111111] dark:text-white transition-colors">Laporan Keuangan</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Analisis penjualan, HPP, dan margin laba bersih.</p>
        </div>
        <div class="flex items-center gap-2">
            <button wire:click="exportExcel" class="px-4 py-2 bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400 border border-green-200 dark:border-green-500/20 rounded-lg text-sm font-bold flex items-center gap-2 hover:bg-green-100 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path></svg>
                Export Excel
            </button>
            <button wire:click="exportPdf" class="px-4 py-2 bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-400 border border-red-200 dark:border-red-500/20 rounded-lg text-sm font-bold flex items-center gap-2 hover:bg-red-100 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                Export PDF
            </button>
        </div>
    </div>

    @if (session()->has('info'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="mb-4 p-3 bg-blue-50 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400 rounded-lg border border-blue-200 text-sm font-medium">
            {{ session('info') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 p-5 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
            <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Total Pendapatan</p>
            <h3 class="text-2xl font-black text-[#111111] dark:text-white">Rp {{ number_format($summary['revenue'], 0, ',', '.') }}</h3>
        </div>
        <div class="bg-white dark:bg-gray-800 p-5 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
            <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Total HPP (Modal)</p>
            <h3 class="text-2xl font-black text-red-500 dark:text-red-400">Rp {{ number_format($summary['cogs'], 0, ',', '.') }}</h3>
        </div>
        <div class="bg-[#111111] dark:bg-gray-100 p-5 rounded-xl border border-gray-800 dark:border-gray-200 shadow-lg relative overflow-hidden">
            <div class="absolute right-0 top-0 w-16 h-16 bg-green-500/20 rounded-bl-full -mr-4 -mt-4"></div>
            <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wide mb-1">Keuntungan Bersih</p>
            <h3 class="text-2xl font-black text-green-400 dark:text-green-600">Rp {{ number_format($summary['profit'], 0, ',', '.') }}</h3>
        </div>
        <div class="bg-white dark:bg-gray-800 p-5 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
            <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Produk Terjual</p>
            <h3 class="text-2xl font-black text-[#111111] dark:text-white">{{ $summary['items'] }} <span class="text-sm font-medium text-gray-400">Item</span></h3>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 p-4 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm mb-6 flex flex-wrap gap-3 items-end">
        <div class="w-full md:w-auto flex-1 min-w-[200px]">
            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1">Cari Produk / No. Order</label>
            <input type="text" wire:model.live.debounce.500ms="search" class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg text-sm text-[#111111] dark:text-white outline-none focus:ring-2 focus:ring-[#111111]">
        </div>
        <div class="w-full md:w-auto">
            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1">Mulai Tanggal</label>
            <input type="date" wire:model.live="startDate" class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg text-sm text-[#111111] dark:text-white outline-none">
        </div>
        <div class="w-full md:w-auto">
            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1">Sampai Tanggal</label>
            <input type="date" wire:model.live="endDate" class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg text-sm text-[#111111] dark:text-white outline-none">
        </div>
        <div class="w-full md:w-auto">
            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1">Kategori</label>
            <select wire:model.live="categoryId" class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg text-sm text-[#111111] dark:text-white outline-none">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat) <option value="{{ $cat->id }}">{{ $cat->name }}</option> @endforeach
            </select>
        </div>
        <div class="w-full md:w-auto">
            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1">Staff Kasir</label>
            <select wire:model.live="staffId" class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg text-sm text-[#111111] dark:text-white outline-none">
                <option value="">Semua Staff</option>
                @foreach($staffs as $stf) <option value="{{ $stf->id }}">{{ $stf->name }}</option> @endforeach
            </select>
        </div>
        <button wire:click="resetFilters" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-lg text-sm font-bold hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
            Reset
        </button>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden w-full">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                <thead class="bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 text-xs uppercase font-bold text-gray-500">
                    <tr>
                        <th class="px-4 py-3">Waktu Transaksi</th>
                        <th class="px-4 py-3">Staff (Input)</th>
                        <th class="px-4 py-3">Produk / Kategori</th>
                        <th class="px-4 py-3 text-center">Qty</th>
                        <th class="px-4 py-3 text-right">Harga Modal</th>
                        <th class="px-4 py-3 text-right">Harga Jual</th>
                        <th class="px-4 py-3 text-right font-black text-green-600 dark:text-green-400">Keuntungan Bersih</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($orderItems as $item)
                        @php
                            $profitPerItem = $item->unit_selling_price - $item->unit_cost_price;
                            $totalProfit = $profitPerItem * $item->qty;
                        @endphp
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                        <td class="px-4 py-3">
                            <p class="font-bold text-[#111111] dark:text-white">{{ $item->order->created_at->format('d M Y') }}</p>
                            <p class="text-xs text-gray-400">{{ $item->order->created_at->format('H:i') }} | {{ $item->order->order_number }}</p>
                        </td>
                        <td class="px-4 py-3 font-medium text-[#111111] dark:text-white">
                            {{ $item->order->cashier->name ?? 'Unknown' }}
                        </td>
                        <td class="px-4 py-3">
                            <p class="font-bold text-[#111111] dark:text-white">{{ $item->product->name ?? 'Produk Dihapus' }}</p>
                            <p class="text-xs text-gray-400">{{ $item->product->category->name ?? '-' }}</p>
                        </td>
                        <td class="px-4 py-3 text-center font-bold text-[#111111] dark:text-white">{{ $item->qty }}</td>
                        <td class="px-4 py-3 text-right text-red-500">Rp {{ number_format($item->unit_cost_price, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right font-medium text-[#111111] dark:text-white">Rp {{ number_format($item->unit_selling_price, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right font-black text-green-600 dark:text-green-400 bg-green-50/50 dark:bg-green-900/10">
                            Rp {{ number_format($totalProfit, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-4 py-8 text-center text-gray-400 font-medium">Belum ada data transaksi yang sesuai filter.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
            {{ $orderItems->links() }}
        </div>
    </div>
</div>