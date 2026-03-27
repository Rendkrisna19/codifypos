<div class="space-y-8 font-sans pb-10">

    @if (session()->has('success'))
        <div class="bg-slate-900 text-white p-4 rounded-xl flex items-center justify-between shadow-lg">
            <span class="text-sm font-bold">{{ session('success') }}</span>
            <button wire:click="$set('success', null)" class="text-slate-400 hover:text-white">✕</button>
        </div>
    @endif

    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tighter uppercase">Riwayat Transaksi</h1>
            <p class="text-slate-500 text-sm mt-1 font-medium">Pantau semua pembayaran masuk dari Midtrans secara real-time.</p>
        </div>
        
        <div class="flex gap-3 w-full lg:w-auto">
            <button wire:click="exportExcel" class="flex-1 lg:flex-none flex items-center justify-center gap-2 px-5 py-2.5 bg-green-50 text-green-700 hover:bg-green-100 border border-green-200 font-bold text-xs rounded-xl uppercase tracking-widest transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Export Excel
            </button>
            <button wire:click="exportPdf" class="flex-1 lg:flex-none flex items-center justify-center gap-2 px-5 py-2.5 bg-slate-900 text-white hover:bg-slate-800 font-bold text-xs rounded-xl uppercase tracking-widest transition-all shadow-lg">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak PDF
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border-2 border-slate-100 dark:border-slate-800 flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Revenue Sukses</p>
                <h3 class="text-2xl font-black text-slate-900 dark:text-white">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
            </div>
            <div class="w-12 h-12 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border-2 border-slate-100 dark:border-slate-800 flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Transaksi Sukses</p>
                <h3 class="text-2xl font-black text-slate-900 dark:text-white">{{ $successCount }}</h3>
            </div>
            <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border-2 border-slate-100 dark:border-slate-800 flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Menunggu Pembayaran</p>
                <h3 class="text-2xl font-black text-slate-900 dark:text-white">{{ $pendingCount }}</h3>
            </div>
            <div class="w-12 h-12 rounded-full bg-orange-50 flex items-center justify-center text-orange-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 p-5 rounded-[2rem] border-2 border-slate-100 dark:border-slate-800 shadow-sm flex flex-col md:flex-row gap-4 items-end">
        
        <div class="w-full md:w-1/3">
            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Cari Invoice / Cafe</label>
            <div class="relative">
                <input type="text" wire:model.live.debounce.500ms="search" placeholder="INV-..." class="w-full bg-slate-50 dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-xl pl-10 pr-4 py-2.5 text-sm font-medium focus:border-slate-900 dark:focus:border-white outline-none transition-all">
                <svg class="w-4 h-4 absolute left-4 top-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="2"/></svg>
            </div>
        </div>

        <div class="w-full md:w-1/5">
            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Status</label>
            <select wire:model.live="statusFilter" class="w-full bg-slate-50 dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-900 dark:text-white focus:border-slate-900 outline-none cursor-pointer">
                <option value="all">Semua Status</option>
                <option value="success">Sukses (Settlement)</option>
                <option value="pending">Tertunda (Pending)</option>
                <option value="failed">Gagal / Expired</option>
            </select>
        </div>

        <div class="w-full md:w-1/5">
            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Dari Tanggal</label>
            <input type="date" wire:model.live="dateFrom" class="w-full bg-slate-50 dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-900 dark:text-white outline-none">
        </div>
        <div class="w-full md:w-1/5">
            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Sampai Tanggal</label>
            <input type="date" wire:model.live="dateTo" class="w-full bg-slate-50 dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-900 dark:text-white outline-none">
        </div>

        <div class="w-full md:w-auto">
            <button wire:click="resetFilters" class="w-full md:w-auto px-5 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold text-xs rounded-xl uppercase tracking-widest transition-all whitespace-nowrap">
                Reset
            </button>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 border-2 border-slate-100 dark:border-slate-800 rounded-3xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead class="bg-slate-50 dark:bg-slate-800/50 border-b-2 border-slate-100 dark:border-slate-800">
                    <tr>
                        <th class="p-5 text-[10px] font-black uppercase tracking-widest text-slate-400">Order ID & Waktu</th>
                        <th class="p-5 text-[10px] font-black uppercase tracking-widest text-slate-400">Bisnis (Tenant)</th>
                        <th class="p-5 text-[10px] font-black uppercase tracking-widest text-slate-400">Paket Langganan</th>
                        <th class="p-5 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">Nominal</th>
                        <th class="p-5 text-[10px] font-black uppercase tracking-widest text-slate-400 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($transactions as $trx)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors" wire:key="trx-{{ $trx->id }}">
                        
                        <td class="p-5">
                            <p class="font-black text-slate-900 dark:text-white text-sm">{{ $trx->order_id }}</p>
                            <p class="text-xs font-medium text-slate-500 mt-1">{{ $trx->created_at->format('d M Y, H:i') }}</p>
                        </td>

                        <td class="p-5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 flex items-center justify-center font-black text-slate-900 dark:text-white border border-slate-200 dark:border-slate-700">
                                    {{ substr($trx->tenant->name ?? '?', 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-bold text-slate-900 dark:text-white uppercase tracking-tighter text-sm">{{ $trx->tenant->name ?? 'Tenant Dihapus' }}</p>
                                </div>
                            </div>
                        </td>

                        <td class="p-5">
                            <p class="font-bold text-slate-700 dark:text-slate-300 text-sm">{{ $trx->package->name ?? 'Paket Dihapus' }}</p>
                            <p class="text-xs font-medium text-slate-400 mt-1">{{ $trx->package->duration_days ?? 0 }} Hari</p>
                        </td>

                        <td class="p-5 text-right">
                            <p class="font-black text-slate-900 dark:text-white text-sm">Rp {{ number_format($trx->amount, 0, ',', '.') }}</p>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">{{ str_replace('_', ' ', $trx->payment_type ?? 'Belum Dipilih') }}</p>
                        </td>

                        <td class="p-5 text-center">
                            @if($trx->status === 'success')
                                <span class="inline-block px-3 py-1 bg-emerald-100 text-emerald-700 border border-emerald-200 rounded-full text-[10px] font-black uppercase tracking-widest">Berhasil</span>
                            @elseif($trx->status === 'pending')
                                <span class="inline-block px-3 py-1 bg-orange-100 text-orange-700 border border-orange-200 rounded-full text-[10px] font-black uppercase tracking-widest">Tertunda</span>
                            @else
                                <span class="inline-block px-3 py-1 bg-red-100 text-red-700 border border-red-200 rounded-full text-[10px] font-black uppercase tracking-widest">Gagal/Expired</span>
                            @endif
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-12 text-center">
                            <div class="flex flex-col items-center justify-center text-slate-400">
                                <svg class="w-12 h-12 mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <p class="font-bold uppercase tracking-widest text-xs">Data transaksi tidak ditemukan.</p>
                                <p class="text-sm font-medium mt-1">Coba sesuaikan filter pencarian atau tanggal.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-5 border-t-2 border-slate-100 dark:border-slate-800">
            {{ $transactions->links() }}
        </div>
    </div>
</div>  