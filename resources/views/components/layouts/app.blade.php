<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
      x-data="{ theme: localStorage.getItem('theme') || 'light' }" 
      :class="theme">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'CodifyPOS - Dashboard' }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @livewireStyles
@vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        tailwind.config = {
            darkMode: 'class', // Wajib untuk toggle manual Dark Mode
            theme: { extend: { fontFamily: { sans: ['Poppins', 'sans-serif'] } } }
        }
    </script>
    <style> body { font-family: 'Poppins', sans-serif; } </style>
</head>
<body x-data="{ sidebarOpen: false, showWelcomeAlert: true }" 
      class="bg-gray-50 dark:bg-gray-900 text-[#111111] dark:text-gray-100 antialiased flex h-screen overflow-hidden transition-colors duration-300">

    <div x-show="sidebarOpen" 
         @click="sidebarOpen = false"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-900/80 z-40 md:hidden"></div>

    @include('components.sidebar')

    <div class="flex-1 flex flex-col overflow-hidden">
        
        @include('components.header')

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 dark:bg-gray-900 p-6 transition-colors duration-300">
            
            <div x-show="showWelcomeAlert" x-transition class="mb-6 bg-[#111111] dark:bg-black text-white rounded-xl p-4 shadow-lg flex items-center justify-between border border-gray-800">
                <div class="flex items-center gap-4">
                    <div class="bg-green-500/20 p-2 rounded-lg">
                        <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-lg">Selamat Datang di CodifyPOS!</h3>
                        <p class="text-gray-300 text-sm">Akun Anda berhasil diverifikasi. Nikmati akses penuh masa trial selama 14 hari ke depan.</p>
                    </div>
                </div>
                <button @click="showWelcomeAlert = false" class="text-gray-400 hover:text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            {{ $slot }}

        </main>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('trialCountdown', (targetDate) => ({
                timeString: 'Menghitung...',
                init() {
                    const target = new Date(targetDate).getTime();
                    setInterval(() => {
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

                        this.timeString = `${days}h ${hours}j ${minutes}m ${seconds}d`;
                    }, 1000);
                }
            }));
        });
    </script>
    @livewireScripts
</body>
</html>