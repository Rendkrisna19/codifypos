@php
    $user = auth()->user();
    // Cek apakah masa trial sudah habis
    $isExpired = $user->tenant && $user->tenant->trial_until && now()->greaterThan($user->tenant->trial_until);
@endphp

<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-[#111111] transition-transform duration-300 ease-in-out md:translate-x-0 md:static md:inset-0 shadow-xl md:shadow-none flex flex-col border-r border-gray-200 dark:border-gray-800 font-sans">
    
    <div class="h-16 flex items-center justify-between px-6 border-b border-gray-200 dark:border-gray-800">
        <h1 class="text-2xl font-bold tracking-tight text-[#111111] dark:text-white">CodifyPOS<span class="text-gray-400">.</span></h1>
        <button @click="sidebarOpen = false" class="md:hidden text-gray-500 hover:text-[#111111] dark:text-gray-400 dark:hover:text-white transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>

    <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-2">
        
        @if(!$isExpired)
            <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-[#111111] text-white dark:bg-white dark:text-[#111111] shadow-md' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-[#222222] hover:text-[#111111] dark:hover:text-white' }}">
                <svg class="w-5 h-5 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Dashboard
            </a>

            <a href="{{ route('pos') }}" wire:navigate class="flex items-center gap-3 px-4 py-3 mt-6 rounded-xl font-medium transition-all duration-200 border border-gray-200 dark:border-gray-800 {{ request()->routeIs('pos') ? 'bg-[#111111] text-white dark:bg-white dark:text-[#111111] shadow-md border-transparent' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-[#222222] hover:text-[#111111] dark:hover:text-white shadow-sm' }}">
                <svg class="w-5 h-5 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                Input Pesanan (POS)
            </a>

            <a href="{{ route('payment') }}" wire:navigate class="flex items-center gap-3 px-4 py-3 mt-2 mb-4 rounded-xl font-medium transition-all duration-200 border border-gray-200 dark:border-gray-800 {{ request()->routeIs('payment') ? 'bg-[#111111] text-white dark:bg-white dark:text-[#111111] shadow-md border-transparent' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-[#222222] hover:text-[#111111] dark:hover:text-white shadow-sm' }}">
                <svg class="w-5 h-5 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                Pembayaran Kasir
            </a>

            @if($user->role === 'owner')
                <div class="pt-5 pb-2 pl-2">
                    <p class="text-xs font-bold text-gray-400 dark:text-gray-600 uppercase tracking-widest">Manajemen</p>
                </div>
                <a href="{{ route('inventory') }}" wire:navigate class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all duration-200 {{ request()->routeIs('inventory') ? 'bg-[#111111] text-white dark:bg-white dark:text-[#111111] shadow-md' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-[#222222] hover:text-[#111111] dark:hover:text-white' }}">
                    <svg class="w-5 h-5 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    Produk & Inventori
                </a>
                <a href="{{ route('staff') }}" wire:navigate class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all duration-200 {{ request()->routeIs('staff') ? 'bg-[#111111] text-white dark:bg-white dark:text-[#111111] shadow-md' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-[#222222] hover:text-[#111111] dark:hover:text-white' }}">
                    <svg class="w-5 h-5 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    Staff & Pengguna
                </a>
                <a href="{{ route('report') }}" wire:navigate class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all duration-200 {{ request()->routeIs('report') ? 'bg-[#111111] text-white dark:bg-white dark:text-[#111111] shadow-md' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-[#222222] hover:text-[#111111] dark:hover:text-white' }}">
                    <svg class="w-5 h-5 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    Laporan Keuangan
                </a>

                <div class="pt-5 pb-2 pl-2">
                    <p class="text-xs font-bold text-gray-400 dark:text-gray-600 uppercase tracking-widest">Pengaturan</p>
                </div>
                <a href="{{ route('printer-manager') }}" wire:navigate class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all duration-200 {{ request()->routeIs('printer-manager') ? 'bg-[#111111] text-white dark:bg-white dark:text-[#111111] shadow-md' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-[#222222] hover:text-[#111111] dark:hover:text-white' }}">
                    <svg class="w-5 h-5 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Manajer Printer
                </a>
                <a href="{{ route('outlet-settings') }}" wire:navigate class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/5 hover:text-[#111111] dark:hover:text-white font-medium transition-colors">
                    <svg class="w-5 h-5 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Pengaturan Outlet
                </a>
            @endif
        @else
            <div class="px-4 py-3 mb-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/30 rounded-xl">
                <p class="text-xs font-bold text-red-600 dark:text-red-400 leading-relaxed">
                    Masa trial berakhir. Semua akses operasional dikunci.
                </p>
            </div>
        @endif

        @if($user->role === 'owner')
            <div class="pt-5 pb-2 pl-2">
                <p class="text-xs font-bold text-gray-400 dark:text-gray-600 uppercase tracking-widest">Billing</p>
            </div>
            <a href="{{ route('subscription') }}" wire:navigate class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all duration-200 {{ request()->routeIs('subscription') ? 'bg-gray-900 text-white shadow-lg shadow-gray-500/30' : 'text-gray-900 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-600/20' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                Paket Berlangganan
            </a>
        @endif
        
    </nav>

    <div class="p-4 border-t border-gray-200 dark:border-gray-800 flex flex-col items-center space-y-4">
        
        <button @click="theme = theme === 'light' ? 'dark' : 'light'; localStorage.setItem('theme', theme)" 
                class="w-12 h-12 flex items-center justify-center bg-gray-100 dark:bg-[#222222] hover:bg-gray-200 dark:hover:bg-[#333333] text-[#111111] dark:text-white rounded-full transition-all duration-300 shadow-sm">
            <svg x-show="theme === 'light'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
            <svg x-show="theme === 'dark'" x-cloak style="display: none;" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        </button>

        <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gray-100 dark:bg-[#222222] hover:bg-gray-200 dark:hover:bg-[#333333] text-[#111111] dark:text-white rounded-xl transition-all duration-200 font-medium">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                Keluar
            </button>
        </form>
    </div>
</aside>