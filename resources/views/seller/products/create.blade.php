@extends('layouts.seller')

@section('title', 'Tambah Produk')

@section('content')
    @php
        $storeAction = \Illuminate\Support\Facades\Route::has('seller.products.store')
            ? route('seller.products.store')
            : url('/seller/products');

        $backHref = \Illuminate\Support\Facades\Route::has('seller.products.index')
            ? route('seller.products.index')
            : url('/seller/products');
    @endphp

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-semibold">Tambah Produk</h1>
        <a href="{{ $backHref }}" class="text-sm text-gray-600 hover:text-gray-900">Kembali</a>
    </div>

    <div class="rounded border bg-white p-6">
        <form method="POST" action="{{ $storeAction }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700">Nama Produk</label>
                <input name="name" value="{{ old('name') }}" class="mt-1 w-full rounded border-gray-300 focus:border-gray-900 focus:ring-gray-900" placeholder="Contoh: Kaos Polos" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Harga</label>
                    <input type="number" step="0.01" name="price" value="{{ old('price') }}" class="mt-1 w-full rounded border-gray-300 focus:border-gray-900 focus:ring-gray-900" placeholder="10000" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Stok</label>
                    <input type="number" name="stock" value="{{ old('stock') }}" class="mt-1 w-full rounded border-gray-300 focus:border-gray-900 focus:ring-gray-900" placeholder="0" />
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Status Produk</label>
                <select name="status" class="mt-1 w-full rounded border-gray-300 focus:border-gray-900 focus:ring-gray-900">
                    <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <div class="pt-2">
                <button type="submit" class="inline-flex items-center rounded bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800">
                    Simpan
                </button>
            </div>
        </form>

        <div class="mt-4 text-xs text-gray-500">
            <div>Catatan business rule diproses di backend:</div>
            <div>- Seller nonaktif tidak boleh menambah produk</div>
            <div>- Harga harus &gt; 0</div>
            <div>- Stok tidak boleh negatif</div>
        </div>
    </div>
@endsection
