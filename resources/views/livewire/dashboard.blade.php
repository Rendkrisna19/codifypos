<div wire:poll.10s="updateDummyData">
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#111111] dark:text-white transition-colors duration-300">Ringkasan Hari Ini</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Pantau performa cafe Anda secara real-time.</p>
        </div>
        <button class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 dark:hover:bg-gray-700 flex items-center gap-2 shadow-sm transition-colors duration-300">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Unduh Laporan
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm relative overflow-hidden transition-colors duration-300">
            <div class="absolute right-0 top-0 w-24 h-24 bg-blue-500/10 rounded-bl-full -mr-8 -mt-8"></div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total Pendapatan (Gross)</p>
            <h3 class="text-3xl font-bold text-[#111111] dark:text-white transition-colors duration-300">Rp {{ number_format($totalSales, 0, ',', '.') }}</h3>
            <p class="text-xs text-green-500 dark:text-green-400 font-medium mt-2 flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                +12% dari kemarin
            </p>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm relative overflow-hidden transition-colors duration-300">
            <div class="absolute right-0 top-0 w-24 h-24 bg-green-500/10 rounded-bl-full -mr-8 -mt-8"></div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Margin Laba Bersih</p>
            <h3 class="text-3xl font-bold text-[#111111] dark:text-white transition-colors duration-300">Rp {{ number_format($margin, 0, ',', '.') }}</h3>
            <p class="text-xs text-gray-400 dark:text-gray-500 font-medium mt-2">Dihitung otomatis dari HPP</p>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm relative overflow-hidden transition-colors duration-300">
             <div class="absolute right-0 top-0 w-24 h-24 bg-orange-500/10 rounded-bl-full -mr-8 -mt-8"></div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total Pesanan</p>
            <h3 class="text-3xl font-bold text-[#111111] dark:text-white transition-colors duration-300">{{ $totalOrders }} <span class="text-lg text-gray-400 font-normal">Transaksi</span></h3>
            <p class="text-xs text-green-500 dark:text-green-400 font-medium mt-2 flex items-center gap-1">
                 <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                +5 orderan baru
            </p>
        </div>
    </div>

    <div wire:ignore class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm transition-colors duration-300" x-data="salesChart()">
        <h3 class="text-lg font-semibold text-[#111111] dark:text-white mb-4">Grafik Penjualan Mingguan</h3>
        <div class="w-full h-72 relative">
            <canvas id="myChart"></canvas>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('salesChart', () => ({
                init() {
                    const ctx = document.getElementById('myChart');
                    // Mendapatkan warna berdasarkan theme saat ini untuk garis grafik
                    const isDark = document.documentElement.classList.contains('dark');
                    const lineColor = isDark ? '#ffffff' : '#111111';
                    const bgFillColor = isDark ? 'rgba(255, 255, 255, 0.05)' : 'rgba(17, 17, 17, 0.05)';

                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
                            datasets: [{
                                label: 'Pendapatan (Rp)',
                                data: [1200000, 1900000, 1500000, 2200000, 2800000, 3500000, 4100000],
                                borderColor: lineColor,
                                backgroundColor: bgFillColor,
                                borderWidth: 3,
                                tension: 0.4,
                                fill: true,
                                pointBackgroundColor: '#ffffff',
                                pointBorderColor: lineColor,
                                pointBorderWidth: 2,
                                pointRadius: 4,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: {
                                y: { beginAtZero: true, grid: { color: isDark ? '#374151' : '#e5e7eb', borderDash: [5, 5] } },
                                x: { grid: { display: false } }
                            }
                        }
                    });
                }
            }));
        });
    </script>
</div>