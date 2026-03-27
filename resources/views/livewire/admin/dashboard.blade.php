<div wire:poll.30s class="space-y-8">
    <!-- Header Page -->
    <div>
        <h1 class="text-4xl font-black text-slate-900 dark:text-white tracking-tighter uppercase">SaaS Overview</h1>
        <p class="text-slate-500 dark:text-slate-400 font-medium italic mt-1">Pusat Kendali Sistem CodifyPOS</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Revenue Card (Black Version) -->
        <div class="bg-slate-900 dark:bg-white p-6 rounded-[2rem] shadow-2xl transition-transform hover:scale-[1.02]" wire:key="stat-revenue">
            <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] mb-2">Estimasi MRR</p>
            <h3 class="text-2xl font-black text-white dark:text-slate-900 tracking-tight">
                Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}
            </h3>
            <div class="mt-4 flex items-center gap-2">
                <span class="px-2 py-0.5 rounded-full bg-white/10 dark:bg-slate-100 text-[10px] font-black text-white dark:text-slate-900 border border-white/20">
                    +12.5% GROWTH
                </span>
            </div>
        </div>

        <!-- Total Tenant -->
        <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] border-2 border-slate-100 dark:border-slate-800 shadow-sm" wire:key="stat-total">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-2">Total Tenant</p>
            <h3 class="text-3xl font-black text-slate-900 dark:text-white leading-none">{{ $totalTenants }}</h3>
            <p class="text-[10px] font-medium text-slate-400 mt-3 flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-slate-900 dark:bg-white"></span>
Terdaftar secara global
            </p>
        </div>

        <!-- Active Status -->
        <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] border-2 border-slate-100 dark:border-slate-800 shadow-sm" wire:key="stat-active">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-2">Tenant Aktif</p>
            <h3 class="text-3xl font-black text-slate-900 dark:text-white leading-none">{{ $activeTenants }}</h3>
            <p class="text-[10px] font-bold text-slate-900 dark:text-slate-400 mt-3 underline decoration-2">Sirkulasi Aktif</p>
        </div>

        <!-- Expired Status -->
        <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] border-2 border-slate-900 dark:border-slate-100 shadow-sm" wire:key="stat-expired">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-2">Expired</p>
            <h3 class="text-3xl font-black text-red-600 dark:text-red-500 leading-none">{{ $expiredTenants }}</h3>
            <p class="text-[10px] font-medium text-slate-400 mt-3">Tindakan diperlukan</p>
        </div>
    </div>

    <!-- Middle Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Chart Pertumbuhan -->
        <div wire:ignore class="lg:col-span-2 bg-white dark:bg-slate-900 p-8 rounded-[2rem] border-2 border-slate-100 dark:border-slate-800"
             x-data="{
                labels: @json($chartLabels),
                data: @json($tenantGrowth),
                init() {
                    const ctx = $refs.canvas.getContext('2d');
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: this.labels,
                            datasets: [{
                                label: 'Tenant Baru',
                                data: this.data,
                                borderColor: '#0f172a',
                                borderWidth: 4,
                                fill: false,
                                tension: 0.4,
                                pointBackgroundColor: '#fff',
                                pointBorderWidth: 3,
                                pointRadius: 5
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: { display: false },
                                x: { grid: { display: false }, ticks: { font: { family: 'Space Grotesk', weight: '700' } } }
                            },
                            plugins: { legend: { display: false } }
                        }
                    });
                }
             }">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-lg font-black text-slate-900 dark:text-white uppercase tracking-tight">Data Pertumbuhan</h3>
                <span class="text-[10px] font-bold border-2 border-slate-900 dark:border-white px-3 py-1 rounded-full">REALTIME</span>
            </div>
            <div class="h-72">
                <canvas x-ref="canvas"></canvas>
            </div>
        </div>

        <!-- Quick Actions (Black Card) -->
        <div class="bg-slate-900 dark:bg-white rounded-[2rem] p-8 text-white dark:text-slate-900 flex flex-col justify-between border-4 border-slate-100 dark:border-slate-800 shadow-2xl">
            <div>
                <div class="w-12 h-12 bg-white/10 dark:bg-slate-900/10 rounded-2xl flex items-center justify-center mb-6">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
                <h3 class="text-2xl font-black leading-tight uppercase tracking-tighter mb-4">Database Management</h3>
                <p class="text-slate-400 dark:text-slate-500 text-sm font-medium leading-relaxed">
                    Akses penuh ke semua tabel tenant secara terisolasi. Gunakan dengan bijak.
                </p>
            </div>
            <button class="w-full mt-10 py-4 bg-white dark:bg-slate-900 text-slate-900 dark:text-white font-black rounded-2xl hover:bg-slate-200 dark:hover:bg-slate-800 transition-all uppercase tracking-widest text-xs shadow-lg shadow-white/5">
                Buka SQL Manager
            </button>
        </div>
    </div>
</div>