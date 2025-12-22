@extends('layouts.app')

@section('title', 'Marketplace - Temukan Produk Terbaik')

@section('content')
@php
    $q = $q ?? request('q', '');
    $availability = $availability ?? request('availability', 'all');
    $sort = $sort ?? request('sort', 'newest');
    $hasQuery = (string)$q !== '' || (string)$availability !== 'all' || (string)$sort !== 'newest';
@endphp

<div class="container mx-auto px-4 lg:px-8 py-8">
    {{-- Hero Section --}}
    <div class="rounded-2xl bg-gradient-to-r from-primary-900 via-primary-800 to-primary-700 p-6 md:p-10 mb-8 md:mb-12 shadow-xl">
        <div class="max-w-2xl">
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">Temukan Produk Terbaik</h1>
            <p class="text-lg text-primary-100 mb-6">Marketplace terpercaya dengan ribuan produk dari seller berkualitas</p>
            
            <form method="GET" action="{{ Route::has('home') ? route('home') : url('/') }}" class="space-y-3">
                {{-- Search Bar --}}
                <div class="relative max-w-xl">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-300"></i>
                    </div>
                    <input
                        type="text"
                        name="q"
                        value="{{ $q }}"
                        placeholder="Cari produk (contoh: kaos, sepatu, hoodie)"
                        class="w-full pl-12 pr-4 py-3 rounded-lg bg-white/10 backdrop-blur-sm border border-white/20 text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-transparent"
                    >
                </div>

                {{-- Inline controls (mobile friendly) --}}
                <div class="max-w-xl grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-white/80 mb-1">Ketersediaan</label>
                        <select name="availability" class="w-full rounded-lg bg-white/90 border border-white/20 text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-white/50">
                            <option value="all" {{ $availability === 'all' ? 'selected' : '' }}>Semua</option>
                            <option value="in_stock" {{ $availability === 'in_stock' ? 'selected' : '' }}>Tersedia</option>
                            <option value="out_of_stock" {{ $availability === 'out_of_stock' ? 'selected' : '' }}>Habis</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-white/80 mb-1">Urutkan</label>
                        <select name="sort" class="w-full rounded-lg bg-white/90 border border-white/20 text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-white/50">
                            <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>Terbaru</option>
                            <option value="oldest" {{ $sort === 'oldest' ? 'selected' : '' }}>Terlama</option>
                            <option value="price_asc" {{ $sort === 'price_asc' ? 'selected' : '' }}>Harga Termurah</option>
                            <option value="price_desc" {{ $sort === 'price_desc' ? 'selected' : '' }}>Harga Termahal</option>
                            <option value="name_asc" {{ $sort === 'name_asc' ? 'selected' : '' }}>Nama A-Z</option>
                            <option value="name_desc" {{ $sort === 'name_desc' ? 'selected' : '' }}>Nama Z-A</option>
                        </select>
                    </div>
                </div>

                <div class="max-w-xl">
                    <button type="submit" class="w-full sm:w-auto bg-white text-primary-900 hover:bg-gray-100 px-6 py-3 rounded-lg font-semibold transition-colors">
                        Cari
                    </button>
                </div>

                @if ($hasQuery)
                    <div class="max-w-xl">
                        <a href="{{ Route::has('home') ? route('home') : url('/') }}" class="inline-flex items-center text-sm font-semibold text-white/90 hover:text-white">
                            <i class="fas fa-xmark mr-2"></i>
                            Reset filter
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    {{-- Products Grid --}}
    <div class="mb-8">
        <div class="flex flex-col gap-3 sm:flex-row sm:justify-between sm:items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Produk Terbaru</h2>
            <div class="text-sm text-gray-600">
                @if ($hasQuery)
                    Menampilkan hasil untuk:
                    @if ((string)$q !== '')
                        <span class="font-semibold text-primary-900">"{{ $q }}"</span>
                    @endif
                @else
                    Jelajahi produk terbaru.
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse ($products ?? [] as $product)
                <div class="group bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                    {{-- Product Image Placeholder --}}
                    <div class="h-48 bg-gradient-to-br from-primary-50 to-primary-100 flex items-center justify-center relative overflow-hidden">
                        @if(!empty($product->image_url))
                            <img src="{{ $product->image_url }}" alt="{{ $product->name ?? 'Produk' }}" class="h-full w-full object-cover" />
                        @else
                            <div class="text-primary-900 opacity-20">
                                <i class="fas fa-shopping-bag text-6xl"></i>
                            </div>
                        @endif
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
                                    <span class="ml-1 text-sm font-medium text-gray-700">{{ number_format((float)($product->rating_avg ?? 0), 1) }}</span>
                                </div>
                                <p class="text-xs text-gray-500">Terjual {{ number_format((int)($product->sold_count ?? 0), 0, ',', '.') }}</p>
                            </div>
                        </div>
                        
                        {{-- Add to Cart Form --}}
                        @php
                            $addAction = Route::has('cart.add') 
                                ? route('cart.add', ['product' => $product->id]) 
                                : url('/cart/add/' . ($product->id ?? 0));
                        @endphp
                        
                        <form method="POST" action="{{ $addAction }}" class="space-y-3 js-add-to-cart">
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
                        @if ($hasQuery)
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Tidak ada hasil</h3>
                            <p class="text-gray-600 mb-6">Coba ubah kata kunci, filter, atau urutan.</p>
                            <a href="{{ Route::has('home') ? route('home') : url('/') }}" class="inline-flex items-center bg-primary-900 hover:bg-primary-800 text-white font-medium px-6 py-3 rounded-lg transition-colors">
                                <i class="fas fa-rotate-left mr-2"></i>Reset Filter
                            </a>
                        @else
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Belum ada produk</h3>
                            <p class="text-gray-600 mb-6">Produk akan segera tersedia. Kamu tetap bisa coba fitur search dan urutkan.</p>
                            <a href="{{ Route::has('home') ? route('home') : url('/') }}" class="inline-flex items-center bg-primary-900 hover:bg-primary-800 text-white font-medium px-6 py-3 rounded-lg transition-colors">
                                <i class="fas fa-rotate mr-2"></i>Refresh
                            </a>
                        @endif
                    </div>
                </div>
            @endforelse
        </div>
        
        {{-- Pagination --}}
        @if(!empty($products) && method_exists($products, 'links'))
            <div class="mt-10 flex justify-center">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</div>

