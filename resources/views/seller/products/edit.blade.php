@extends('layouts.seller')

@section('title', 'Edit Produk')

@section('content')
    @php
        $updateAction = \Illuminate\Support\Facades\Route::has('seller.products.update')
            ? route('seller.products.update', ['product' => $product->id])
            : url('/seller/products/' . ($product->id ?? 0));

        $backHref = \Illuminate\Support\Facades\Route::has('seller.products.index')
            ? route('seller.products.index')
            : url('/seller/products');
    @endphp

    <div class="rounded-2xl bg-gradient-to-r from-primary-900 via-primary-800 to-primary-700 p-6 md:p-8 mb-6 shadow-xl">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-white">Edit Produk</h1>
                <p class="text-sm text-primary-100">Perbarui informasi produk kamu.</p>
            </div>
            <a href="{{ $backHref }}" class="inline-flex items-center justify-center rounded-lg bg-white text-primary-900 hover:bg-gray-100 px-4 py-2 text-sm font-semibold">
                Kembali
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
        <form method="POST" action="{{ $updateAction }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-semibold text-gray-700">Nama Produk</label>
                <input
                    name="name"
                    value="{{ old('name', $product->name ?? '') }}"
                    class="mt-2 w-full rounded-lg border-gray-300 focus:border-primary-900 focus:ring-primary-900"
                    placeholder="Contoh: Kaos Polos"
                />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700">Harga</label>
                    <input
                        type="number"
                        step="0.01"
                        name="price"
                        value="{{ old('price', $product->price ?? '') }}"
                        class="mt-2 w-full rounded-lg border-gray-300 focus:border-primary-900 focus:ring-primary-900"
                        placeholder="10000"
                    />
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700">Stok</label>
                    <input
                        type="number"
                        name="stock"
                        value="{{ old('stock', $product->stock ?? '') }}"
                        class="mt-2 w-full rounded-lg border-gray-300 focus:border-primary-900 focus:ring-primary-900"
                        placeholder="0"
                    />
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700">Status Produk</label>
                <select name="status" class="mt-2 w-full rounded-lg border-gray-300 focus:border-primary-900 focus:ring-primary-900">
                    <option value="active" {{ old('status', $product->status ?? 'active') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ old('status', $product->status ?? 'active') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700">Deskripsi</label>
                <textarea
                    name="description"
                    rows="4"
                    class="mt-2 w-full rounded-lg border-gray-300 focus:border-primary-900 focus:ring-primary-900"
                    placeholder="Deskripsi singkat produk"
                >{{ old('description', $product->description ?? '') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700">Image URL</label>
                <input
                    name="image_url"
                    value="{{ old('image_url', $product->image_url ?? '') }}"
                    class="mt-2 w-full rounded-lg border-gray-300 focus:border-primary-900 focus:ring-primary-900"
                    placeholder="https://..."
                />
            </div>

            <div class="pt-2">
                <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-primary-900 hover:bg-primary-800 text-white px-5 py-2 text-sm font-semibold">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
@endsection
