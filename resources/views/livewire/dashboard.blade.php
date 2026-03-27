<div wire:poll.10s>
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#111111] dark:text-white transition-colors duration-300">Ringkasan Hari Ini</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Pantau performa cafe Anda secara real-time.</p>
        </div>
        {{-- <button class="bg-white dark:bg-[#222222] border border-gray-200 dark:border-gray-800 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 dark:hover:bg-[#333333] flex items-center gap-2 shadow-sm transition-colors duration-300 dark:text-white">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Unduh Laporan
        </button> --}}
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white dark:bg-[#111111] p-6 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm relative overflow-hidden transition-colors duration-300">
            <div class="absolute right-0 top-0 w-24 h-24 bg-gray-100 dark:bg-[#222222] rounded-bl-full -mr-8 -mt-8 transition-colors duration-300"></div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total Pendapatan (Gross)</p>
            <h3 class="text-3xl font-bold text-[#111111] dark:text-white transition-colors duration-300">Rp {{ number_format($totalSales, 0, ',', '.') }}</h3>
            <p class="text-xs text-[#111111] dark:text-gray-300 font-medium mt-2 flex items-center gap-1">Penjualan Lunas Hari Ini</p>
        </div>

        <div class="bg-white dark:bg-[#111111] p-6 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm relative overflow-hidden transition-colors duration-300">
            <div class="absolute right-0 top-0 w-24 h-24 bg-gray-100 dark:bg-[#222222] rounded-bl-full -mr-8 -mt-8 transition-colors duration-300"></div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Margin Laba Bersih</p>
            <h3 class="text-3xl font-bold text-[#111111] dark:text-white transition-colors duration-300">Rp {{ number_format($margin, 0, ',', '.') }}</h3>
            <p class="text-xs text-gray-400 dark:text-gray-500 font-medium mt-2 flex items-center gap-1">Dihitung otomatis dari HPP</p>
        </div>

        <div class="bg-white dark:bg-[#111111] p-6 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm relative overflow-hidden transition-colors duration-300">
             <div class="absolute right-0 top-0 w-24 h-24 bg-gray-100 dark:bg-[#222222] rounded-bl-full -mr-8 -mt-8 transition-colors duration-300"></div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total Pesanan</p>
            <h3 class="text-3xl font-bold text-[#111111] dark:text-white transition-colors duration-300">{{ $totalOrders }} <span class="text-lg text-gray-400 font-normal">Transaksi</span></h3>
            <p class="text-xs text-[#111111] dark:text-gray-300 font-medium mt-2 flex items-center gap-1">Pesanan lunas hari ini</p>
        </div>
    </div>

    <div class="p-6 bg-white dark:bg-[#111111] border border-gray-200 dark:border-gray-800 rounded-2xl shadow-sm" wire:ignore>
        <div class="mb-4">
            <h2 class="text-xl font-bold text-[#111111] dark:text-white">Grafik Penjualan 7 Hari Terakhir</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Statistik pesanan lunas per hari</p>
        </div>
        
        <div class="relative h-72 w-full">
            <canvas id="penjualanChart"></canvas>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var chartInstance = null;

        document.addEventListener('livewire:navigated', function() {
            initChart();
        });

        // Re-init chart setelah wire:poll berjalan
        document.addEventListener('livewire:load', function () {
            initChart();
        });

        function initChart() {
            const canvas = document.getElementById('penjualanChart');
            if (!canvas) return;

            const ctx = canvas.getContext('2d');
            const isDarkMode = document.documentElement.classList.contains('dark') || localStorage.getItem('theme') === 'dark';
            const textColor = isDarkMode ? '#e5e7eb' : '#111111';
            const gridColor = isDarkMode ? '#333333' : '#e5e7eb';
            const lineColor = isDarkMode ? '#ffffff' : '#111111';
            const bgColor = isDarkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(17, 17, 17, 0.05)';

            if (chartInstance !== null) {
                chartInstance.destroy();
            }

            // Ambil data dari backend via Blade
            const labelsData = @json($chartLabels);
            const valuesData = @json($chartData);

            chartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labelsData,
                    datasets: [{
                        label: 'Total Penjualan',
                        data: valuesData,
                        borderColor: lineColor,
                        backgroundColor: bgColor,
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: isDarkMode ? '#111111' : '#ffffff',
                        pointBorderColor: lineColor,
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) { label += ': '; }
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: gridColor, drawBorder: false },
                            ticks: {
                                color: textColor,
                                callback: function(value) {
                                    if (value >= 1000000) return 'Rp ' + (value / 1000000) + 'M';
                                    if (value >= 1000) return 'Rp ' + (value / 1000) + 'K';
                                    return 'Rp ' + value;
                                }
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { color: textColor }
                        }
                    }
                }
            });
        }
    </script>
</div>