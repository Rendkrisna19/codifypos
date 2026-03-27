<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CodifyPOS - Sistem Kasir Pintar Berbasis Cloud untuk F&B</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">

    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Space Grotesk', sans-serif; }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased selection:bg-slate-900 selection:text-white overflow-x-hidden">

    <nav class="fixed top-0 w-full z-50 bg-white/80 backdrop-blur-xl border-b border-slate-200 transition-all">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="text-2xl font-black tracking-tighter uppercase">
                Codify<span class="text-slate-400 font-light italic">POS</span>
            </div>
            
            <div class="hidden md:flex items-center gap-8 text-sm font-bold text-slate-500 uppercase tracking-widest">
                <a href="#fitur" class="hover:text-slate-900 transition-colors">Fitur</a>
                <a href="#cara-kerja" class="hover:text-slate-900 transition-colors">Cara Kerja</a>
                <a href="#harga" class="hover:text-slate-900 transition-colors">Harga</a>
            </div>

            <div class="flex items-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" wire:navigate class="text-xs font-black uppercase tracking-widest px-5 py-2.5 rounded-xl bg-slate-900 text-white hover:bg-slate-800 transition shadow-lg">Dashboard &rarr;</a>
                @else
                    <a href="{{ route('login') }}" wire:navigate class="hidden md:block text-xs font-black uppercase tracking-widest text-slate-500 hover:text-slate-900 transition">Login</a>
                    <a href="{{ route('register') }}" wire:navigate class="text-xs font-black uppercase tracking-widest px-5 py-2.5 rounded-xl bg-slate-900 text-white hover:bg-slate-800 transition shadow-lg hover:shadow-xl hover:-translate-y-0.5 transform duration-200">Daftar Gratis</a>
                @endauth
            </div>
        </div>
    </nav>

    <section class="max-w-7xl mx-auto px-6 pt-40 pb-24 flex flex-col items-center text-center relative">
        <div class="absolute top-20 left-10 w-72 h-72 bg-slate-200 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob"></div>
        <div class="absolute top-40 right-10 w-72 h-72 bg-slate-300 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob animation-delay-2000"></div>

        <div data-aos="fade-down" class="inline-flex items-center px-4 py-2 rounded-full border-2 border-slate-200 bg-white text-xs font-bold uppercase tracking-widest mb-8 shadow-sm relative z-10">
            <span class="flex h-2 w-2 rounded-full bg-emerald-500 mr-3 animate-pulse"></span>
            Sistem Kasir Cloud #1 Untuk Cafe & Resto
        </div>
        
        <h1 data-aos="fade-up" data-aos-delay="100" class="text-5xl md:text-7xl font-black tracking-tighter leading-[1.1] max-w-5xl mb-6 relative z-10 uppercase">
            Otomatisasi Bisnis F&B Anda <br class="hidden md:block"/> 
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-slate-900 to-slate-500">Tanpa Ribet.</span>
        </h1>
        
        <p data-aos="fade-up" data-aos-delay="200" class="text-slate-500 text-lg md:text-xl font-medium max-w-2xl mb-10 leading-relaxed relative z-10">
            Tinggalkan pencatatan manual. Pantau penjualan kasir, kelola stok bahan baku, dan analisa profit meja secara *real-time* langsung dari perangkat Anda.
        </p>
        
        <div data-aos="fade-up" data-aos-delay="300" class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto relative z-10">
            <a href="{{ route('register') }}" wire:navigate class="px-8 py-4 bg-slate-900 text-white rounded-2xl font-black uppercase tracking-widest hover:bg-slate-800 transition shadow-2xl hover:-translate-y-1 w-full sm:w-auto text-center text-sm">
                Mulai Trial 14 Hari
            </a>
            <a href="#fitur" class="px-8 py-4 bg-white border-2 border-slate-200 text-slate-900 rounded-2xl font-black uppercase tracking-widest hover:bg-slate-50 transition w-full sm:w-auto text-center shadow-sm text-sm">
                Jelajahi Fitur &darr;
            </a>
        </div>

        <div data-aos="zoom-in-up" data-aos-delay="400" class="mt-24 w-full max-w-5xl rounded-[2rem] border-[8px] border-white overflow-hidden shadow-2xl relative">
            <img src="{{ asset('images/banner.png') }}" alt="Dashboard Restoran CodifyPOS" class="w-full h-auto object-cover transform hover:scale-105 transition duration-1000 ease-in-out">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/40 to-transparent pointer-events-none"></div>
        </div>
    </section>

    <section id="fitur" class="bg-white py-24 border-y border-slate-200">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-20" data-aos="fade-up">
                <h2 class="text-4xl md:text-5xl font-black uppercase tracking-tighter mb-4 text-slate-900">Dirancang Untuk Kecepatan</h2>
                <p class="text-slate-500 font-medium max-w-2xl mx-auto text-lg">Solusi lengkap dari manajemen pesanan kasir, integrasi dapur, hingga laporan laba rugi bulanan.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div data-aos="fade-up" data-aos-delay="100" class="bg-slate-50 p-10 rounded-[2rem] border-2 border-slate-100 hover:border-slate-900 transition-colors duration-300 group">
                    <div class="w-14 h-14 bg-white border-2 border-slate-200 text-slate-900 rounded-2xl flex items-center justify-center mb-8 group-hover:bg-slate-900 group-hover:text-white transition-colors duration-300 shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h3 class="text-xl font-black uppercase tracking-tight mb-4 text-slate-900">Kasir Anti Lemot</h3>
                    <p class="text-slate-500 text-sm leading-relaxed font-medium">Proses pesanan (Dine-in/Take-away) dalam hitungan detik. Mendukung split bill, manajemen meja, dan cetak struk via Bluetooth.</p>
                </div>

                <div data-aos="fade-up" data-aos-delay="200" class="bg-slate-50 p-10 rounded-[2rem] border-2 border-slate-100 hover:border-slate-900 transition-colors duration-300 group">
                    <div class="w-14 h-14 bg-white border-2 border-slate-200 text-slate-900 rounded-2xl flex items-center justify-center mb-8 group-hover:bg-slate-900 group-hover:text-white transition-colors duration-300 shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-black uppercase tracking-tight mb-4 text-slate-900">Analitik Real-Time</h3>
                    <p class="text-slate-500 text-sm leading-relaxed font-medium">Pantau omzet harian, menu paling laris, dan jam paling sibuk langsung dari HP Anda layaknya seorang bos.</p>
                </div>

                <div data-aos="fade-up" data-aos-delay="300" class="bg-slate-50 p-10 rounded-[2rem] border-2 border-slate-100 hover:border-slate-900 transition-colors duration-300 group">
                    <div class="w-14 h-14 bg-white border-2 border-slate-200 text-slate-900 rounded-2xl flex items-center justify-center mb-8 group-hover:bg-slate-900 group-hover:text-white transition-colors duration-300 shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </div>
                    <h3 class="text-xl font-black uppercase tracking-tight mb-4 text-slate-900">Stok & Inventaris</h3>
                    <p class="text-slate-500 text-sm leading-relaxed font-medium">Sistem peringatan otomatis saat bahan baku menipis. Kelola resep dan HPP untuk memastikan margin keuntungan yang maksimal.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="harga" class="py-24 bg-slate-50">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-20" data-aos="fade-up">
                <h2 class="text-4xl md:text-5xl font-black uppercase tracking-tighter mb-4 text-slate-900">Investasi Masuk Akal</h2>
                <p class="text-slate-500 font-medium text-lg">Tanpa biaya tersembunyi. Tingkatkan skala bisnis F&B Anda hari ini.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
                
                @if(isset($packages) && $packages->count() > 0)
                    @foreach($packages as $index => $pkg)
                        @php
                            // Membuat paket di tengah (jika ada 3) menjadi "Recommended" dan berwarna gelap
                            $isPopular = ($packages->count() >= 3 && $index === 1) || ($packages->count() == 1); 
                        @endphp

                        <div data-aos="fade-up" data-aos-delay="{{ $index * 100 }}" 
                             class="flex flex-col rounded-[2rem] p-10 transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl relative
                             {{ $isPopular ? 'bg-slate-900 text-white shadow-xl scale-105 border border-slate-800' : 'bg-white text-slate-900 border-2 border-slate-100 shadow-sm' }}">
                            
                            @if($isPopular)
                                <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 bg-white text-slate-900 text-[10px] font-black uppercase tracking-widest px-4 py-1.5 rounded-full shadow-md">
                                    Paling Laris
                                </div>
                            @endif

                            <h3 class="text-xl font-black uppercase tracking-tight mb-2">{{ $pkg->name }}</h3>
                            <p class="text-sm font-medium mb-8 {{ $isPopular ? 'text-slate-400' : 'text-slate-500' }}">Akses sistem selama {{ $pkg->duration_days }} Hari</p>
                            
                            <div class="mb-10 flex items-baseline gap-1">
                                <span class="text-2xl font-bold">Rp</span>
                                <span class="text-5xl font-black tracking-tighter">{{ number_format($pkg->price, 0, ',', '.') }}</span>
                            </div>
                            
                            <ul class="space-y-4 mb-10 text-sm font-medium flex-1 {{ $isPopular ? 'text-slate-300' : 'text-slate-600' }}">
                                @foreach(explode("\n", str_replace("\r", "", $pkg->features)) as $feature)
                                    @if(trim($feature) !== '')
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 mr-3 shrink-0 {{ $isPopular ? 'text-white' : 'text-slate-900' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg> 
                                        {{ $feature }}
                                    </li>
                                    @endif
                                @endforeach
                            </ul>
                            
                            <a href="{{ route('register') }}" wire:navigate 
                               class="w-full py-4 rounded-xl font-black uppercase tracking-widest text-xs text-center transition-all 
                               {{ $isPopular ? 'bg-white text-slate-900 hover:bg-slate-200' : 'bg-slate-100 text-slate-900 hover:bg-slate-900 hover:text-white border border-slate-200' }}">
                                Pilih Paket Ini
                            </a>
                        </div>
                    @endforeach
                @else
                    <div class="col-span-full text-center p-12 bg-white rounded-3xl border-2 border-dashed border-slate-200">
                        <p class="text-slate-500 font-bold uppercase tracking-widest">Paket Berlangganan Sedang Diperbarui.</p>
                    </div>
                @endif
                
            </div>
        </div>
    </section>

    <footer class="bg-white border-t border-slate-200 pt-20 pb-10">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-16">
                <div class="col-span-1 md:col-span-2">
                    <div class="text-3xl font-black tracking-tighter uppercase mb-6 text-slate-900">
                        Codify<span class="text-slate-400 font-light italic">POS</span>
                    </div>
                    <p class="text-slate-500 text-sm max-w-sm leading-relaxed font-medium">
                        Platform Point of Sale modern yang dirancang khusus untuk mempercepat operasional dan menaikkan profit F&B di Indonesia.
                    </p>
                </div>
                <div>
                    <h4 class="font-black uppercase tracking-widest text-xs mb-6 text-slate-900">Produk</h4>
                    <ul class="space-y-4 text-sm font-medium text-slate-500">
                        <li><a href="#fitur" class="hover:text-slate-900 transition-colors">Fitur POS</a></li>
                        <li><a href="#harga" class="hover:text-slate-900 transition-colors">Pricing</a></li>
                        <li><a href="#" class="hover:text-slate-900 transition-colors">Sistem Kitchen</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-black uppercase tracking-widest text-xs mb-6 text-slate-900">Bantuan</h4>
                    <ul class="space-y-4 text-sm font-medium text-slate-500">
                        <li><a href="#" class="hover:text-slate-900 transition-colors">Pusat Bantuan</a></li>
                        <li><a href="#" class="hover:text-slate-900 transition-colors">Hubungi CS</a></li>
                        <li><a href="#" class="hover:text-slate-900 transition-colors">Syarat & Ketentuan</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-slate-100 pt-8 flex flex-col md:flex-row justify-between items-center text-xs font-bold uppercase tracking-widest text-slate-400">
                <p>&copy; {{ date('Y') }} CodifyPOS Indonesia.</p>
                <div class="mt-4 md:mt-0">Crafted with Precision</div>
            </div>
        </div>
    </footer>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            once: true, // Animasi hanya berjalan satu kali saat di-scroll
            duration: 800, // Durasi animasi
            offset: 100, // Jarak trigger animasi dari bawah layar
        });
    </script>
</body>
</html>