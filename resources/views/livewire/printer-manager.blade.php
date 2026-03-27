<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-[#111111] dark:text-white transition-colors">Manajer Printer & Struk</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Sesuaikan ukuran kertas dan format struk untuk cafe Anda.</p>
    </div>

    @if (session()->has('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="mb-6 p-4 bg-green-50 dark:bg-green-500/10 text-green-700 dark:text-green-400 rounded-xl border border-green-200 dark:border-green-500/20 text-sm font-bold flex items-center gap-2 shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-col lg:flex-row gap-8">
        
        <div class="w-full lg:w-1/2">
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-6 transition-colors">
                <form wire:submit.prevent="saveSettings" class="space-y-5">
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Nama Profil Printer</label>
                        <input type="text" wire:model.live="printerName" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg text-[#111111] dark:text-white focus:ring-2 focus:ring-[#111111] outline-none" placeholder="Misal: Printer Kasir Depan">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Ukuran Kertas Thermal</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="relative flex items-center justify-center p-3 border-2 rounded-xl cursor-pointer transition-colors {{ $paperSize === '58mm' ? 'border-[#111111] dark:border-white bg-gray-50 dark:bg-gray-700' : 'border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                                <input type="radio" wire:model.live="paperSize" value="58mm" class="sr-only">
                                <div class="text-center">
                                    <span class="block text-sm font-bold text-[#111111] dark:text-white">58 mm</span>
                                    <span class="block text-xs text-gray-500">Standar Bluetooth Mobile</span>
                                </div>
                            </label>
                            <label class="relative flex items-center justify-center p-3 border-2 rounded-xl cursor-pointer transition-colors {{ $paperSize === '80mm' ? 'border-[#111111] dark:border-white bg-gray-50 dark:bg-gray-700' : 'border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                                <input type="radio" wire:model.live="paperSize" value="80mm" class="sr-only">
                                <div class="text-center">
                                    <span class="block text-sm font-bold text-[#111111] dark:text-white">80 mm</span>
                                    <span class="block text-xs text-gray-500">Standar Desktop/USB</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Teks Tambahan (Header)</label>
                        <input type="text" wire:model.live="headerText" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg text-[#111111] dark:text-white focus:ring-2 focus:ring-[#111111] outline-none" placeholder="Misal: Cabang Sudirman, Promo 20%">
                        <p class="text-xs text-gray-500 mt-1">Akan dicetak di bawah nama Cafe.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Teks Penutup (Footer)</label>
                        <input type="text" wire:model.live="footerText" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg text-[#111111] dark:text-white focus:ring-2 focus:ring-[#111111] outline-none" placeholder="Terima Kasih Atas Kunjungan Anda">
                        <p class="text-xs text-gray-500 mt-1">Dicetak di bagian paling bawah struk.</p>
                    </div>

                    <div class="flex items-center pt-2">
                        <input type="checkbox" wire:model="autoPrint" id="autoPrint" class="w-5 h-5 text-[#111111] bg-gray-100 border-gray-300 rounded focus:ring-[#111111] cursor-pointer">
                        <label for="autoPrint" class="ml-3 text-sm font-bold text-gray-700 dark:text-gray-300 cursor-pointer">
                            Auto-Print
                            <span class="block text-xs text-gray-500 font-normal">Otomatis buka dialog print setelah pembayaran sukses.</span>
                        </label>
                    </div>

                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit" class="w-full py-3 bg-[#111111] dark:bg-white text-white dark:text-[#111111] rounded-xl font-bold hover:bg-gray-800 dark:hover:bg-gray-200 transition-colors shadow-md">
                            Simpan Pengaturan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex justify-center bg-gray-100 dark:bg-gray-900/50 p-6 rounded-2xl border border-gray-200 dark:border-gray-800">
            <div>
                <p class="text-center text-xs font-bold text-gray-500 uppercase tracking-widest mb-4">Live Preview Struk</p>
                
                <div class="bg-white text-black p-5 shadow-2xl mx-auto transition-all duration-300" 
                     style="width: {{ $paperSize === '58mm' ? '280px' : '380px' }}; font-family: monospace;">
                    
                    <div class="text-center mb-3">
                        <h2 class="text-xl font-bold mb-1">{{ auth()->user()->tenant->name ?? 'NAMA CAFE' }}</h2>
                        @if($headerText)
                            <p class="text-xs mb-1">{{ $headerText }}</p>
                        @endif
                        <p class="text-[10px] text-gray-600 border-b border-dashed border-gray-400 pb-2">10/Mar/2026 14:30 | Kasir: {{ auth()->user()->name }}</p>
                    </div>

                    <div class="border-b border-dashed border-gray-400 pb-2 mb-2 text-xs">
                        <div class="flex justify-between mb-1">
                            <span>Kopi Susu x2</span>
                            <span>40.000</span>
                        </div>
                        <div class="flex justify-between mb-1">
                            <span>Croissant x1</span>
                            <span>22.000</span>
                        </div>
                    </div>

                    <div class="text-xs mb-4">
                        <div class="flex justify-between font-bold text-sm">
                            <span>TOTAL:</span>
                            <span>62.000</span>
                        </div>
                    </div>

                    <div class="text-center text-[10px] text-gray-600 border-t border-dashed border-gray-400 pt-3">
                        <p>{{ $footerText ?: 'Terima Kasih' }}</p>
                        <p class="mt-1">Powered by CodifyPOS.</p>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>