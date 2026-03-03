<div x-data="{ printReceipt() { window.print(); } }">
    
    <style>
        @media print {
            body * { visibility: hidden; }
            #printable-receipt, #printable-receipt * { visibility: visible; }
            #printable-receipt { 
                position: absolute; left: 0; top: 0; 
                width: 58mm; /* Ukuran Kertas Thermal Cafe Standar */
                padding: 0; margin: 0; color: #000; font-family: monospace; font-size: 12px;
            }
            .no-print { display: none !important; }
        }
    </style>

    <div class="flex flex-col lg:flex-row gap-6 h-[calc(100vh-8rem)] no-print">
        
        <div class="w-full lg:w-2/3 flex flex-col h-full bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden transition-colors">
            
            <div class="p-4 border-b border-gray-200 dark:border-gray-700 space-y-4 bg-white dark:bg-gray-800 z-10">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-[#111111] dark:text-white">{{ auth()->user()->tenant->name ?? 'Kasir' }}</h2>
                    <div class="relative w-64">
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari menu..." class="w-full pl-10 pr-4 py-2 bg-gray-100 dark:bg-gray-900 border border-transparent rounded-lg focus:ring-2 focus:ring-[#111111] dark:focus:ring-gray-600 outline-none text-sm dark:text-white transition-colors">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>
                
                <div class="flex gap-2 overflow-x-auto pb-2 scrollbar-hide">
                    <button wire:click="setCategory(null)" class="px-4 py-1.5 text-sm font-medium rounded-full whitespace-nowrap transition-colors {{ $selectedCategory === null ? 'bg-[#111111] dark:bg-white text-white dark:text-[#111111]' : 'bg-gray-100 dark:bg-gray-900 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700' }}">
                        Semua Menu
                    </button>
                    @foreach($categories as $cat)
                    <button wire:click="setCategory({{ $cat->id }})" class="px-4 py-1.5 text-sm font-medium rounded-full whitespace-nowrap transition-colors {{ $selectedCategory == $cat->id ? 'bg-[#111111] dark:bg-white text-white dark:text-[#111111]' : 'bg-gray-100 dark:bg-gray-900 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700' }}">
                        {{ $cat->name }}
                    </button>
                    @endforeach
                </div>
            </div>

            <div class="flex-1 overflow-y-auto p-4 relative z-0">
                @if($products->isEmpty())
                    <div class="h-full flex flex-col items-center justify-center text-gray-400">
                        <p class="text-sm font-medium">Menu tidak ditemukan atau belum ditambahkan.</p>
                    </div>
                @else
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($products as $prod)
                        <div wire:click="addToCart({{ $prod->id }})" class="cursor-pointer group bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden hover:border-[#111111] dark:hover:border-white hover:shadow-md transition-all">
                            <div class="h-32 bg-gray-200 dark:bg-gray-800 flex items-center justify-center overflow-hidden">
                                @if($prod->image)
                                    <img src="{{ asset('storage/' . $prod->image) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                                @else
                                    <span class="text-4xl">☕</span>
                                @endif
                            </div>
                            <div class="p-3">
                                <h3 class="text-sm font-bold text-[#111111] dark:text-white leading-tight mb-1 group-hover:text-blue-600 line-clamp-2">{{ $prod->name }}</h3>
                                <div class="flex justify-between items-center">
                                    <p class="text-sm font-bold text-green-600 dark:text-green-400">Rp {{ number_format($prod->selling_price, 0, ',', '.') }}</p>
                                    <p class="text-[10px] text-gray-400">Stok: {{ $prod->stock }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <div class="w-full lg:w-1/3 flex flex-col h-full bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden transition-colors">
            
            <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <h2 class="text-lg font-bold text-[#111111] dark:text-white">Pesanan Baru</h2>
                <button wire:click="clearCart" class="text-sm text-red-500 hover:text-red-700 font-medium">Kosongkan</button>
            </div>

            <div class="p-3 flex gap-2 border-b border-gray-100 dark:border-gray-800">
                <button wire:click="$set('orderType', 'dine_in')" class="flex-1 py-2 text-sm font-bold rounded-lg border transition-colors {{ $orderType === 'dine_in' ? 'bg-[#111111] dark:bg-white text-white dark:text-[#111111] border-transparent' : 'bg-transparent text-gray-500 border-gray-200 dark:border-gray-700' }}">Dine In</button>
                <button wire:click="$set('orderType', 'take_away')" class="flex-1 py-2 text-sm font-bold rounded-lg border transition-colors {{ $orderType === 'take_away' ? 'bg-[#111111] dark:bg-white text-white dark:text-[#111111] border-transparent' : 'bg-transparent text-gray-500 border-gray-200 dark:border-gray-700' }}">Take Away</button>
            </div>

            <div class="flex-1 overflow-y-auto p-4 space-y-4">
                @forelse($cart as $index => $item)
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <h4 class="text-sm font-bold text-[#111111] dark:text-white line-clamp-1">{{ $item['name'] }}</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="flex items-center bg-gray-100 dark:bg-gray-900 rounded-lg p-1">
                            <button wire:click="decreaseQty({{ $index }})" class="w-6 h-6 flex items-center justify-center bg-white dark:bg-gray-800 rounded text-[#111111] dark:text-white shadow-sm hover:bg-gray-200">-</button>
                            <span class="w-8 text-center text-sm font-bold text-[#111111] dark:text-white">{{ $item['qty'] }}</span>
                            <button wire:click="increaseQty({{ $index }})" class="w-6 h-6 flex items-center justify-center bg-white dark:bg-gray-800 rounded text-[#111111] dark:text-white shadow-sm hover:bg-gray-200">+</button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="h-full flex flex-col items-center justify-center text-gray-400 space-y-2">
                    <p class="text-sm font-medium">Belum ada pesanan</p>
                </div>
                @endforelse
            </div>

            <div class="p-4 bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800">
                
                <div class="grid grid-cols-3 gap-2 mb-4">
                    <button class="py-2 text-xs font-bold rounded-lg border bg-[#111111] text-white border-transparent">
                        Tunai (Cash)
                    </button>
                    <button disabled class="relative py-2 text-xs font-bold rounded-lg border bg-gray-100 dark:bg-gray-800 text-gray-400 border-gray-200 dark:border-gray-700 cursor-not-allowed opacity-70 group">
                        QRIS
                        <span class="absolute -top-2 -right-2 bg-yellow-400 text-black text-[9px] px-1.5 py-0.5 rounded shadow-sm flex items-center gap-1">
                            <svg class="w-2 h-2" fill="currentColor" viewBox="0 0 20 20"><path d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"></path></svg> Premium
                        </span>
                    </button>
                    <button disabled class="relative py-2 text-xs font-bold rounded-lg border bg-gray-100 dark:bg-gray-800 text-gray-400 border-gray-200 dark:border-gray-700 cursor-not-allowed opacity-70">
                        Transfer
                        <span class="absolute -top-2 -right-2 bg-yellow-400 text-black text-[9px] px-1.5 py-0.5 rounded shadow-sm flex items-center gap-1">
                            <svg class="w-2 h-2" fill="currentColor" viewBox="0 0 20 20"><path d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"></path></svg> Premium
                        </span>
                    </button>
                </div>

                <div class="space-y-1.5 mb-4 text-sm">
                    <div class="flex justify-between text-gray-500"><span>Subtotal</span> <span class="font-medium text-[#111111] dark:text-white">Rp {{ number_format($subtotal, 0, ',', '.') }}</span></div>
                    <div class="flex justify-between text-gray-500"><span>PPN (11%)</span> <span class="font-medium text-[#111111] dark:text-white">Rp {{ number_format($tax, 0, ',', '.') }}</span></div>
                    <div class="flex justify-between text-lg font-black text-[#111111] dark:text-white pt-2 border-t border-gray-200 dark:border-gray-700">
                        <span>Total Tagihan</span> <span>Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                    </div>
                </div>

                @if(!empty($cart))
                <div class="mb-4">
                    <input type="number" wire:model.live.debounce.300ms="amountTendered" class="w-full text-right font-bold text-lg px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#111111] outline-none text-[#111111] dark:text-white" placeholder="Uang diterima...">
                    @if($amountTendered && $change >= 0)
                    <div class="mt-2 p-2 bg-green-50 dark:bg-green-500/10 rounded-lg border border-green-200 text-center">
                        <span class="text-sm font-bold text-green-700 dark:text-green-400">Kembalian: Rp {{ number_format($change, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    @error('payment') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                @endif

                <button wire:click="processPayment" class="w-full py-4 bg-[#111111] dark:bg-white text-white dark:text-[#111111] text-lg font-black rounded-xl hover:bg-gray-800 dark:hover:bg-gray-200 transition-colors shadow-lg {{ empty($cart) ? 'opacity-50 cursor-not-allowed' : '' }}">
                    BAYAR & CETAK STRUK
                </button>
            </div>
        </div>
    </div>

    @if($receiptData)
    <div class="fixed inset-0 z-50 flex items-center justify-center no-print">
        <div class="fixed inset-0 bg-gray-900/80"></div>
        <div class="relative z-10 bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden text-[#111111]">
            <div class="p-6 text-center border-b border-gray-200 bg-green-50">
                <div class="w-12 h-12 bg-green-500 text-white rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-green-700">Transaksi Berhasil!</h3>
                <p class="text-xs text-green-600">Pesanan telah tersimpan ke sistem.</p>
            </div>
            
            <div class="p-6 flex flex-col gap-3">
                <button @click="printReceipt()" class="w-full py-3 bg-[#111111] text-white font-bold rounded-lg hover:bg-gray-800 flex justify-center items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Cetak Struk Thermal
                </button>
                <button wire:click="$set('receiptData', null)" class="w-full py-3 bg-gray-100 text-gray-700 font-bold rounded-lg hover:bg-gray-200">
                    Tutup & Lanjut Transaksi
                </button>
            </div>
        </div>
    </div>

    <div id="printable-receipt" class="bg-white text-black p-4 hidden">
        <div style="text-align: center; margin-bottom: 10px;">
            <h2 style="font-size: 16px; margin: 0;">{{ $receiptData['cafe_name'] }}</h2>
            <p style="font-size: 10px; margin: 2px 0;">Struk Pembelian ({{ $receiptData['order_type'] }})</p>
            <p style="font-size: 10px; margin: 2px 0;">{{ $receiptData['date'] }} | KSR: {{ $receiptData['cashier'] }}</p>
            <p style="font-size: 10px; margin: 2px 0;">No: {{ $receiptData['order_number'] }}</p>
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
            <span>Subtotal:</span>
            <span>{{ number_format($receiptData['subtotal'], 0, ',', '.') }}</span>
        </div>
        <div style="display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 2px;">
            <span>PPN (11%):</span>
            <span>{{ number_format($receiptData['tax'], 0, ',', '.') }}</span>
        </div>
        <div style="display: flex; justify-content: space-between; font-size: 12px; font-weight: bold; margin-bottom: 5px;">
            <span>TOTAL:</span>
            <span>{{ number_format($receiptData['grand_total'], 0, ',', '.') }}</span>
        </div>

        <div style="border-top: 1px dashed #000; padding-top: 5px; font-size: 11px;">
            <div style="display: flex; justify-content: space-between;">
                <span>TUNAI:</span>
                <span>{{ number_format($receiptData['tendered'], 0, ',', '.') }}</span>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span>KEMBALI:</span>
                <span>{{ number_format($receiptData['change'], 0, ',', '.') }}</span>
            </div>
        </div>

        <div style="text-align: center; margin-top: 15px; font-size: 10px;">
            <p style="margin: 0;">Terima Kasih Atas Kunjungan Anda</p>
            <p style="margin: 0;">Powered by CodifyPOS.</p>
        </div>
    </div>
    @endif
    
    <script>
        window.onbeforeprint = function() { document.getElementById('printable-receipt').style.display = 'block'; };
        window.onafterprint = function() { document.getElementById('printable-receipt').style.display = 'none'; };
    </script>
</div>