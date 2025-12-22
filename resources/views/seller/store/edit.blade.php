@extends('layouts.seller')

@section('title', 'Mengelola Toko')

@section('content')
    @php
        $updateAction = \Illuminate\Support\Facades\Route::has('seller.store.update')
            ? route('seller.store.update')
            : url('/seller/store');

        $productsHref = \Illuminate\Support\Facades\Route::has('seller.products.index')
            ? route('seller.products.index')
            : url('/seller/products');
    @endphp

    <div class="rounded-2xl bg-gradient-to-r from-primary-900 via-primary-800 to-primary-700 p-6 md:p-8 mb-6 shadow-xl">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-white">Mengelola Toko</h1>
                <p class="text-sm text-primary-100">Atur identitas toko kamu.</p>
            </div>
            <a href="{{ $productsHref }}" class="inline-flex items-center justify-center rounded-lg bg-white text-primary-900 hover:bg-gray-100 px-4 py-2 text-sm font-semibold">
                Kembali ke Produk
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
        <form method="POST" action="{{ $updateAction }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-semibold text-gray-700">Nama Toko</label>
                <input
                    name="name"
                    value="{{ old('name', $seller->name ?? '') }}"
                    class="mt-2 w-full rounded-lg border-gray-300 focus:border-primary-900 focus:ring-primary-900"
                    placeholder="Contoh: Toko Andalan"
                />
            </div>

            <div class="flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between pt-2">
                <div class="text-xs text-gray-500">
                    Email toko: <span class="font-semibold text-gray-700">{{ $seller->email ?? '-' }}</span>
                </div>
                <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-primary-900 hover:bg-primary-800 text-white px-5 py-2 text-sm font-semibold">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
@endsection
