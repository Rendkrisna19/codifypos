<div class="space-y-8 font-sans pb-10">
    
    <div>
        <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tighter uppercase">Analitik Bisnis</h1>
        <p class="text-slate-500 text-sm mt-1 font-medium">Pantau pertumbuhan SaaS CodifyPOS, pendapatan bulanan, dan retensi pengguna.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-[#111111] dark:bg-white text-white dark:text-[#111111] p-6 rounded-2xl shadow-xl relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 dark:bg-black/5 rounded-full blur-xl"></div>
            <p class="text-[10px] font-black uppercase tracking-widest opacity-70 mb-1">Total Pendapatan (All Time)</p>
            <h3 class="text-3xl font-black tracking-tighter">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
        </div>

        <div class="bg-white dark:bg-slate-900 border-2 border-slate-100 dark:border-slate-800 p-6 rounded-2xl shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Klien Terdaftar</p>
            <div class="flex items-end gap-3">
                <h3 class="text-3xl font-black text-slate-900 dark:text-white">{{ $totalTenants }}</h3>
                <span class="text-xs font-bold text-slate-400 mb-1">Tenant</span>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 border-2 border-slate-100 dark:border-slate-800 p-6 rounded-2xl shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Klien Aktif Saat Ini</p>
            <div class="flex items-end gap-3">
                <h3 class="text-3xl font-black text-emerald-500">{{ $activeTenants }}</h3>
                <span class="text-xs font-bold text-emerald-500/70 mb-1">Aman</span>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 border-2 border-slate-100 dark:border-slate-800 p-6 rounded-2xl shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Klien Expired / Suspend</p>
            <div class="flex items-end gap-3">
                <h3 class="text-3xl font-black text-red-500">{{ $expiredTenants }}</h3>
                <span class="text-xs font-bold text-red-500/70 mb-1">Butuh Follow Up</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2 bg-white dark:bg-slate-900 border-2 border-slate-100 dark:border-slate-800 p-6 rounded-3xl shadow-sm">
            <div class="mb-4">
                <h3 class="text-lg font-black text-slate-900 dark:text-white uppercase tracking-tight">Tren Pendapatan</h3>
                <p class="text-xs font-medium text-slate-500">6 Bulan Terakhir</p>
            </div>
            
            <div wire:ignore id="revenueChart" class="w-full h-72"></div>
        </div>

        <div class="bg-white dark:bg-slate-900 border-2 border-slate-100 dark:border-slate-800 p-6 rounded-3xl shadow-sm">
            <div class="mb-4">
                <h3 class="text-lg font-black text-slate-900 dark:text-white uppercase tracking-tight">Distribusi Paket</h3>
                <p class="text-xs font-medium text-slate-500">Paket Paling Laris Dibeli</p>
            </div>
            
            <div wire:ignore id="packageChart" class="w-full h-72 flex justify-center items-center"></div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('livewire:initialized', () => {
            
            // 1. Inisialisasi Chart Pendapatan (Line Chart)
            const revenueOptions = {
                series: [{
                    name: 'Pendapatan (Rp)',
                    data: {!! $chartRevenues !!}
                }],
                chart: {
                    type: 'area',
                    height: 300,
                    toolbar: { show: false },
                    fontFamily: 'Space Grotesk, sans-serif'
                },
                colors: ['#111111'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.7,
                        opacityTo: 0.1,
                        stops: [0, 90, 100]
                    }
                },
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 3 },
                xaxis: {
                    categories: {!! $chartMonths !!},
                    labels: { style: { cssClass: 'text-xs font-bold text-slate-500' } },
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                yaxis: {
                    labels: {
                        formatter: function (val) { return "Rp " + val.toLocaleString("id-ID"); },
                        style: { cssClass: 'text-xs font-bold text-slate-500' }
                    }
                },
                grid: { borderColor: '#f1f5f9', strokeDashArray: 4 },
                tooltip: { theme: 'dark' }
            };

            const revenueChart = new ApexCharts(document.querySelector("#revenueChart"), revenueOptions);
            revenueChart.render();

            // 2. Inisialisasi Chart Paket (Donut Chart)
            const packageCountsData = {!! $packageCounts !!};
            
            // Cek jika data kosong, isi dengan array [0] agar chart tidak error
            const finalPackageCounts = packageCountsData.length > 0 ? packageCountsData : [1];
            const finalPackageNames = packageCountsData.length > 0 ? {!! $packageNames !!} : ['Belum Ada Transaksi'];

            const packageOptions = {
                series: finalPackageCounts,
                labels: finalPackageNames,
                chart: {
                    type: 'donut',
                    height: 300,
                    fontFamily: 'Space Grotesk, sans-serif'
                },
                colors: ['#111111', '#475569', '#94a3b8', '#cbd5e1'],
                plotOptions: {
                    pie: {
                        donut: { size: '75%' }
                    }
                },
                dataLabels: { enabled: false },
                stroke: { show: false },
                legend: { position: 'bottom', fontSize: '12px', fontWeight: 'bold' },
                tooltip: { theme: 'light' }
            };

            const packageChart = new ApexCharts(document.querySelector("#packageChart"), packageOptions);
            packageChart.render();
        });
    </script>
</div>