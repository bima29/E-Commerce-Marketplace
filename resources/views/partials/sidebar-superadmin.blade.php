<div class="rounded-2xl border border-gray-200 bg-white overflow-hidden shadow-sm">
    <div class="border-b border-gray-200 px-5 py-4">
        <div class="font-bold text-gray-900">Superadmin</div>
        <div class="text-xs text-gray-500">Kelola seller, toko, dan produk.</div>
    </div>
    <div class="p-2">
        @php
            $sellersHref = \Illuminate\Support\Facades\Route::has('superadmin.sellers.index')
                ? route('superadmin.sellers.index')
                : url('/superadmin/sellers');

            $productsHref = \Illuminate\Support\Facades\Route::has('superadmin.products.index')
                ? route('superadmin.products.index')
                : url('/superadmin/products');

            $isSellersActive = request()->routeIs('superadmin.sellers.*');
            $isProductsActive = request()->routeIs('superadmin.products.*');

            $linkBase = 'flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition-colors';
            $linkActive = 'bg-primary-50 text-primary-900';
            $linkInactive = 'text-gray-700 hover:bg-gray-50 hover:text-gray-900';
        @endphp

        <a class="{{ $linkBase }} {{ $isSellersActive ? $linkActive : $linkInactive }}" href="{{ $sellersHref }}">
            <i class="fas fa-store"></i>
            <span>Sellers / Toko</span>
        </a>
        <a class="mt-1 {{ $linkBase }} {{ $isProductsActive ? $linkActive : $linkInactive }}" href="{{ $productsHref }}">
            <i class="fas fa-box"></i>
            <span>Produk</span>
        </a>
    </div>
</div>
