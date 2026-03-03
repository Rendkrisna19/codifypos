<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CodifyPOS - Sistem Kasir Pintar</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-[#fcfcfc] text-[#111111] antialiased selection:bg-[#111111] selection:text-white">

    <nav class="max-w-7xl mx-auto px-6 py-6 flex justify-between items-center">
        <div class="text-2xl font-bold tracking-tighter">
            CodifyPOS<span class="text-gray-400">.</span>
        </div>
        <div>
            @auth
                <a href="{{ route('dashboard') }}" wire:navigate class="text-sm font-semibold hover:text-gray-600 transition">Ke Dashboard &rarr;</a>
            @else
                <a href="{{ route('login') }}" wire:navigate class="text-sm font-medium px-5 py-2.5 rounded-full border border-gray-200 hover:border-[#111111] transition">Masuk / Daftar</a>
            @endauth
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-6 pt-20 pb-32 flex flex-col items-center text-center">
        <div class="inline-flex items-center px-3 py-1 rounded-full border border-gray-200 bg-white text-xs font-medium mb-8">
            <span class="flex h-2 w-2 rounded-full bg-green-500 mr-2"></span>
            Sistem POS berbasis Cloud untuk Cafe & Resto
        </div>
        
        <h1 class="text-5xl md:text-7xl font-bold tracking-tight leading-tight max-w-4xl mb-6">
            Kelola Cafe Anda Lebih <br/> <span class="text-transparent bg-clip-text bg-gradient-to-r from-gray-800 to-black">Cepat & Profesional.</span>
        </h1>
        
        <p class="text-gray-500 text-lg md:text-xl max-w-2xl mb-10">
            Satu platform untuk kelola pesanan, pantau stok, dan analisa margin laba. Tanpa install aplikasi, bisa diakses dari perangkat apa saja.
        </p>
        
        <div class="flex flex-col sm:flex-row gap-4">
            <a href="{{ route('login') }}" wire:navigate class="px-8 py-4 bg-[#111111] text-white rounded-lg font-medium hover:bg-gray-800 transition shadow-lg shadow-gray-200">
                Mulai Gratis 14 Hari
            </a>
            <a href="#fitur" class="px-8 py-4 bg-white border border-gray-200 text-[#111111] rounded-lg font-medium hover:bg-gray-50 transition">
                Pelajari Fitur
            </a>
        </div>

        <div class="mt-20 w-full max-w-5xl h-64 md:h-96 bg-gray-100 rounded-2xl border border-gray-200 overflow-hidden flex items-center justify-center relative shadow-inner">
            <div class="absolute inset-0 bg-gradient-to-t from-gray-50 to-transparent z-10"></div>
            <p class="text-gray-400 font-medium z-0">[ Gambar/Mockup Dashboard CodifyPOS di sini ]</p>
        </div>
    </main>

</body>
</html>