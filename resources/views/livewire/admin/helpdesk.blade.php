<div x-data="{ mobileChatOpen: false }" 
     x-effect="mobileChatOpen = $wire.activeTenantId !== null"
     class="flex h-[calc(100vh-6rem)] md:h-[calc(100vh-8rem)] bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl overflow-hidden shadow-xl shadow-slate-200/50 dark:shadow-none font-sans relative">
    
    <div :class="mobileChatOpen ? 'hidden md:flex' : 'flex'" 
         class="w-full md:w-1/3 lg:w-1/4 flex-col border-r border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/80 transition-all z-10">
        
        <div class="p-5 border-b border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 sticky top-0 z-10">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tighter">Kotak Masuk</h2>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-0.5">Pusat Bantuan Klien</p>
                </div>
                @php $totalUnread = $tenants->sum('unread_count'); @endphp
                @if($totalUnread > 0)
                    <span class="w-8 h-8 rounded-full bg-slate-900 text-white dark:bg-white dark:text-slate-900 flex items-center justify-center text-xs font-black shadow-md">
                        {{ $totalUnread }}
                    </span>
                @endif
            </div>
        </div>

        <div class="flex-1 overflow-y-auto custom-scrollbar bg-slate-50 dark:bg-[#111111]/50">
            @forelse($tenants as $tenant)
                @php 
                    $isActive = $activeTenantId == $tenant->id;
                    $lastMessage = $tenant->helpdeskMessages->first();
                @endphp
                <div wire:click="selectTenant({{ $tenant->id }}, '{{ addslashes($tenant->name) }}')" 
                     @click="mobileChatOpen = true"
                     class="p-5 border-b border-slate-100 dark:border-slate-800/50 cursor-pointer transition-all duration-200 relative group
                     {{ $isActive ? 'bg-white dark:bg-slate-800 shadow-sm border-l-4 border-l-slate-900 dark:border-l-white' : 'hover:bg-white/60 dark:hover:bg-slate-800/50 border-l-4 border-l-transparent' }}">
                    
                    <div class="flex justify-between items-start mb-1.5">
                        <h3 class="font-black text-sm truncate pr-2 tracking-tight {{ $isActive ? 'text-slate-900 dark:text-white' : 'text-slate-700 dark:text-slate-300' }}">
                            {{ $tenant->name }}
                        </h3>
                        @if($tenant->unread_count > 0)
                            <span class="px-2 py-0.5 bg-red-500 text-white text-[9px] font-black rounded-full shadow-sm animate-pulse">
                                {{ $tenant->unread_count }} Baru
                            </span>
                        @elseif($lastMessage)
                            <span class="text-[9px] font-bold text-slate-400 uppercase">
                                {{ $lastMessage->created_at->shortAbsoluteDiffForHumans() }}
                            </span>
                        @endif
                    </div>
                    
                    <p class="text-xs truncate font-medium {{ $isActive ? 'text-slate-600 dark:text-slate-400' : 'text-slate-500 dark:text-slate-500' }}">
                        @if($lastMessage)
                            @if($lastMessage->user_id == auth()->id())
                                <span class="opacity-60 text-[10px] uppercase tracking-widest mr-1">Anda:</span> 
                            @endif
                            {{ $lastMessage->message }}
                        @else
                            <i class="opacity-50">Belum ada obrolan...</i>
                        @endif
                    </p>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center p-8 text-center h-40">
                    <svg class="w-10 h-10 text-slate-300 dark:text-slate-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Inbox Kosong</span>
                </div>
            @endforelse
        </div>
    </div>

    <div :class="mobileChatOpen ? 'flex' : 'hidden md:flex'" 
         class="flex-col w-full md:w-2/3 lg:w-3/4 bg-white dark:bg-slate-900 relative">
        
        @if($activeTenantId)
            
            <div class="px-4 py-4 md:p-5 border-b border-slate-200 dark:border-slate-800 bg-white/90 dark:bg-slate-900/90 backdrop-blur-sm flex justify-between items-center z-20 shadow-sm absolute top-0 w-full">
                <div class="flex items-center gap-4">
                    <button @click="$wire.set('activeTenantId', null); mobileChatOpen = false" class="md:hidden p-2 -ml-2 text-slate-500 hover:text-slate-900 dark:hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                    </button>

                    <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center font-black text-slate-900 dark:text-white shadow-sm">
                        {{ substr($activeTenantName, 0, 1) }}
                    </div>
                    <div>
                        <h2 class="text-base md:text-lg font-black text-slate-900 dark:text-white uppercase tracking-tighter leading-none">{{ $activeTenantName }}</h2>
                        <div class="flex items-center gap-1.5 mt-1">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 relative flex"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span></span>
                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Koneksi Aman</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto p-4 md:p-6 space-y-6 bg-slate-50/50 dark:bg-[#0a0a0a] custom-scrollbar pt-24 pb-24"
                 x-data="{ 
                    initScroll() {
                        const box = $refs.chatBox;
                        box.scrollTop = box.scrollHeight;
                        // MutationObserver: Murni mendeteksi setiap ada elemen baru (pesan baru) masuk ke dalam chat box
                        new MutationObserver(() => {
                            box.scrollTo({ top: box.scrollHeight, behavior: 'smooth' });
                        }).observe(box, { childList: true, subtree: true });
                    }
                 }" 
                 x-init="initScroll()"
                 x-ref="chatBox"
                 wire:poll.keep-alive.5s="loadMessages"> 

                <div class="text-center pb-4">
                    <span class="px-3 py-1 bg-slate-200 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-[10px] font-bold uppercase tracking-widest rounded-full">
                        Enkripsi End-to-End Diaktifkan
                    </span>
                </div>

                @forelse($messages as $msg)
                    @php $isAdmin = $msg->user_id == auth()->id(); @endphp
                    
                    <div class="flex flex-col {{ $isAdmin ? 'items-end' : 'items-start' }} group">
                        <div class="flex items-baseline gap-2 mb-1 px-1">
                            @if(!$isAdmin) <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Klien</span> @endif
                            <span class="text-[9px] font-bold text-slate-400">{{ $msg->created_at->format('H:i') }}</span>
                            @if($isAdmin) <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Anda</span> @endif
                        </div>
                        
                        <div class="max-w-[85%] md:max-w-[70%] px-5 py-3.5 text-sm font-medium shadow-sm leading-relaxed
                            {{ $isAdmin 
                                ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900 rounded-2xl rounded-tr-sm' 
                                : 'bg-white text-slate-800 dark:bg-slate-800 dark:text-slate-200 border border-slate-200 dark:border-slate-700 rounded-2xl rounded-tl-sm' }}">
                            {!! nl2br(e($msg->message)) !!}
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col justify-center items-center h-full opacity-50">
                        <svg class="w-16 h-16 text-slate-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-widest text-center">
                            Mulai bantu {{ $activeTenantName }}
                        </p>
                    </div>
                @endforelse
            </div>

            <div class="absolute bottom-0 w-full p-4 border-t border-slate-200 dark:border-slate-800 bg-white/90 dark:bg-slate-900/90 backdrop-blur-md z-20">
                <form wire:submit.prevent="sendMessage" class="flex items-end gap-3 max-w-4xl mx-auto">
                    <div class="flex-1 relative">
                        <textarea wire:model="newMessage" 
                                  placeholder="Ketik balasan Anda..." 
                                  rows="1"
                                  class="w-full bg-slate-100 dark:bg-slate-800 border-none rounded-2xl pl-5 pr-12 py-3.5 text-sm font-medium focus:ring-2 focus:ring-slate-900 dark:focus:ring-white outline-none transition-all text-slate-900 dark:text-white resize-none custom-scrollbar"
                                  style="min-height: 48px; max-height: 120px;"
                                  oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'"
                                  required></textarea>
                    </div>
                           
                    <button type="submit" 
                            class="w-12 h-12 shrink-0 flex items-center justify-center bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-full hover:scale-105 transition-all shadow-lg focus:outline-none focus:ring-4 focus:ring-slate-900/20 disabled:opacity-50">
                        <svg wire:loading.remove wire:target="sendMessage" class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        <svg wire:loading wire:target="sendMessage" class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </button>
                </form>
            </div>

        @else
            <div class="flex-1 flex flex-col justify-center items-center bg-slate-50 dark:bg-[#0a0a0a]">
                <div class="w-24 h-24 bg-white dark:bg-slate-900 rounded-[2rem] flex items-center justify-center mb-6 shadow-2xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800 transform -rotate-6">
                    <svg class="w-10 h-10 text-slate-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                </div>
                <h3 class="text-2xl font-black text-slate-900 dark:text-white uppercase tracking-tighter">CodifyHQ Support</h3>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mt-2 max-w-xs text-center">Pilih klien dari daftar di sebelah kiri untuk membalas keluhan atau pertanyaan.</p>
            </div>
        @endif
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 20px; }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #334155; }
        /* Hilangkan scrollbar di textarea tapi tetap bisa di-scroll */
        textarea.custom-scrollbar::-webkit-scrollbar { display: none; }
        textarea.custom-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</div>