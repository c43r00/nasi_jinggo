<div>
    @extends('layouts.app')

@section('title', 'Dashboard Pemilik')
@section('page-title', 'Dashboard Pemilik')
@section('page-description', 'Overview bisnis dan statistik penjualan')

@section('content')
<div class="space-y-6">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Penjualan Hari Ini -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-blue-100 text-sm">Penjualan Hari Ini</p>
                    <h3 class="text-3xl font-bold mt-2">Rp {{ number_format($salesToday ?? 0, 0, ',', '.') }}</h3>
                    <p class="text-sm mt-2 text-blue-100">
                        <i class="fas fa-arrow-up"></i> {{ $transactionsToday ?? 0 }} transaksi
                    </p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-lg p-3">
                    <i class="fas fa-money-bill-wave text-2xl"></i>
                </div>
            </div>
        </div>
        
        <!-- Total Produksi Hari Ini -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-green-100 text-sm">Produksi Hari Ini</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $productionToday ?? 0 }}</h3>
                    <p class="text-sm mt-2 text-green-100">
                        <i class="fas fa-box"></i> Bungkus diproduksi
                    </p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-lg p-3">
                    <i class="fas fa-utensils text-2xl"></i>
                </div>
            </div>
        </div>
        
        <!-- Stok Tersedia -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-purple-100 text-sm">Stok Tersedia</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $stockAvailable ?? 0 }}</h3>
                    <p class="text-sm mt-2 text-purple-100">
                        <i class="fas fa-warehouse"></i> Bungkus ready
                    </p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-lg p-3">
                    <i class="fas fa-boxes text-2xl"></i>
                </div>
            </div>
        </div>
        
        <!-- Total Karyawan -->
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-orange-100 text-sm">Total Karyawan</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $totalEmployees ?? 0 }}</h3>
                    <p class="text-sm mt-2 text-orange-100">
                        <i class="fas fa-user-check"></i> {{ $activeEmployees ?? 0 }} aktif
                    </p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-lg p-3">
                    <i class="fas fa-users text-2xl"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Grafik Penjualan 7 Hari -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-bold text-gray-800">
                    <i class="fas fa-chart-line text-blue-500 mr-2"></i>
                    Penjualan 7 Hari Terakhir
                </h3>
                <span class="text-sm text-gray-500">{{ \Carbon\Carbon::now()->format('d M') }}</span>
            </div>
            <canvas id="salesChart" height="250"></canvas>
        </div>
        
        <!-- Produk Terlaris -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-bold text-gray-800">
                    <i class="fas fa-fire text-orange-500 mr-2"></i>
                    Produk Terlaris
                </h3>
                <span class="text-sm text-gray-500">Minggu Ini</span>
            </div>
            <div class="space-y-4">
                @forelse($topProducts ?? [] as $index => $product)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                {{ $index + 1 }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">{{ $product->name ?? 'Nasi Jinggo' }}</p>
                                <p class="text-sm text-gray-500">{{ $product->sold ?? 0 }} terjual</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-green-600">Rp {{ number_format($product->revenue ?? 0, 0, ',', '.') }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-400">
                        <i class="fas fa-box-open text-4xl mb-3"></i>
                        <p>Belum ada data penjualan</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    
    <!-- Recent Transactions & Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Transactions -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-bold text-gray-800">
                    <i class="fas fa-receipt text-blue-500 mr-2"></i>
                    Transaksi Terbaru
                </h3>
                <a href="{{ route('sales.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                    Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-3 px-2 text-sm font-semibold text-gray-600">ID</th>
                            <th class="text-left py-3 px-2 text-sm font-semibold text-gray-600">Waktu</th>
                            <th class="text-left py-3 px-2 text-sm font-semibold text-gray-600">Kasir</th>
                            <th class="text-right py-3 px-2 text-sm font-semibold text-gray-600">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentTransactions ?? [] as $transaction)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3 px-2 text-sm">#{{ $transaction->id ?? '001' }}</td>
                                <td class="py-3 px-2 text-sm text-gray-600">{{ $transaction->created_at ?? now()->format('H:i') }}</td>
                                <td class="py-3 px-2 text-sm">{{ $transaction->user->name ?? 'Kasir' }}</td>
                                <td class="py-3 px-2 text-sm text-right font-semibold text-green-600">
                                    Rp {{ number_format($transaction->total_amount ?? 5000, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-8 text-gray-400">
                                    <i class="fas fa-inbox text-3xl mb-2"></i>
                                    <p>Belum ada transaksi hari ini</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-6">
                <i class="fas fa-bolt text-yellow-500 mr-2"></i>
                Quick Actions
            </h3>
            
            <div class="space-y-3">
                <a href="{{ route('users.create') }}" class="block w-full bg-blue-500 hover:bg-blue-600 text-white rounded-lg p-4 text-center transition">
                    <i class="fas fa-user-plus text-xl mb-2"></i>
                    <p class="font-semibold">Tambah Karyawan</p>
                </a>
                
                <a href="#" class="block w-full bg-green-500 hover:bg-green-600 text-white rounded-lg p-4 text-center transition">
                    <i class="fas fa-file-download text-xl mb-2"></i>
                    <p class="font-semibold">Download Laporan</p>
                </a>
                
                <a href="#" class="block w-full bg-purple-500 hover:bg-purple-600 text-white rounded-lg p-4 text-center transition">
                    <i class="fas fa-chart-bar text-xl mb-2"></i>
                    <p class="font-semibold">Analisis Bisnis</p>
                </a>
                
                <a href="{{ route('profile') }}" class="block w-full bg-gray-500 hover:bg-gray-600 text-white rounded-lg p-4 text-center transition">
                    <i class="fas fa-cog text-xl mb-2"></i>
                    <p class="font-semibold">Pengaturan</p>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Sales Chart
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($salesLastWeek['dates'] ?? ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min']) !!},
            datasets: [{
                label: 'Penjualan (Rp)',
                data: {!! json_encode($salesLastWeek['amounts'] ?? [50000, 75000, 60000, 90000, 85000, 120000, 95000]) !!},
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + (value/1000) + 'k';
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
</div>
