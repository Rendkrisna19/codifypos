<div class="max-w-7xl mx-auto px-4 py-8 md:py-12 font-sans dark:text-white">
    
    <script src="{{ env('MIDTRANS_IS_PRODUCTION', false) ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}" data-navigate-track></script>

    @if(session()->has('error'))
        <div class="mb-8 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl flex items-center gap-3">
            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            <p class="font-bold text-red-700 dark:text-red-400">{{ session('error') }}</p>
        </div>
    @endif

    <div class="mb-12 bg-white dark:bg-[#111111] border-2 {{ $isExpired ? 'border-red-500 shadow-red-500/10' : 'border-[#111111] dark:border-gray-800 shadow-xl' }} rounded-[2rem] p-6 md:p-8 flex flex-col md:flex-row items-center justify-between gap-6 shadow-sm relative overflow-hidden">
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-slate-100 dark:bg-[#222222] rounded-full blur-3xl opacity-50 pointer-events-none"></div>

        <div class="relative z-10 w-full md:w-auto text-center md:text-left">
            <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Bisnis Terdaftar: {{ $tenantName }}</p>
            <h2 class="text-2xl md:text-3xl font-black uppercase tracking-tight text-[#111111] dark:text-white flex flex-col md:flex-row items-center md:justify-start gap-3">
                Paket Aktif: {{ $activePackageName }}
                @if($activePackageName === 'Free Trial 14 Hari')
                    <span class="px-3 py-1 bg-gray-100 text-[#111111] dark:bg-white dark:text-[#111111] text-[10px] rounded-full border border-gray-300 font-bold">TRIAL MODE</span>
                @endif
            </h2>
        </div>

        <div class="relative z-10 flex flex-col items-center md:items-end text-center md:text-right">
            @if($isExpired)
                <div class="px-6 py-3 bg-red-600 text-white rounded-2xl shadow-lg border border-red-700">
                    <p class="text-[10px] font-black uppercase tracking-widest opacity-80 mb-0.5">Masa Aktif Berakhir</p>
                    <p class="text-2xl font-black">EXPIRED</p>
                </div>
                <p class="text-xs font-bold text-red-500 mt-2">Sistem dikunci. Silakan perpanjang paket.</p>
            @else
                <div class="px-6 py-3 bg-[#111111] text-white dark:bg-white dark:text-[#111111] rounded-2xl shadow-lg border border-gray-800 dark:border-gray-200">
                    <p class="text-[10px] font-black uppercase tracking-widest opacity-80 mb-0.5">Sisa Masa Aktif</p>
                    <p class="text-2xl font-black">{{ $daysLeft }} Hari</p>
                </div>
                <p class="text-xs font-bold text-gray-500 mt-2">Sistem berjalan dengan lancar.</p>
            @endif
        </div>
    </div>

    <div class="text-center mb-12">
        <h2 class="text-3xl md:text-4xl font-black text-[#111111] dark:text-white uppercase tracking-tighter mb-4">Pilih Perpanjangan Paket</h2>
        <p class="text-gray-500 dark:text-gray-400 font-medium text-lg">Pilih durasi paket langganan. Pembayaran akan terverifikasi secara otomatis.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
        @foreach($packages as $index => $pkg)
            @php 
                $isPopular = ($packages->count() >= 3 && $index === 1) || ($packages->count() == 1); 
            @endphp

            <div class="flex flex-col rounded-3xl p-10 transition-all duration-300 relative border-2 shadow-sm 
                {{ $isPopular 
                    ? 'bg-[#111111] text-white border-[#111111] dark:bg-white dark:text-[#111111] dark:border-white scale-105 shadow-2xl z-10' 
                    : 'bg-white text-[#111111] border-gray-200 dark:bg-[#111111] dark:border-gray-800 dark:text-white hover:border-gray-400 dark:hover:border-gray-600' }}">
                
                @if($isPopular)
                    <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 bg-white text-[#111111] dark:bg-[#111111] dark:text-white border border-[#111111] dark:border-white text-[10px] font-black uppercase tracking-widest px-4 py-1.5 rounded-full shadow-md">
                        Paling Laris
                    </div>
                @endif

                <h3 class="text-xl font-black uppercase tracking-tight mb-2">{{ $pkg->name }}</h3>
                <p class="text-sm font-medium mb-8 {{ $isPopular ? 'opacity-70' : 'text-gray-500' }}">Akses sistem selama {{ $pkg->duration_days }} Hari</p>
                
                <div class="mb-10 flex items-baseline gap-1">
                    <span class="text-2xl font-bold">Rp</span>
                    <span class="text-5xl font-black tracking-tighter">{{ number_format($pkg->price, 0, ',', '.') }}</span>
                </div>
                
                <ul class="space-y-4 mb-10 text-sm font-medium flex-1 {{ $isPopular ? 'opacity-90' : 'text-gray-600 dark:text-gray-400' }}">
                    @foreach(explode("\n", str_replace("\r", "", $pkg->features)) as $feature)
                        @if(trim($feature) !== '')
                        <li class="flex items-start">
                            <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg> 
                            {{ $feature }}
                        </li>
                        @endif
                    @endforeach
                </ul>
                
                <button wire:click="checkout({{ $pkg->id }})" 
                        wire:loading.attr="disabled"
                        class="w-full py-4 rounded-xl font-black uppercase tracking-widest text-xs text-center transition-all flex justify-center items-center gap-2 border-2 
                        {{ $isPopular 
                            ? 'bg-white text-[#111111] border-white hover:bg-gray-200 dark:bg-[#111111] dark:text-white dark:border-[#111111] dark:hover:bg-gray-800' 
                            : 'bg-[#111111] text-white border-[#111111] hover:bg-gray-800 dark:bg-white dark:text-[#111111] dark:border-white dark:hover:bg-gray-200' }}">
                    <span wire:loading.remove wire:target="checkout({{ $pkg->id }})">Bayar Sekarang</span>
                    <span wire:loading wire:target="checkout({{ $pkg->id }})">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Memproses...
                    </span>
                </button>
            </div>
        @endforeach
    </div>

   @script
    <script>
        $wire.on('pay-with-snap', (event) => {
            
            // FIX: Mengambil token dengan sangat aman. 
            // Jika formatnya Object langsung ambil, jika Array cari di index 0.
            const token = event?.token ?? event[0]?.token;
            
            if(!token) {
                console.error("Data dari Livewire:", event);
                alert('Token pembayaran gagal dibuat. Silakan cek koneksi atau hubungi admin.');
                return;
            }

            window.snap.pay(token, {
                onSuccess: function(result){
                    alert('Pembayaran berhasil! Sistem sedang memproses perpanjangan paket.');
                    window.location.reload();
                },
                onPending: function(result){
                    alert('Silakan selesaikan pembayaran sesuai instruksi Midtrans.');
                },
                onError: function(result){
                    alert('Pembayaran gagal, ditolak, atau kedaluwarsa.');
                },
                onClose: function(){
                    console.log('Popup ditutup oleh user tanpa membayar.');
                }
            });
        });
    </script>
    @endscript

</div>