<div id="js-toast-container" class="fixed top-5 right-5 space-y-3" style="z-index: 9999;"></div>

{{-- Quantity Script --}}
<script>
    const showToast = (message) => {
        let container = document.getElementById('js-toast-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'js-toast-container';
            container.className = 'fixed top-5 right-5 space-y-3';
            container.style.zIndex = '9999';
            document.body.appendChild(container);
        }

        const toast = document.createElement('div');
        toast.className = 'w-full max-w-sm rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-800 shadow-lg transition-opacity duration-200 opacity-100';
        toast.innerHTML = `
            <div class="flex items-start justify-between gap-4">
                <div class="text-sm font-semibold">${message}</div>
                <button type="button" class="-mr-1 -mt-1 inline-flex h-7 w-7 items-center justify-center rounded-md text-green-800/70 hover:text-green-900 hover:bg-green-100" aria-label="Tutup">
                    &times;
                </button>
            </div>
        `;

        const removeToast = () => {
            toast.classList.remove('opacity-100');
            toast.classList.add('opacity-0');
            window.setTimeout(() => toast.remove(), 200);
        };

        const closeBtn = toast.querySelector('button');
        if (closeBtn) {
            closeBtn.addEventListener('click', removeToast);
        }

        container.appendChild(toast);
        window.setTimeout(removeToast, 5000);
    };

    window.showToast = showToast;

    document.querySelectorAll('form.js-add-to-cart').forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            const submitBtn = form.querySelector('button[type="submit"]');
            const originalHtml = submitBtn ? submitBtn.innerHTML : null;
            if (submitBtn) {
                submitBtn.disabled = true;
            }

            try {
                const formData = new FormData(form);

                const res = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                    body: formData,
                });

                const payload = await res.json().catch(() => null);
                if (!res.ok) {
                    const msg = payload?.message || 'Gagal menambahkan ke keranjang';
                    alert(msg);
                    return;
                }

                if (typeof payload?.cartCount !== 'undefined') {
                    document.querySelectorAll('[data-cart-count-badge]').forEach(el => {
                        el.textContent = payload.cartCount;
                    });
                }

                showToast('Berhasil masuk keranjang');
            } catch (err) {
                alert('Terjadi kesalahan. Coba lagi.');
            } finally {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    if (originalHtml !== null) {
                        submitBtn.innerHTML = originalHtml;
                    }
                }
            }
        });
    });

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