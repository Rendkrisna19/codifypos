<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
      x-data="{ theme: localStorage.getItem('theme') || 'light' }" 
      :class="theme"
      class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'CodifyPOS - Dashboard' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/login.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Space+Grotesk:wght@300..700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        // Mengatur Outfit sebagai font sans utama
                        sans: ['"Outfit"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        'codify-black': '#111111',
                        'codify-white': '#fcfcfc',
                    }
                }
            }
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
        
        body { 
            font-family: 'Outfit', sans-serif;
            letter-spacing: normal; 
        }

        /* Smooth Scrollbar untuk Dark Mode */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        .dark ::-webkit-scrollbar-thumb { background: #374151; border-radius: 10px; }
        ::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #111111; }
    </style>
</head>
<body x-data="{ sidebarOpen: false, showWelcomeAlert: true }" 
      class="bg-codify-white dark:bg-gray-950 text-codify-black dark:text-gray-100 antialiased flex h-screen overflow-hidden transition-colors duration-500 font-sans">

    <div x-show="sidebarOpen" 
         x-cloak
         @click="sidebarOpen = false"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-codify-black/60 backdrop-blur-sm z-40 md:hidden"></div>

    @include('components.sidebar')

    <div class="flex-1 flex flex-col overflow-hidden">
        
        @include('components.header')

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50/50 dark:bg-gray-900/50 p-4 md:p-8 transition-colors duration-300">
            
            

            <div class="max-w-7xl mx-auto">
                {{ $slot }}
            </div>

        </main>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('trialCountdown', (targetDate) => ({
                timeString: 'Menghitung...',
                init() {
                    const target = new Date(targetDate).getTime();
                    const updateCountdown = () => {
                        const now = new Date().getTime();
                        const distance = target - now;

                        if (distance < 0) {
                            this.timeString = "Trial Habis";
                            return;
                        }
                        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                        this.timeString = `${days}d ${hours}h ${minutes}m ${seconds}s`;
                    };
                    updateCountdown();
                    setInterval(updateCountdown, 1000);
                }
            }));
        });
    </script>

    @livewireScripts
</body>
</html>