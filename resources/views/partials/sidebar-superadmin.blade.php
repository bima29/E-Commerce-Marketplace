<div class="rounded border bg-white">
    <div class="border-b px-4 py-3 font-semibold">Superadmin</div>
    <div class="p-2">
        @php
            $sellersHref = \Illuminate\Support\Facades\Route::has('superadmin.sellers.index')
                ? route('superadmin.sellers.index')
                : url('/superadmin/sellers');

            $productsHref = \Illuminate\Support\Facades\Route::has('superadmin.products.index')
                ? route('superadmin.products.index')
                : url('/superadmin/products');
        @endphp

        <a class="block rounded px-3 py-2 text-sm hover:bg-gray-50" href="{{ $sellersHref }}">Sellers</a>
        <a class="block rounded px-3 py-2 text-sm hover:bg-gray-50" href="{{ $productsHref }}">Products</a>
    </div>
</div>
