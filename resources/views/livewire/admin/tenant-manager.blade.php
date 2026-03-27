<div class="space-y-8 font-sans">
    
    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="bg-slate-900 text-white p-4 rounded-xl flex items-center justify-between shadow-lg">
            <span class="text-sm font-bold">{{ session('success') }}</span>
            <button wire:click="$set('success', null)" class="text-slate-400 hover:text-white">✕</button>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="bg-red-50 text-red-600 border border-red-200 p-4 rounded-xl flex items-center justify-between shadow-sm">
            <span class="text-sm font-bold">{{ session('error') }}</span>
            <button wire:click="$set('error', null)" class="text-red-400 hover:text-red-600">✕</button>
        </div>
    @endif

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tighter uppercase">Tenant Directory</h1>
            <p class="text-slate-500 text-sm mt-1 font-medium">Kelola data klien, blokir akses, dan hapus data permanen.</p>
        </div>
        
        <div class="flex gap-3">
            <div class="bg-white dark:bg-slate-900 border-2 border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2 text-center">
                <p class="text-[10px] font-black uppercase text-slate-400">Total Tenant</p>
                <p class="text-xl font-black text-slate-900 dark:text-white">{{ $stats['total'] }}</p>
            </div>
            <div class="bg-slate-900 dark:bg-white rounded-xl px-4 py-2 text-center shadow-lg">
                <p class="text-[10px] font-black uppercase text-slate-400 dark:text-slate-500">Expiring Soon</p>
                <p class="text-xl font-black text-white dark:text-slate-900">{{ $stats['expiring_soon'] }}</p>
            </div>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="flex flex-col md:flex-row gap-4 justify-between bg-white dark:bg-slate-900 p-4 rounded-2xl border-2 border-slate-100 dark:border-slate-800 shadow-sm">
        <div class="flex gap-2 overflow-x-auto pb-2 md:pb-0 hide-scrollbar">
            <button wire:click="$set('filterStatus', 'all')" class="px-4 py-2 rounded-lg text-xs font-bold transition-all whitespace-nowrap {{ $filterStatus === 'all' ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900' : 'bg-slate-100 text-slate-500 hover:bg-slate-200' }}">Semua</button>
            <button wire:click="$set('filterStatus', 'active')" class="px-4 py-2 rounded-lg text-xs font-bold transition-all whitespace-nowrap {{ $filterStatus === 'active' ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900' : 'bg-slate-100 text-slate-500 hover:bg-slate-200' }}">Aman (Aktif)</button>
            <button wire:click="$set('filterStatus', 'expiring_soon')" class="px-4 py-2 rounded-lg text-xs font-bold transition-all whitespace-nowrap {{ $filterStatus === 'expiring_soon' ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900' : 'bg-slate-100 text-slate-500 hover:bg-slate-200' }}">Hampir Habis</button>
            <button wire:click="$set('filterStatus', 'expired')" class="px-4 py-2 rounded-lg text-xs font-bold transition-all whitespace-nowrap {{ $filterStatus === 'expired' ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900' : 'bg-slate-100 text-slate-500 hover:bg-slate-200' }}">Expired</button>
        </div>
        
        <div class="relative min-w-[250px]">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama bisnis..." class="w-full bg-slate-50 dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-lg pl-10 pr-4 py-2 text-sm font-medium focus:border-slate-900 outline-none transition-all">
            <svg class="w-4 h-4 absolute left-4 top-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="2"/></svg>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white dark:bg-slate-900 border-2 border-slate-100 dark:border-slate-800 rounded-3xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 dark:bg-slate-800/50">
                    <tr>
                        <th class="p-5 text-[10px] font-black uppercase tracking-widest text-slate-400">Bisnis & Owner</th>
                        <th class="p-5 text-[10px] font-black uppercase tracking-widest text-slate-400">Status Trial</th>
                        <th class="p-5 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($tenants as $tenant)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors" wire:key="tenant-{{ $tenant->id }}">
                        
                        <!-- Kolom Info Tenant -->
                        <td class="p-5">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center font-black text-slate-900 dark:text-white border border-slate-200 dark:border-slate-700 relative">
                                    {{ substr($tenant->name, 0, 1) }}
                                    <!-- Indikator Blokir -->
                                    @if(!$tenant->is_active)
                                        <span class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full border-2 border-white dark:border-slate-900"></span>
                                    @endif
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <p class="font-black text-slate-900 dark:text-white uppercase tracking-tighter">{{ $tenant->name }}</p>
                                        @if(!$tenant->is_active)
                                            <span class="px-1.5 py-0.5 rounded text-[8px] font-black uppercase bg-red-100 text-red-600 border border-red-200">BLOCKED</span>
                                        @endif
                                    </div>
                                    @php $owner = $tenant->users->first(); @endphp
                                    @if($owner)
                                        <p class="text-xs text-slate-500 font-medium">{{ $owner->name }} • {{ $owner->phone ?? 'No WA' }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <!-- Kolom Sisa Hari -->
                        <td class="p-5">
                            <div class="flex flex-col items-start gap-1">
                                <p class="text-sm font-bold text-slate-900 dark:text-white">
                                    {{ \Carbon\Carbon::parse($tenant->trial_until)->format('d M Y') }}
                                </p>
                                
                                @if($tenant->days_left < 0)
                                    <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase bg-slate-900 text-white dark:bg-white dark:text-slate-900">
                                        Expired (Terlewat {{ abs($tenant->days_left) }} hari)
                                    </span>
                                @elseif($tenant->days_left <= 3)
                                    <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase bg-slate-200 text-slate-800 dark:bg-slate-700 dark:text-slate-200 border border-slate-300 dark:border-slate-600">
                                        Sisa {{ $tenant->days_left }} Hari
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase text-slate-500">
                                        Sisa {{ $tenant->days_left }} Hari
                                    </span>
                                @endif
                            </div>
                        </td>

                        <!-- Kolom Tombol Aksi -->
                        <td class="p-5 text-right">
                            <div class="flex justify-end items-center gap-1.5">
                                
                                <!-- Tombol Kirim Email -->
                                <button wire:click="sendEmailReminder({{ $tenant->id }})" 
                                    class="p-2 text-slate-400 hover:text-slate-900 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-all" title="Kirim Email">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" stroke-width="2"/></svg>
                                </button>

                                <!-- Tombol Kirim WA -->
                                <button wire:click="sendWhatsAppReminder({{ $tenant->id }})" 
                                    class="p-2 text-slate-400 hover:text-slate-900 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-all" title="Kirim WhatsApp">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                </button>

                                <!-- Separator -->
                                <div class="w-px h-6 bg-slate-200 dark:bg-slate-700 mx-1"></div>

                                <!-- Tombol Blokir / Buka Blokir -->
                                <button wire:click="toggleSuspend({{ $tenant->id }})" 
                                    class="p-2 rounded-lg transition-all {{ $tenant->is_active ? 'text-slate-400 hover:text-orange-600 hover:bg-orange-50' : 'text-orange-600 bg-orange-50 border border-orange-200' }}" 
                                    title="{{ $tenant->is_active ? 'Blokir Tenant' : 'Buka Blokir' }}">
                                    @if($tenant->is_active)
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                    @else
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path></svg>
                                    @endif
                                </button>

                                <!-- Tombol Hapus (Dengan Konfirmasi) -->
                                <button 
                                    wire:click="deleteTenant({{ $tenant->id }})" 
                                    wire:confirm="YAKIN HAPUS PERMANEN? Aksi ini akan menghapus semua user, produk, dan laporan keuangan milik tenant ini secara permanen!"
                                    class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Hapus Permanen">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>

                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="p-10 text-center">
                            <p class="text-slate-400 font-bold uppercase tracking-widest text-xs">Tidak ada data tenant ditemukan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100 dark:border-slate-800">
            {{ $tenants->links() }}
        </div>
    </div>
</div>