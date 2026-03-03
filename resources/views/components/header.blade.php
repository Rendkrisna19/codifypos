<header class="bg-[#fcfcfc] dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 h-16 flex items-center justify-between px-6 sticky top-0 z-30 transition-colors duration-300">
    <div class="flex items-center gap-4">
        <button @click="sidebarOpen = true" class="md:hidden text-gray-500 dark:text-gray-400 hover:text-[#111111] dark:hover:text-white">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </button>
        <h2 class="text-lg font-semibold text-[#111111] dark:text-white hidden sm:block">
            {{ auth()->user()->tenant->name ?? 'CodifyPOS Tenant' }}
        </h2>
    </div>

    <div class="flex items-center gap-6">
        @if(auth()->user()->tenant && auth()->user()->tenant->trial_until)
        <div x-data="trialCountdown('{{ auth()->user()->tenant->trial_until->toIso8601String() }}')" class="hidden md:flex items-center gap-2 bg-gray-100 dark:bg-gray-800 px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-700 transition-colors duration-300">
            <svg class="w-4 h-4 text-red-500 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="text-xs font-medium text-gray-600 dark:text-gray-400">Sisa Trial:</span>
            <span class="text-sm font-bold text-[#111111] dark:text-white" x-text="timeString"></span>
        </div>
        @endif

        <div class="flex items-center gap-3">
            <div class="text-right hidden sm:block">
                <p class="text-sm font-semibold text-[#111111] dark:text-white">{{ auth()->user()->name }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 capitalize">{{ auth()->user()->role }}</p>
            </div>
            <div class="w-9 h-9 rounded-full bg-[#111111] dark:bg-white text-white dark:text-[#111111] flex items-center justify-center font-bold shadow-sm transition-colors duration-300">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>
        </div>
    </div>
</header>