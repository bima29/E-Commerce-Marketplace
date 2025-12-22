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

    <div class="mb-6 md:mb-8">
        <div class="rounded-2xl bg-gradient-to-r from-primary-900 via-primary-800 to-primary-700 p-6 md:p-8 shadow-xl overflow-hidden relative">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -translate-y-16 translate-x-8"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/5 rounded-full translate-y-8 -translate-x-8"></div>

            <div class="relative z-10">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="space-y-2">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-white/10 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V5a2 2 0 00-2-2H6a2 2 0 00-2 2v8m16 0a2 2 0 01-2 2H8a2 2 0 01-2-2m14 0v6a2 2 0 01-2 2H8a2 2 0 01-2-2v-6m6 3h4"/>
                                </svg>
                            </div>
                            <h1 class="text-2xl md:text-3xl font-bold text-white">Tambah Produk Baru</h1>
                        </div>
                        <p class="text-sm text-primary-100 max-w-2xl">Tambahkan produk baru untuk toko kamu.</p>
                    </div>
                    <a href="{{ $backHref }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-white/10 hover:bg-white/20 backdrop-blur-sm border border-white/20 text-white hover:text-white px-5 py-3 text-sm font-medium transition-all duration-200 hover:scale-[1.02]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Kembali ke Daftar
                    </a>
                </div>
            </div>
        </div>

        <div class="mt-6 px-2">
            <div class="flex items-center justify-center">
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-full bg-primary-900 text-white flex items-center justify-center">
                        <span class="text-sm font-semibold">1</span>
                    </div>
                    <div class="w-16 md:w-24 h-1 bg-primary-900"></div>
                    <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center">
                        <span class="text-sm font-semibold">2</span>
                    </div>
                </div>
            </div>
            <div class="flex justify-between text-xs text-gray-600 mt-2 px-2">
                <span class="font-medium text-primary-900">Informasi Produk</span>
                <span>Verifikasi Data</span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 p-6 md:p-8 shadow-sm mb-8">
        <div class="mb-8 pb-6 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-primary-50 rounded-lg">
                    <svg class="w-5 h-5 text-primary-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V5a2 2 0 00-2-2H6a2 2 0 00-2 2v8m16 0a2 2 0 01-2 2H8a2 2 0 01-2-2m14 0v6a2 2 0 01-2 2H8a2 2 0 01-2-2v-6m6 3h4"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Informasi Produk</h2>
                    <p class="text-sm text-gray-600">Lengkapi data produk untuk toko kamu</p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ $storeAction }}" class="space-y-8">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="space-y-6">
                    <div class="group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <svg class="w-4 h-4 text-primary-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V5a2 2 0 00-2-2H6a2 2 0 00-2 2v8m16 0a2 2 0 01-2 2H8a2 2 0 01-2-2m14 0v6a2 2 0 01-2 2H8a2 2 0 01-2-2v-6m6 3h4"/>
                            </svg>
                            Nama Produk
                        </label>
                        <div class="relative">
                            <input
                                name="name"
                                value="{{ old('name') }}"
                                class="mt-1 w-full rounded-lg border-gray-300 focus:border-primary-900 focus:ring-2 focus:ring-primary-900/20 px-4 py-3 transition-all duration-200 group-hover:border-primary-500"
                                placeholder="Contoh: Kaos Polos"
                            />
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                        </div>
                        <p class="mt-2 text-xs text-gray-500">Nama produk yang akan tampil di halaman marketplace</p>
                    </div>

                    <div class="group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <svg class="w-4 h-4 text-primary-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4m10 0h4m-2-2v4M4 17l6-6 4 4 7-7"/>
                            </svg>
                            Image URL
                        </label>
                        <input
                            name="image_url"
                            value="{{ old('image_url') }}"
                            class="mt-1 w-full rounded-lg border-gray-300 focus:border-primary-900 focus:ring-2 focus:ring-primary-900/20 px-4 py-3 transition-all duration-200 group-hover:border-primary-500"
                            placeholder="https://..."
                        />
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                <svg class="w-4 h-4 text-primary-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-10V6m0 12v-2m9-4a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Harga
                            </label>
                            <input
                                type="number"
                                step="0.01"
                                name="price"
                                value="{{ old('price') }}"
                                class="mt-1 w-full rounded-lg border-gray-300 focus:border-primary-900 focus:ring-2 focus:ring-primary-900/20 px-4 py-3 transition-all duration-200 group-hover:border-primary-500"
                                placeholder="10000"
                            />
                        </div>

                        <div class="group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                <svg class="w-4 h-4 text-primary-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                </svg>
                                Stok
                            </label>
                            <input
                                type="number"
                                name="stock"
                                value="{{ old('stock') }}"
                                class="mt-1 w-full rounded-lg border-gray-300 focus:border-primary-900 focus:ring-2 focus:ring-primary-900/20 px-4 py-3 transition-all duration-200 group-hover:border-primary-500"
                                placeholder="0"
                            />
                        </div>
                    </div>

                    <div class="group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <svg class="w-4 h-4 text-primary-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Status Produk
                        </label>
                        <div class="relative">
                            <select name="status" class="mt-1 w-full rounded-lg border-gray-300 focus:border-primary-900 focus:ring-2 focus:ring-primary-900/20 px-4 py-3 appearance-none transition-all duration-200 group-hover:border-primary-500">
                                <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="group">
                    <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4 text-primary-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                        </svg>
                        Deskripsi
                    </label>
                    <textarea
                        name="description"
                        rows="4"
                        class="mt-1 w-full rounded-lg border-gray-300 focus:border-primary-900 focus:ring-2 focus:ring-primary-900/20 px-4 py-3 transition-all duration-200 group-hover:border-primary-500"
                        placeholder="Deskripsi singkat produk"
                    >{{ old('description') }}</textarea>
                </div>
            </div>

            <div class="pt-6 border-t border-gray-100">
                <div class="flex flex-col sm:flex-row gap-4 justify-end">
                    <a href="{{ $backHref }}" class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 hover:text-gray-900 px-6 py-3 text-sm font-medium transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-gradient-to-r from-primary-900 to-primary-800 hover:from-primary-800 hover:to-primary-700 text-white px-6 py-3 text-sm font-semibold shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-[1.02]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Produk Baru
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="bg-primary-50 border border-primary-100 rounded-2xl p-6 mb-6">
        <div class="flex items-start gap-4">
            <div class="p-2 bg-primary-100 rounded-lg">
                <svg class="w-6 h-6 text-primary-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="font-semibold text-primary-900 mb-2">Catatan Business Rule</h3>
                <ul class="space-y-2 text-sm text-gray-700">
                    <li class="flex items-start gap-2">
                        <div class="w-1.5 h-1.5 rounded-full bg-primary-900 mt-1.5"></div>
                        <span>Seller nonaktif tidak boleh menambah produk</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <div class="w-1.5 h-1.5 rounded-full bg-primary-900 mt-1.5"></div>
                        <span>Harga harus &gt; 0</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <div class="w-1.5 h-1.5 rounded-full bg-primary-900 mt-1.5"></div>
                        <span>Stok tidak boleh negatif</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endsection
