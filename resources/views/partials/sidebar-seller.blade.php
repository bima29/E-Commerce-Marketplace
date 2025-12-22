<div class="rounded-2xl border border-gray-200 bg-white overflow-hidden shadow-sm">
    <div class="border-b border-gray-200 px-5 py-4">
        <div class="font-bold text-gray-900">Seller</div>
        <div class="text-xs text-gray-500">Kelola toko & produk kamu.</div>
    </div>

    <div class="p-2">
        @php
            $productsHref = \Illuminate\Support\Facades\Route::has('seller.products.index')
                ? route('seller.products.index')
                : url('/seller/products');

            $storeHref = \Illuminate\Support\Facades\Route::has('seller.store.edit')
                ? route('seller.store.edit')
                : url('/seller/store');

            $isProductsActive = request()->routeIs('seller.products.*');
            $isStoreActive = request()->routeIs('seller.store.*');

            $linkBase = 'flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition-colors';
            $linkActive = 'bg-primary-50 text-primary-900';
            $linkInactive = 'text-gray-700 hover:bg-gray-50 hover:text-gray-900';
        @endphp

        <a class="{{ $linkBase }} {{ $isStoreActive ? $linkActive : $linkInactive }}" href="{{ $storeHref }}">
            <i class="fas fa-shop"></i>
            <span>Mengelola Toko</span>
        </a>

        <a class="mt-1 {{ $linkBase }} {{ $isProductsActive ? $linkActive : $linkInactive }}" href="{{ $productsHref }}">
            <i class="fas fa-box"></i>
            <span>Produk Saya</span>
        </a>
    </div>
</div>
