<div class="rounded border bg-white">
    <div class="border-b px-4 py-3 font-semibold">Seller</div>
    <div class="p-2">
        @php
            $productsHref = \Illuminate\Support\Facades\Route::has('seller.products.index')
                ? route('seller.products.index')
                : url('/seller/products');
        @endphp

        <a class="block rounded px-3 py-2 text-sm hover:bg-gray-50" href="{{ $productsHref }}">Produk Saya</a>
    </div>
</div>
