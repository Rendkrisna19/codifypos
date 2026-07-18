<header class="h-20 bg-white/70 dark:bg-slate-900/70 backdrop-blur-xl border-b border-slate-200 dark:border-slate-800 flex items-center justify-between px-4 md:px-8 sticky top-0 z-30">
    
    <div class="flex items-center gap-4">
        <!-- Mobile Toggle Button -->
        <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/></svg>
        </button>

        <!-- Desktop Toggle Button -->
        <button @click="sidebarCollapsed = !sidebarCollapsed" class="hidden lg:block p-2 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>

        <div class="hidden sm:flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
            <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">System Online</span>
        </div>
    </div>

    <div class="flex items-center gap-3 md:gap-6">
        <!-- Theme Toggle (Opsional) -->
        <button @click="theme = (theme === 'light' ? 'dark' : 'light'); localStorage.setItem('theme', theme)" 
                class="p-2 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-full transition-all">
            <svg x-show="theme === 'dark'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 9H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            <svg x-show="theme === 'light'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
        </button>

        <!-- Profile Section -->
        <div class="flex items-center gap-3 pl-4 md:pl-6 border-l border-slate-200 dark:border-slate-800">
            <div class="text-right hidden md:block">
                <p class="text-xs font-bold text-slate-900 dark:text-white leading-none mb-1">Admin Pusat</p>
                <p class="text-[9px] text-slate-400 font-medium uppercase tracking-tighter">Chief Commander</p>
            </div>
            <div class="relative group">
                <div class="w-10 h-10 rounded-full border-2 border-slate-900 dark:border-white p-0.5 cursor-pointer overflow-hidden">
                    <div class="w-full h-full bg-slate-900 dark:bg-white rounded-full flex items-center justify-center text-[10px] text-white dark:text-slate-900 font-black">
                        HQ
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>