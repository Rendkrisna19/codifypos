<div class="flex flex-col lg:flex-row gap-6 h-auto lg:h-[calc(100vh-8rem)] pb-10 lg:pb-0">
    
    <div class="w-full lg:w-2/3 flex flex-col h-[60vh] sm:h-[65vh] lg:h-full bg-transparent overflow-hidden">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 gap-3 sm:gap-0">
            <h2 class="text-xl md:text-2xl font-black text-[#111111] dark:text-white uppercase tracking-tighter">Input Pesanan</h2>
            <div class="relative w-full sm:w-64">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari menu..." class="w-full pl-10 pr-4 py-2.5 bg-white dark:bg-gray-800 border-2 border-gray-100 dark:border-gray-700 rounded-full focus:border-[#111111] dark:focus:border-white outline-none text-sm font-medium dark:text-white shadow-sm transition-all">
                <svg class="w-5 h-5 text-gray-400 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
        </div>

        <div class="flex gap-3 overflow-x-auto pb-4 mb-2 scrollbar-hide px-1">
            <div wire:click="setCategory(null)" class="flex flex-col items-center gap-2 cursor-pointer group min-w-[65px] md:min-w-[70px]">
                <div class="w-14 h-14 md:w-16 md:h-16 rounded-full flex items-center justify-center border-2 transition-all shadow-sm {{ $selectedCategory === null ? 'border-[#111111] bg-gray-100 dark:border-white dark:bg-gray-800' : 'border-transparent bg-white dark:bg-gray-800 group-hover:border-gray-300' }}">
                    <span class="text-lg md:text-xl">🍽️</span>
                </div>
                <span class="text-[10px] md:text-xs font-bold text-[#111111] dark:text-white uppercase tracking-widest">Semua</span>
            </div>
            
            @foreach($categories as $cat)
            <div wire:click="setCategory({{ $cat->id }})" class="flex flex-col items-center gap-2 cursor-pointer group min-w-[65px] md:min-w-[70px]">
                <div class="w-14 h-14 md:w-16 md:h-16 rounded-full flex items-center justify-center border-2 transition-all shadow-sm {{ $selectedCategory == $cat->id ? 'border-[#111111] bg-gray-100 dark:border-white dark:bg-gray-800' : 'border-transparent bg-white dark:bg-gray-800 group-hover:border-gray-300' }}">
                    <span class="text-lg md:text-xl font-black text-gray-400">{{ substr($cat->name, 0, 1) }}</span>
                </div>
                <span class="text-[10px] md:text-xs font-bold text-[#111111] dark:text-gray-300 text-center line-clamp-1 uppercase tracking-widest">{{ $cat->name }}</span>
            </div>
            @endforeach
        </div>

        <div class="flex-1 overflow-y-auto px-1 pb-4 custom-scrollbar">
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 md:gap-4">
                @foreach($products as $prod)
                <div class="bg-white dark:bg-gray-800 rounded-[1.5rem] p-3 md:p-4 flex flex-col items-center text-center shadow-sm border-2 border-gray-100 dark:border-gray-700 hover:border-[#111111] dark:hover:border-white transition-all group">
                    
                    <div class="w-16 h-16 md:w-20 md:h-20 lg:w-24 lg:h-24 rounded-full bg-gray-50 dark:bg-gray-900 mb-3 overflow-hidden flex items-center justify-center border-2 border-gray-100 dark:border-gray-700 p-2 group-hover:scale-105 transition-transform">
                        @if($prod->image) 
                            <img src="{{ asset('storage/' . $prod->image) }}" class="w-full h-full object-contain"> 
                        @else 
                            <span class="text-2xl md:text-3xl opacity-50">☕</span> 
                        @endif
                    </div>
                    
                    <h3 class="text-xs md:text-sm font-black text-[#111111] dark:text-white leading-tight mb-1 line-clamp-2 h-8 md:h-10">{{ $prod->name }}</h3>
                    <p class="text-xs md:text-sm font-bold text-gray-500 dark:text-gray-400 mb-3 md:mb-4">Rp {{ number_format($prod->selling_price, 0, ',', '.') }}</p>
                    
                    <button wire:click="addToCart({{ $prod->id }})" wire:loading.attr="disabled" 
                            class="w-full py-2 border-2 border-[#111111] dark:border-white text-[#111111] dark:text-white rounded-xl text-[10px] md:text-xs font-black uppercase tracking-widest hover:bg-[#111111] hover:text-white dark:hover:bg-white dark:hover:text-[#111111] transition-colors shadow-sm">
                        + Tambah
                    </button>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="w-full lg:w-1/3 flex flex-col h-auto min-h-[45vh] lg:h-full bg-white dark:bg-gray-800 rounded-[2rem] border-2 border-gray-100 dark:border-gray-700 shadow-xl overflow-hidden transition-colors relative">
        
        <div class="p-5 md:p-6 border-b-2 border-gray-50 dark:border-gray-700">
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-lg md:text-xl font-black text-[#111111] dark:text-white uppercase tracking-tighter">Pesanan Meja</h2>
                <button wire:click="clearCart" wire:confirm="Kosongkan semua pesanan?" class="text-[10px] md:text-xs font-black uppercase tracking-widest text-red-500 hover:text-red-700 bg-red-50 dark:bg-red-900/20 px-3 py-1.5 rounded-lg transition-colors">Batal</button>
            </div>
            
            <div class="mb-4">
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Nama / Nomor Meja <span class="text-red-500">*</span></label>
                <input type="text" wire:model="customerName" placeholder="Cth: Meja 4 / Budi" class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border-2 border-gray-100 dark:border-gray-700 rounded-xl text-sm font-bold text-[#111111] dark:text-white outline-none focus:border-[#111111] dark:focus:border-white transition-colors">
                @error('customerName') <span class="text-red-500 text-[10px] font-bold mt-1 block">{{ $message }}</span> @enderror
            </div>
            
            <div class="flex gap-2">
                <button wire:click="$set('orderType', 'dine_in')" class="flex-1 py-2.5 text-[10px] md:text-xs font-black uppercase tracking-widest rounded-xl border-2 transition-all {{ $orderType === 'dine_in' ? 'bg-[#111111] dark:bg-white text-white dark:text-[#111111] border-transparent shadow-md' : 'bg-transparent text-gray-500 border-gray-100 dark:border-gray-700 hover:border-gray-300' }}">Dine In</button>
                <button wire:click="$set('orderType', 'take_away')" class="flex-1 py-2.5 text-[10px] md:text-xs font-black uppercase tracking-widest rounded-xl border-2 transition-all {{ $orderType === 'take_away' ? 'bg-[#111111] dark:bg-white text-white dark:text-[#111111] border-transparent shadow-md' : 'bg-transparent text-gray-500 border-gray-100 dark:border-gray-700 hover:border-gray-300' }}">Take Away</button>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto p-5 md:p-6 space-y-5 custom-scrollbar">
            @if (session()->has('success'))
                <div class="p-3 bg-emerald-50 text-emerald-700 rounded-xl text-[10px] md:text-xs font-black uppercase tracking-widest text-center border-2 border-emerald-100">{{ session('success') }}</div>
            @endif
            
            @forelse($cart as $index => $item)
            <div class="flex items-center gap-3">
                <div class="flex-1">
                    <h4 class="text-sm font-black text-[#111111] dark:text-white line-clamp-1">{{ $item['name'] }}</h4>
                    <p class="text-[10px] md:text-xs font-bold text-gray-500 uppercase tracking-widest mt-0.5">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <div class="flex items-center bg-gray-50 dark:bg-gray-900 rounded-xl p-1 border-2 border-gray-100 dark:border-gray-700">
                        <button wire:click="decreaseQty({{ $index }})" class="w-7 h-7 flex items-center justify-center bg-white dark:bg-gray-800 rounded-lg text-[#111111] dark:text-white shadow-sm hover:bg-gray-200 transition-colors font-bold">-</button>
                        <span class="w-8 text-center text-xs font-black text-[#111111] dark:text-white">{{ $item['qty'] }}</span>
                        <button wire:click="increaseQty({{ $index }})" class="w-7 h-7 flex items-center justify-center bg-[#111111] dark:bg-white rounded-lg text-white dark:text-[#111111] shadow-sm hover:opacity-80 transition-opacity font-bold">+</button>
                    </div>
                </div>
            </div>
            @empty
            <div class="h-full flex flex-col items-center justify-center text-gray-400 space-y-3 opacity-50 py-10">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                <p class="text-[10px] font-black uppercase tracking-widest">Keranjang Kosong</p>
            </div>
            @endforelse
        </div>

        <div class="p-5 md:p-6 bg-gray-50 dark:bg-gray-900/50 border-t-2 border-gray-100 dark:border-gray-700 mt-auto">
            <div class="flex justify-between items-end mb-4">
                <span class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Total Estimasi</span> 
                <span class="text-xl md:text-2xl font-black text-[#111111] dark:text-white leading-none">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
            </div>
            <button wire:click="submitOrder" class="w-full py-4 bg-[#111111] dark:bg-white text-white dark:text-[#111111] text-xs font-black uppercase tracking-widest rounded-xl hover:opacity-80 transition-all shadow-xl hover:-translate-y-0.5 {{ empty($cart) ? 'opacity-50 cursor-not-allowed hover:translate-y-0' : '' }}">
                Kirim Ke Dapur & Kasir
            </button>
        </div>
    </div>

    <style>
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #e2e8f0; border-radius: 10px; }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #334155; }
    </style>
</div>