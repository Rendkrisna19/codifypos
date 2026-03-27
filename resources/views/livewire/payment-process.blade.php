<div x-data="{ printReceipt() { window.print(); } }">
    
    @if($receiptData)
    <style>
        /* Sembunyikan struk di layar monitor normal */
        #printable-receipt { display: none; }
        
        @media print {
            /* 1. Sembunyikan semua UI Kasir, Sidebar, dan Header */
            .no-print, aside, header { 
                display: none !important; 
            }

            /* 2. MATIKAN PAKSA LAYOUT h-screen & overflow-hidden DARI app.blade.php */
            html, body, main, .flex-1 {
                height: auto !important;
                min-height: auto !important;
                overflow: visible !important;
                display: block !important;
                background-color: white !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            /* 3. Tampilkan struk dengan format rapi di pojok kiri atas */
            #printable-receipt { 
                display: block !important; 
                position: relative !important;
                width: {{ $receiptData['paper_size'] ?? '58mm' }}; 
                margin: 0 !important; 
                padding: 0 !important; 
                color: black !important; 
                font-family: monospace; 
                font-size: 12px;
            }
        }
    </style>
    @endif

    <div class="mb-4 flex flex-col md:flex-row md:items-center justify-between gap-4 no-print">
        <h2 class="text-2xl font-black text-[#111111] dark:text-white">Pusat Pembayaran</h2>
        
        <div class="flex bg-gray-200 dark:bg-gray-800 p-1 rounded-xl">
            <button wire:click="$set('activeTab', 'unpaid')" class="px-6 py-2.5 rounded-lg text-sm font-bold transition-all {{ $activeTab === 'unpaid' ? 'bg-white dark:bg-[#111111] text-[#111111] dark:text-white shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-[#111111] dark:hover:text-white' }}">
                Antrean Bayar
            </button>
            <button wire:click="$set('activeTab', 'paid')" class="px-6 py-2.5 rounded-lg text-sm font-bold transition-all {{ $activeTab === 'paid' ? 'bg-white dark:bg-[#111111] text-[#111111] dark:text-white shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-[#111111] dark:hover:text-white' }}">
                Riwayat Lunas
            </button>
        </div>
    </div>

    @if($activeTab === 'unpaid')
    <div class="flex flex-col lg:flex-row gap-6 h-[calc(100vh-12rem)] no-print" wire:poll.5s>
        
        <div class="w-full lg:w-1/2 flex flex-col h-full bg-transparent overflow-hidden">
            <div class="relative mb-4">
                <input type="text" wire:model.live="search" placeholder="Cari Nama/Meja di antrean..." class="w-full pl-10 pr-4 py-2.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-full focus:ring-2 focus:ring-[#111111] outline-none text-sm dark:text-white shadow-sm">
                <svg class="w-4 h-4 text-gray-400 absolute left-4 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>

            <div class="flex-1 overflow-y-auto pr-2 space-y-3">
                @forelse($unpaidOrders as $order)
                <div wire:click="selectOrder({{ $order->id }})" class="p-4 bg-white dark:bg-gray-800 rounded-2xl border-2 cursor-pointer transition-all shadow-sm {{ $selectedOrder && $selectedOrder->id === $order->id ? 'border-[#111111] dark:border-white' : 'border-transparent hover:border-gray-300 dark:hover:border-gray-600' }}">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-black text-[#111111] dark:text-white">{{ $order->customer_name }}</h3>
                            <p class="text-xs font-bold text-gray-400">{{ $order->order_number }} • Pesan: {{ $order->created_at->format('H:i') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-black text-green-600 dark:text-green-400">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</p>
                            <p class="text-[10px] font-bold text-red-500 uppercase tracking-wide animate-pulse mt-1 bg-red-50 dark:bg-red-900/20 px-2 py-0.5 rounded">Belum Bayar</p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-20 text-gray-400">
                    <span class="text-4xl">☕</span>
                    <p class="text-sm font-bold mt-3">Antrean pembayaran kosong.</p>
                </div>
                @endforelse
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex flex-col h-full bg-white dark:bg-gray-800 rounded-3xl border border-gray-200 dark:border-gray-700 shadow-xl overflow-hidden transition-colors">
            @if($selectedOrder)
            <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-black text-[#111111] dark:text-white">Proses Bayar</h2>
                    <p class="text-sm font-bold text-gray-500">Pelanggan: <span class="text-[#111111] dark:text-white">{{ $selectedOrder->customer_name }}</span></p>
                </div>
                <button wire:click="$set('selectedOrder', null)" class="text-gray-400 hover:text-red-500"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            
            <div class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900/50">
                <div class="space-y-3 mb-6">
                    @foreach($selectedOrder->items as $item)
                    <div class="flex justify-between text-sm font-bold text-[#111111] dark:text-white border-b border-gray-200 dark:border-gray-800 pb-2">
                        <span>{{ $item->qty }}x {{ $item->product->name ?? 'Menu' }}</span>
                        <span>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                    </div>
                    @endforeach
                </div>

                <div class="space-y-1 mb-6 text-sm">
                    <div class="flex justify-between text-gray-500 font-bold"><span>Subtotal</span> <span class="text-[#111111] dark:text-white">Rp {{ number_format($selectedOrder->subtotal, 0, ',', '.') }}</span></div>
                    <div class="flex justify-between text-gray-500 font-bold"><span>PPN (11%)</span> <span class="text-[#111111] dark:text-white">Rp {{ number_format($selectedOrder->tax_amount, 0, ',', '.') }}</span></div>
                    <div class="flex justify-between text-2xl font-black text-[#111111] dark:text-white pt-3 mt-3 border-t border-gray-200 dark:border-gray-700">
                        <span>TOTAL</span> <span>Rp {{ number_format($selectedOrder->grand_total, 0, ',', '.') }}</span>
                    </div>
                </div>

                <label class="block text-xs font-bold text-gray-500 mb-2">Metode Pembayaran</label>
                <div class="grid grid-cols-3 gap-2 mb-6">
                    <button class="py-3 text-xs font-bold rounded-xl border bg-[#111111] text-white border-transparent">Tunai</button>
                    <button disabled class="relative py-3 text-xs font-bold rounded-xl border bg-gray-100 dark:bg-gray-800 text-gray-400 border-gray-200 dark:border-gray-700 cursor-not-allowed opacity-60">QRIS <span class="absolute -top-2 -right-2 bg-yellow-400 text-black text-[9px] px-1.5 py-0.5 rounded shadow-sm">Pro</span></button>
                    <button disabled class="relative py-3 text-xs font-bold rounded-xl border bg-gray-100 dark:bg-gray-800 text-gray-400 border-gray-200 dark:border-gray-700 cursor-not-allowed opacity-60">Transfer <span class="absolute -top-2 -right-2 bg-yellow-400 text-black text-[9px] px-1.5 py-0.5 rounded shadow-sm">Pro</span></button>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1">Uang Diterima Kasir</label>
                    <input type="number" wire:model.live.debounce.300ms="amountTendered" class="w-full text-right font-black text-2xl px-4 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-[#111111] outline-none text-[#111111] dark:text-white" placeholder="0">
                    @if($amountTendered && $change >= 0)
                    <div class="mt-2 text-right">
                        <span class="text-sm font-bold text-gray-500">Kembalian: </span>
                        <span class="text-xl font-black text-green-600 dark:text-green-400">Rp {{ number_format($change, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    @error('payment') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="p-6 bg-white dark:bg-gray-800 border-t border-gray-100 dark:border-gray-700">
                <button wire:click="processPayment" class="w-full py-4 bg-[#111111] dark:bg-white text-white dark:text-[#111111] text-lg font-black rounded-xl hover:opacity-80 transition-colors shadow-lg">
                    TERIMA PEMBAYARAN & CETAK
                </button>
            </div>
            @else
            <div class="h-full flex flex-col items-center justify-center text-gray-400 opacity-50">
                <span class="text-4xl mb-4">🧾</span>
                <p class="text-sm font-bold">Pilih antrean pesanan di sebelah kiri</p>
            </div>
            @endforelse
        </div>
    </div>
    @endif

    @if($activeTab === 'paid')
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden no-print">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari Nama Pelanggan atau No. Order..." class="w-full pl-10 pr-4 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-[#111111] outline-none text-sm dark:text-white">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <div class="w-full sm:w-48">
                <input type="date" wire:model.live="filterDate" class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg text-sm text-[#111111] dark:text-white outline-none">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                <thead class="bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 text-xs uppercase font-bold text-gray-500">
                    <tr>
                        <th class="px-4 py-3">Transaksi</th>
                        <th class="px-4 py-3">Pelanggan</th>
                        <th class="px-4 py-3">Kasir</th>
                        <th class="px-4 py-3 text-right">Total (Rp)</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($paidOrders as $order)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                        <td class="px-4 py-3">
                            <p class="font-bold text-[#111111] dark:text-white">{{ $order->order_number }}</p>
                            <p class="text-[10px] text-gray-400">{{ $order->created_at->format('d M Y H:i') }}</p>
                        </td>
                        <td class="px-4 py-3 font-bold text-[#111111] dark:text-white">{{ $order->customer_name }}</td>
                        <td class="px-4 py-3">{{ $order->cashier->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-right font-black text-green-600 dark:text-green-400">{{ number_format($order->grand_total, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="bg-green-100 text-green-800 text-[10px] px-2 py-1 rounded-full font-bold uppercase tracking-wide">LUNAS</span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <button wire:click="reprint({{ $order->id }})" class="px-3 py-1.5 bg-[#111111] dark:bg-white text-white dark:text-[#111111] rounded-lg text-xs font-bold hover:opacity-80 transition flex items-center gap-1 ml-auto">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                Cetak
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400 font-medium">Belum ada riwayat transaksi lunas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
            {{ $paidOrders->links() }}
        </div>
    </div>
    @endif

    @if($receiptData)
    <div class="fixed inset-0 z-[100] flex items-center justify-center no-print">
        <div class="fixed inset-0 bg-gray-900/80"></div>
        <div class="relative z-10 bg-white rounded-3xl shadow-xl w-full max-w-sm overflow-hidden text-[#111111] p-6 text-center">
            <h3 class="text-2xl font-black mb-1">{{ isset($receiptData['is_reprint']) ? 'CETAK ULANG' : 'LUNAS!' }}</h3>
            <p class="text-sm text-gray-500 mb-6">Struk a.n <span class="font-bold text-[#111111]">{{ $receiptData['customer'] }}</span> siap dicetak.</p>
            
            <button @click="printReceipt()" class="w-full py-4 bg-[#111111] text-white text-sm font-bold rounded-xl hover:bg-gray-800 flex justify-center items-center gap-2 mb-2 shadow-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak Struk
            </button>
            <button wire:click="$set('receiptData', null)" class="w-full py-3 bg-gray-100 text-gray-700 text-sm font-bold rounded-xl hover:bg-gray-200 transition-colors">
                Tutup
            </button>
            
            @if($receiptData['auto_print']) 
                <script> setTimeout(function() { window.print(); }, 500); </script> 
            @endif
        </div>
    </div>

    <div id="printable-receipt" class="bg-white">
        <div style="text-align: center; margin-bottom: 10px;">
            <h2 style="font-size: 16px; margin: 0; font-weight: bold;">{{ $receiptData['cafe_name'] }}</h2>
            @if($receiptData['header']) <p style="font-size: 10px; margin: 2px 0;">{{ $receiptData['header'] }}</p> @endif
            <p style="font-size: 10px; margin: 2px 0;">---------------------------------</p>
            @if(isset($receiptData['is_reprint'])) <p style="font-size: 10px; margin: 2px 0; font-weight:bold;">** COPY RECEIPT **</p> @endif
            <p style="font-size: 10px; margin: 2px 0; text-align: left;">Tgl: {{ $receiptData['date'] }} | Ksr: {{ $receiptData['cashier'] }}</p>
            <p style="font-size: 10px; margin: 2px 0; text-align: left;">No : {{ $receiptData['order_number'] }}</p>
            <p style="font-size: 10px; margin: 2px 0; text-align: left; font-weight: bold;">Pelanggan: {{ $receiptData['customer'] }}</p>
        </div>
        
        <div style="border-top: 1px dashed #000; border-bottom: 1px dashed #000; padding: 5px 0; margin-bottom: 5px;">
            @foreach($receiptData['items'] as $item)
            <div style="margin-bottom: 3px;">
                <div style="font-size: 11px;">{{ $item['name'] }}</div>
                <div style="display: flex; justify-content: space-between; font-size: 11px;">
                    <span>{{ $item['qty'] }}x @ {{ number_format($item['price'], 0, ',', '.') }}</span>
                    <span>{{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}</span>
                </div>
            </div>
            @endforeach
        </div>

        <div style="display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 2px;">
            <span>Subtotal:</span> <span>{{ number_format($receiptData['subtotal'], 0, ',', '.') }}</span>
        </div>
        <div style="display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 2px;">
            <span>PPN (11%):</span> <span>{{ number_format($receiptData['tax'], 0, ',', '.') }}</span>
        </div>
        <div style="display: flex; justify-content: space-between; font-size: 13px; font-weight: bold; margin-bottom: 5px;">
            <span>TOTAL:</span> <span>{{ number_format($receiptData['grand_total'], 0, ',', '.') }}</span>
        </div>

        <div style="border-top: 1px dashed #000; padding-top: 5px; font-size: 11px;">
            <div style="display: flex; justify-content: space-between;">
                <span>TUNAI:</span> <span>{{ number_format($receiptData['tendered'], 0, ',', '.') }}</span>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span>KEMBALI:</span> <span>{{ number_format($receiptData['change'], 0, ',', '.') }}</span>
            </div>
        </div>

        <div style="text-align: center; margin-top: 15px; font-size: 10px;">
            <p style="margin: 0;">{{ $receiptData['footer'] }}</p>
            <p style="margin: 0;">Powered by CodifyPOS.</p>
        </div>
    </div>
    @endif
</div>