<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pemilik - Nasi Jinggo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 py-4 sm:px-6 lg:px-8 flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-900">Dashboard Pemilik</h1>
                <div class="flex items-center gap-4">
                    <span class="text-sm text-gray-600">{{ Auth::user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded text-sm">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 py-6 sm:px-6 lg:px-8">
            
            <!-- Welcome Message -->
            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded">
                    <p class="text-green-700">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Statistics Cards Row 1 -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <!-- Total Penjualan Hari Ini -->
                <div class="bg-gradient-to-r from-green-400 to-green-500 rounded-lg shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Penjualan Hari Ini</p>
                            <p class="text-3xl font-bold mt-2">Rp {{ number_format($salesToday, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-white bg-opacity-30 p-3 rounded-full">
                            💰
                        </div>
                    </div>
                </div>

                <!-- Total Transaksi -->
                <div class="bg-gradient-to-r from-blue-400 to-blue-500 rounded-lg shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Total Transaksi</p>
                            <p class="text-3xl font-bold mt-2">{{ number_format($transactionsToday) }}</p>
                        </div>
                        <div class="bg-white bg-opacity-30 p-3 rounded-full">
                            🧾
                        </div>
                    </div>
                </div>

                <!-- Total Produksi -->
                <div class="bg-gradient-to-r from-purple-400 to-purple-500 rounded-lg shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Produksi Hari Ini</p>
                            <p class="text-3xl font-bold mt-2">{{ number_format($productionToday) }} porsi</p>
                        </div>
                        <div class="bg-white bg-opacity-30 p-3 rounded-full">
                            🍱
                        </div>
                    </div>
                </div>

                <!-- Stok Menipis -->
                <div class="bg-gradient-to-r from-orange-400 to-orange-500 rounded-lg shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Stok Menipis</p>
                            <p class="text-3xl font-bold mt-2">{{ $lowStockIngredients }}</p>
                            <p class="text-xs opacity-75 mt-1">bahan baku</p>
                        </div>
                        <div class="bg-white bg-opacity-30 p-3 rounded-full">
                            ⚠️
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards Row 2 -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Penjualan Bulan Ini</h3>
                    <p class="text-2xl font-bold text-green-600">Rp {{ number_format($salesThisMonth, 0, ',', '.') }}</p>
                    <p class="text-sm text-gray-600 mt-1">{{ number_format($transactionsThisMonth) }} transaksi</p>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Produksi Bulan Ini</h3>
                    <p class="text-2xl font-bold text-purple-600">{{ number_format($productionThisMonth) }} porsi</p>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Karyawan Aktif</h3>
                    <p class="text-2xl font-bold text-blue-600">{{ $activeEmployees }} orang</p>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <a href="{{ route('users.index') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
                    <div class="flex items-center gap-4">
                        <div class="bg-blue-100 p-3 rounded-full text-2xl">👥</div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Kelola Karyawan</h3>
                            <p class="text-sm text-gray-600">Tambah & kelola akun staff</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('reports.profit-loss') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
                    <div class="flex items-center gap-4">
                        <div class="bg-purple-100 p-3 rounded-full text-2xl">📊</div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Laporan Laba Rugi</h3>
                            <p class="text-sm text-gray-600">Analisis keuangan usaha</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('pemilik.sales.index') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
                    <div class="flex items-center gap-4">
                        <div class="bg-green-100 p-3 rounded-full text-2xl">💵</div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Data Penjualan</h3>
                            <p class="text-sm text-gray-600">Lihat riwayat transaksi</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Recent Transactions -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Sales Chart -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Grafik Penjualan 7 Hari Terakhir</h3>
                    <canvas id="salesChart"></canvas>
                </div>

            <!-- Charts & Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Sales Chart -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Grafik Penjualan 7 Hari Terakhir</h3>
                    <canvas id="salesChart"></canvas>
                </div>

            <!-- Top Products -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Produk Terlaris</h3>
                <div class="space-y-3">
                    @forelse($topProducts as $index => $product)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-sm font-bold text-blue-600">
                                    {{ $index + 1 }}
                                </div>
                                <span class="text-sm font-medium text-gray-900">{{ $product->name }}</span>
                            </div>
                            <span class="text-sm font-bold text-gray-900">{{ number_format($product->total_sold) }} porsi</span>
                        </div>
                    @empty
                        <p class="text-gray-500 text-sm text-center py-4">Belum ada data penjualan</p>
                    @endforelse
                </div>
            </div>

             <!-- Karyawan Bekerja Hari Ini -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Karyawan Bekerja Hari Ini</h3>
                <div class="space-y-3">
                    @forelse($employeesWorkingToday as $employee)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold">
                                    {{ strtoupper(substr($employee->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $employee->name }}</p>
                                    <p class="text-xs text-gray-600">{{ ucfirst($employee->role) }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-blue-600">{{ $employee->transaction_count }}</p>
                                <p class="text-xs text-gray-500">transaksi</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <p class="text-gray-500 text-sm">Belum ada karyawan yang bekerja hari ini</p>
                        </div>
                    @endforelse
                </div>
            </div>


            <!-- Recent Transactions -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Transaksi Terbaru</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Invoice</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kasir</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Metode</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($recentTransactions as $transaction)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{ $transaction->invoice_number }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $transaction->sale_date->format('d/m/Y H:i') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $transaction->user->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">
                                        Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span class="px-2 py-1 bg-gray-100 rounded text-xs">{{ strtoupper($transaction->payment_method) }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada transaksi hari ini</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>

    <script>
        // Sales Chart
        const salesData = @json($salesLastWeek);
        const labels = salesData.map(item => {
            const date = new Date(item.date);
            return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
        });
        const data = salesData.map(item => item.total);

        const ctx = document.getElementById('salesChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Penjualan (Rp)',
                    data: data,
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>