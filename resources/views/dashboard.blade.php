@extends('layouts.app')

@section('title', 'Dashboard')

@section('page-title','Dashboard')

@section('content')
    <!-- Dashboard Widgets -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-blue-500 text-white p-6 rounded-lg shadow-lg">
            <h2 class="text-lg font-semibold">Saldo Bulan Lalu</h2>
            <p class="text-3xl font-bold mt-2">Rp 10.000.000</p>
        </div>
        <div class="bg-green-500 text-white p-6 rounded-lg shadow-lg">
            <h2 class="text-lg font-semibold">Nilai Transfer Bulan Ini</h2>
            <p class="text-3xl font-bold mt-2">Rp 3.500.000</p>
        </div>
        <div class="bg-yellow-500 text-white p-6 rounded-lg shadow-lg">
            <h2 class="text-lg font-semibold">Sisa Saldo Bulan Ini</h2>
            <p class="text-3xl font-bold mt-2">Rp 6.500.000</p>
        </div>
    </div>

    {{-- <!-- Tabel Uang Masuk -->
    <div class="mb-6 bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Uang Masuk</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Keterangan</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">2025-12-01</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">Transfer Toko A</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 dark:text-green-400 text-right font-semibold">Rp 500.000</td>
                    </tr>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">2025-12-05</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">Transfer Toko B</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 dark:text-green-400 text-right font-semibold">Rp 300.000</td>
                    </tr>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">2025-12-10</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">Transfer Toko C</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 dark:text-green-400 text-right font-semibold">Rp 700.000</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tabel Uang Keluar -->
    <div class="mb-6 bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Uang Keluar</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Keterangan</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">2025-12-03</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">Belanja Alat Tulis</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 dark:text-red-400 text-right font-semibold">Rp 200.000</td>
                    </tr>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">2025-12-07</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">Biaya Listrik</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 dark:text-red-400 text-right font-semibold">Rp 100.000</td>
                    </tr>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">2025-12-12</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">Biaya Transportasi</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 dark:text-red-400 text-right font-semibold">Rp 150.000</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div> --}}

    <!-- Grafik Pie Chart -->
    <div class="mb-6 bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Grafik Pemasukan & Pengeluaran</h3>
        <div class="max-w-md mx-auto">
            <canvas id="pieChart"></canvas>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('pieChart').getContext('2d');
    
    // Check if dark mode is active
    const isDarkMode = document.documentElement.classList.contains('dark');
    const textColor = isDarkMode ? '#e5e7eb' : '#374151';
    
    const pieChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Uang Masuk', 'Uang Keluar'],
            datasets: [{
                label: 'Total',
                data: [10000000, 3500000], // data dalam rupiah
                backgroundColor: ['#22c55e', '#ef4444'], // hijau & merah
                borderWidth: 2,
                borderColor: isDarkMode ? '#1f2937' : '#ffffff'
            }]
        },
        options: { 
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    labels: {
                        color: textColor,
                        font: {
                            size: 14
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += 'Rp ' + context.parsed.toLocaleString('id-ID');
                            return label;
                        }
                    }
                }
            }
        }
    });
    
    // Update chart colors when theme changes
    if (typeof toggleTheme !== 'undefined') {
        const originalToggleTheme = toggleTheme;
        toggleTheme = function() {
            originalToggleTheme();
            setTimeout(() => {
                const isDark = document.documentElement.classList.contains('dark');
                pieChart.options.plugins.legend.labels.color = isDark ? '#e5e7eb' : '#374151';
                pieChart.data.datasets[0].borderColor = isDark ? '#1f2937' : '#ffffff';
                pieChart.update();
            }, 100);
        };
    }
</script>
@endpush