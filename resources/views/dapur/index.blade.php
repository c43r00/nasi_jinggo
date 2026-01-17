@extends('layouts.app')

@section('title', 'Dashboard Dapur')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50 py-8">
    <div class="container mx-auto px-4">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl shadow-2xl p-8 mb-8 text-white">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-4xl font-bold mb-2">Dashboard Dapur</h1>
                </div>
                
                <!-- Logo/Avatar Staff -->
                <div class="flex items-center gap-3">
                    <div class="bg-white rounded-full p-2 shadow-lg">
                        <svg class="w-8 h-8 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-lg">Made Sari</p>
                        <p class="text-sm text-blue-100">Staff Dapur</p>
                    </div>
                </div>
            </div>
            
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
                <div class="bg-red-500 bg-opacity-90 rounded-xl p-6 hover:bg-opacity-100 transition duration-300 cursor-pointer">
                    <div class="flex items-center gap-4">
                        <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-red-100 text-sm">Stok Menipis</p>
                            <p class="text-4xl font-bold">{{ $stockMenipis }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-green-500 bg-opacity-90 rounded-xl p-6 hover:bg-opacity-100 transition duration-300 cursor-pointer">
                    <div class="flex items-center gap-4">
                        <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-green-100 text-sm">Normal</p>
                            <p class="text-4xl font-bold">{{ $normal }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <!-- Header Section dengan Export PDF dan Input Stok -->
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-bold text-gray-800">Stok Bahan Baku</h2>
                
                <!-- Tombol Export PDF dan Input Stok -->
                <div class="flex gap-4">
                    <a href="/dapur/export-pdf" 
                       class="bg-red-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-red-700 transition duration-300 flex items-center gap-2 shadow-lg hover:shadow-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Export PDF
                    </a>
                    <button onclick="openModal()" 
                            class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition duration-300 flex items-center gap-2 shadow-lg hover:shadow-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Input Stok Masuk
                    </button>
                </div>
            </div>

            @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg" role="alert">
                <p class="font-semibold">Berhasil!</p>
                <p>{{ session('success') }}</p>
            </div>
            @endif

            @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg" role="alert">
                <p class="font-semibold">Error!</p>
                <p>{{ session('error') }}</p>
            </div>
            @endif

            <!-- Filter Tabs -->
            <div class="mb-8">
                <div class="flex gap-4 border-b">
                    <button onclick="filterStatus('all')" 
                            class="tab-btn px-6 py-3 font-semibold text-gray-600 hover:text-blue-600 border-b-2 border-transparent hover:border-blue-600 transition duration-300 active">
                        Semua Bahan
                    </button>
                    <button onclick="filterStatus('stok_menipis')" 
                            class="tab-btn px-6 py-3 font-semibold text-gray-600 hover:text-red-600 border-b-2 border-transparent hover:border-red-600 transition duration-300">
                        Stok Menipis
                    </button>
                    <button onclick="filterStatus('normal')" 
                            class="tab-btn px-6 py-3 font-semibold text-gray-600 hover:text-green-600 border-b-2 border-transparent hover:border-green-600 transition duration-300">
                        Normal
                    </button>
                </div>
            </div>

            <!-- Daftar Bahan -->
            <div class="space-y-4">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Daftar Bahan</h3>
                
                @if(isset($groupedIngredients['stok_menipis']) && $groupedIngredients['stok_menipis']->count() > 0)
                <div class="stok-menipis-section mb-8">
                    @foreach($groupedIngredients['stok_menipis'] as $item)
                    <div class="ingredient-card bg-red-50 border-l-4 border-red-500 rounded-lg p-6 mb-4 hover:shadow-lg transition duration-300" data-status="stok_menipis">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <h4 class="text-xl font-bold text-gray-800">{{ $item['ingredient']->name }}</h4>
                                    <span class="bg-red-500 text-white text-xs px-3 py-1 rounded-full font-semibold">Stok Menipis</span>
                                </div>
                                <p class="text-gray-600 mb-2">{{ $item['ingredient']->category->name ?? 'Tanpa Kategori' }}</p>
                                <p class="text-sm text-gray-500 mb-4">Supplier: {{ $item['ingredient']->supplier_name }}</p>
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-600">Stok Saat Ini</p>
                                        <p class="text-2xl font-bold text-red-600">{{ $item['ingredient']->stock_quantity }} {{ $item['ingredient']->unit }}</p>
                                        <p class="text-xs text-gray-500">Min: {{ $item['ingredient']->minimum_stock }} {{ $item['ingredient']->unit }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Harga per {{ $item['ingredient']->unit }}</p>
                                        <p class="text-lg font-semibold text-gray-800">Rp {{ number_format($item['ingredient']->price_per_unit, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif

                @if(isset($groupedIngredients['normal']) && $groupedIngredients['normal']->count() > 0)
                <div class="normal-section">
                    @foreach($groupedIngredients['normal'] as $item)
                    <div class="ingredient-card bg-green-50 border-l-4 border-green-500 rounded-lg p-6 mb-4 hover:shadow-lg transition duration-300" data-status="normal">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <h4 class="text-xl font-bold text-gray-800">{{ $item['ingredient']->name }}</h4>
                                    <span class="bg-green-500 text-white text-xs px-3 py-1 rounded-full font-semibold">Normal</span>
                                </div>
                                <p class="text-gray-600 mb-2">{{ $item['ingredient']->category->name ?? 'Tanpa Kategori' }}</p>
                                <p class="text-sm text-gray-500 mb-4">Supplier: {{ $item['ingredient']->supplier_name }}</p>
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-600">Stok Saat Ini</p>
                                        <p class="text-2xl font-bold text-green-600">{{ $item['ingredient']->stock_quantity }} {{ $item['ingredient']->unit }}</p>
                                        <p class="text-xs text-gray-500">Min: {{ $item['ingredient']->minimum_stock }} {{ $item['ingredient']->unit }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Harga per {{ $item['ingredient']->unit }}</p>
                                        <p class="text-lg font-semibold text-gray-800">Rp {{ number_format($item['ingredient']->price_per_unit, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal Input Stok -->
<div id="modalInput" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-2xl font-bold text-gray-800">Input Stok Masuk</h3>
                <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700 transition duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form action="{{ route('dapur.store') }}" method="POST" class="space-y-4">
                @csrf
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Pembelian *</label>
                        <input type="date" name="purchase_date" required 
                               value="{{ old('purchase_date', date('Y-m-d')) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300">
                        @error('purchase_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Kategori *</label>
                        <select name="category_id" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300">
                            <option value="">Pilih Kategori</option>
                            @php
                                $categories = \App\Models\Category::all();
                            @endphp
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Bahan *</label>
                    <input type="text" name="ingredient_name" required 
                           value="{{ old('ingredient_name') }}"
                           placeholder="Contoh: Tepung Terigu, Gula Pasir"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300">
                    @error('ingredient_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Jumlah (Kg) *</label>
                        <input type="number" name="quantity" required step="0.01" min="0"
                               value="{{ old('quantity') }}"
                               placeholder="Contoh: 25"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300">
                        @error('quantity')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Harga per Kg *</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2.5 text-gray-500 text-sm">Rp</span>
                            <input type="number" name="price_per_unit" required step="0.01" min="0"
                                   value="{{ old('price_per_unit') }}"
                                   placeholder="Contoh: 15000"
                                   class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300">
                        </div>
                        @error('price_per_unit')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Supplier *</label>
                    <input type="text" name="supplier_name" required
                           value="{{ old('supplier_name') }}"
                           placeholder="Contoh: Toko Bahan Masak"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300">
                    @error('supplier_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex gap-4 pt-2">
                    <button type="submit" 
                            class="flex-1 bg-blue-600 text-white px-6 py-2.5 rounded-lg font-semibold hover:bg-blue-700 transition duration-300 shadow-lg hover:shadow-xl">
                        Simpan Data Stok
                    </button>
                    <button type="button" onclick="resetForm()" 
                            class="flex-1 bg-gray-200 text-gray-700 px-6 py-2.5 rounded-lg font-semibold hover:bg-gray-300 transition duration-300">
                        Reset Form
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openModal() {
    document.getElementById('modalInput').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    document.getElementById('modalInput').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function resetForm() {
    document.querySelector('form').reset();
}

function filterStatus(status) {
    const cards = document.querySelectorAll('.ingredient-card');
    const tabs = document.querySelectorAll('.tab-btn');
    
    tabs.forEach(tab => {
        tab.classList.remove('active', 'border-blue-600', 'text-blue-600', 'border-red-600', 'text-red-600', 'border-green-600', 'text-green-600');
    });
    event.target.classList.add('active');
    
    if (status === 'all') {
        cards.forEach(card => card.style.display = 'block');
        event.target.classList.add('border-blue-600', 'text-blue-600');
    } else {
        cards.forEach(card => {
            if (card.dataset.status === status) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
        
        if (status === 'stok_menipis') {
            event.target.classList.add('border-red-600', 'text-red-600');
        } else if (status === 'normal') {
            event.target.classList.add('border-green-600', 'text-green-600');
        }
    }
}

document.getElementById('modalInput').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
    }
});
</script>
@endsection