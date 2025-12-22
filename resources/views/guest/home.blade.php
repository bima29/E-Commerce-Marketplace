@extends('layouts.app')

@section('title', 'Marketplace - Temukan Produk Terbaik')

@section('content')
<div class="container mx-auto px-4 lg:px-8 py-8">
    {{-- Hero Section --}}
    <div class="rounded-2xl bg-gradient-to-r from-primary-900 via-primary-800 to-primary-700 p-6 md:p-10 mb-8 md:mb-12 shadow-xl">
        <div class="max-w-2xl">
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">Temukan Produk Terbaik</h1>
            <p class="text-lg text-primary-100 mb-6">Marketplace terpercaya dengan ribuan produk dari seller berkualitas</p>
            
            {{-- Search Bar --}}
            <div class="relative max-w-xl">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input 
                    type="text" 
                    placeholder="Cari produk terbaik..."
                    class="w-full pl-12 pr-4 py-3 rounded-lg bg-white/10 backdrop-blur-sm border border-white/20 text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-transparent"
                >
                <button class="absolute right-2 top-2 bg-white text-primary-900 hover:bg-gray-100 px-4 py-1.5 rounded-md font-medium transition-colors">
                    Cari
                </button>
            </div>
        </div>
    </div>

    {{-- Products Grid --}}
    <div class="mb-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Produk Terbaru</h2>
            <div class="flex space-x-2">
                <button class="px-4 py-2 text-sm font-medium text-primary-900 bg-primary-50 rounded-lg hover:bg-primary-100">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
                <button class="px-4 py-2 text-sm font-medium text-primary-900 bg-primary-50 rounded-lg hover:bg-primary-100">
                    <i class="fas fa-sort mr-2"></i>Urutkan
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse ($products ?? [] as $product)
                <div class="group bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                    {{-- Product Image Placeholder --}}
                    <div class="h-48 bg-gradient-to-br from-primary-50 to-primary-100 flex items-center justify-center relative overflow-hidden">
                        <div class="text-primary-900 opacity-20">
                            <i class="fas fa-shopping-bag text-6xl"></i>
                        </div>
                        @if($product->stock > 0)
                            <span class="absolute top-3 right-3 bg-green-100 text-green-800 text-xs font-semibold px-3 py-1 rounded-full">
                                <i class="fas fa-check-circle mr-1"></i>Tersedia
                            </span>
                        @else
                            <span class="absolute top-3 right-3 bg-red-100 text-red-800 text-xs font-semibold px-3 py-1 rounded-full">
                                <i class="fas fa-times-circle mr-1"></i>Habis
                            </span>
                        @endif
                    </div>
                    
                    <div class="p-5">
                        {{-- Product Info --}}
                        <div class="mb-3">
                            <h3 class="font-bold text-gray-900 group-hover:text-primary-900 line-clamp-2 mb-2">
                                {{ $product->name ?? 'Nama Produk' }}
                            </h3>
                            <p class="text-sm text-gray-500 mb-3 line-clamp-2">
                                {{ $product->description ?? 'Deskripsi produk' }}
                            </p>
                        </div>
                        
                        {{-- Seller Info --}}
                        <div class="flex items-center mb-4">
                            <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center mr-3">
                                <i class="fas fa-store text-primary-900 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Seller</p>
                                <p class="text-sm font-medium text-gray-900">{{ $product->seller->name ?? 'Seller' }}</p>
                            </div>
                        </div>
                        
                        {{-- Price & Stock --}}
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <p class="text-2xl font-bold text-primary-900">
                                    Rp {{ number_format($product->price ?? 0, 0, ',', '.') }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    Stok: <span class="font-semibold {{ $product->stock > 10 ? 'text-green-600' : ($product->stock > 0 ? 'text-yellow-600' : 'text-red-600') }}">
                                        {{ $product->stock ?? 0 }}
                                    </span>
                                </p>
                            </div>
                            <div class="text-right">
                                <div class="flex items-center text-yellow-400">
                                    <i class="fas fa-star text-sm"></i>
                                    <span class="ml-1 text-sm font-medium text-gray-700">4.8</span>
                                </div>
                                <p class="text-xs text-gray-500">Terjual 250+</p>
                            </div>
                        </div>
                        
                        {{-- Add to Cart Form --}}
                        @php
                            $addAction = Route::has('cart.add') 
                                ? route('cart.add', ['product' => $product->id]) 
                                : url('/cart/add/' . ($product->id ?? 0));
                        @endphp
                        
                        <form method="POST" action="{{ $addAction }}" class="space-y-3">
                            @csrf
                            <div class="flex items-center space-x-3">
                                <div class="flex-1">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                                    <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden">
                                        <button type="button" class="decrement-btn px-3 py-2 bg-gray-100 hover:bg-gray-200 border-r">
                                            <i class="fas fa-minus text-gray-600"></i>
                                        </button>
                                        <input
                                            type="number"
                                            name="qty"
                                            min="1"
                                            max="{{ $product->stock ?? 1 }}"
                                            value="1"
                                            class="w-full text-center border-0 focus:ring-0 focus:outline-none py-2"
                                        >
                                        <button type="button" class="increment-btn px-3 py-2 bg-gray-100 hover:bg-gray-200 border-l">
                                            <i class="fas fa-plus text-gray-600"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <button
                                        type="submit"
                                        {{ ($product->stock ?? 0) <= 0 ? 'disabled' : '' }}
                                        class="w-full bg-gradient-to-r from-primary-900 to-primary-800 hover:from-primary-800 hover:to-primary-700 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed"
                                    >
                                        <i class="fas fa-cart-plus mr-2"></i>
                                        {{ ($product->stock ?? 0) > 0 ? 'Tambah' : 'Habis' }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="bg-white rounded-2xl border border-gray-200 p-12 text-center">
                        <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-primary-50 flex items-center justify-center">
                            <i class="fas fa-box-open text-primary-900 text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Belum ada produk</h3>
                        <p class="text-gray-600 mb-6">Produk akan segera tersedia</p>
                        <button class="bg-primary-900 hover:bg-primary-800 text-white font-medium px-6 py-3 rounded-lg transition-colors">
                            <i class="fas fa-redo-alt mr-2"></i>Refresh Halaman
                        </button>
                    </div>
                </div>
            @endforelse
        </div>
        
        {{-- Pagination --}}
        @if(!empty($products) && count($products) > 0)
            <div class="mt-10 flex justify-center">
                <nav class="flex items-center space-x-2">
                    <button class="w-10 h-10 flex items-center justify-center rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-50">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="w-10 h-10 flex items-center justify-center rounded-lg bg-primary-900 text-white font-medium">1</button>
                    <button class="w-10 h-10 flex items-center justify-center rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-50">2</button>
                    <button class="w-10 h-10 flex items-center justify-center rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-50">3</button>
                    <span class="px-2 text-gray-400">...</span>
                    <button class="w-10 h-10 flex items-center justify-center rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-50">10</button>
                    <button class="w-10 h-10 flex items-center justify-center rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-50">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </nav>
            </div>
        @endif
    </div>
</div>

{{-- Quantity Script --}}
<script>
    document.querySelectorAll('.increment-btn').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.parentNode.querySelector('input[name="qty"]');
            const max = parseInt(input.getAttribute('max'));
            let value = parseInt(input.value);
            if (value < max) input.value = value + 1;
        });
    });

    document.querySelectorAll('.decrement-btn').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.parentNode.querySelector('input[name="qty"]');
            const min = parseInt(input.getAttribute('min'));
            let value = parseInt(input.value);
            if (value > min) input.value = value - 1;
        });
    });
</script>

<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    /* Hide number input arrows */
    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    input[type="number"] {
        -moz-appearance: textfield;
    }
</style>
@endsection