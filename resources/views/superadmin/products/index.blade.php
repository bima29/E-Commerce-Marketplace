@extends('layouts.superadmin')

@section('title', 'Products')

@section('content')
    @php
        $createHref = \Illuminate\Support\Facades\Route::has('superadmin.products.create')
            ? route('superadmin.products.create')
            : url('/superadmin/products/create');

        $indexHref = \Illuminate\Support\Facades\Route::has('superadmin.products.index')
            ? route('superadmin.products.index')
            : url('/superadmin/products');
    @endphp

    <div class="rounded-2xl bg-gradient-to-r from-primary-900 via-primary-800 to-primary-700 p-6 md:p-8 mb-6 shadow-xl">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-white">Produk</h1>
                <p class="text-sm text-primary-100">Kelola produk per seller dan statusnya.</p>
            </div>
            <a href="{{ $createHref }}" class="inline-flex items-center justify-center rounded-lg bg-primary-900/20 text-white hover:bg-primary-900/30 ring-1 ring-white/30 px-4 py-2 text-sm font-semibold">
                Tambah Produk
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 p-5 mb-5 shadow-sm">
        <form method="GET" action="{{ $indexHref }}" class="flex flex-col gap-3 md:flex-row md:items-end">
            <div class="flex-1">
                <label class="block text-sm font-semibold text-gray-700">Filter Seller (opsional)</label>
                <select name="seller_id" class="mt-2 w-full rounded-lg border-gray-300 focus:border-primary-900 focus:ring-primary-900">
                    <option value="">Semua Seller</option>
                    @foreach (($sellers ?? []) as $seller)
                        <option value="{{ $seller->id }}" {{ (string)request('seller_id') === (string)$seller->id ? 'selected' : '' }}>
                            {{ $seller->name ?? ('Seller #' . $seller->id) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <button class="inline-flex items-center justify-center rounded-lg bg-primary-900 hover:bg-primary-800 px-5 py-2 text-sm font-semibold text-white" type="submit">Terapkan</button>
            </div>
        </form>
    </div>

    <div class="md:hidden space-y-4">
        @forelse ($products ?? [] as $product)
            @php
                $isActive = ($product->status ?? null) === 'active' || ($product->status ?? null) === 'Aktif' || ($product->is_active ?? null) === true;
                $editHref = \Illuminate\Support\Facades\Route::has('superadmin.products.edit')
                    ? route('superadmin.products.edit', ['product' => $product->id])
                    : url('/superadmin/products/' . ($product->id ?? 0) . '/edit');
                $deleteAction = \Illuminate\Support\Facades\Route::has('superadmin.products.destroy')
                    ? route('superadmin.products.destroy', ['product' => $product->id])
                    : url('/superadmin/products/' . ($product->id ?? 0));
            @endphp

            <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div class="min-w-0">
                        <div class="font-bold text-gray-900 truncate">{{ $product->name ?? '-' }}</div>
                        <div class="mt-1 text-sm text-gray-600 truncate">{{ $product->seller->name ?? '-' }}</div>
                        <div class="mt-2 text-sm text-gray-600">
                            Rp {{ number_format((float)($product->price ?? 0), 0, ',', '.') }}
                            <span class="text-gray-300 mx-2">|</span>
                            Stok: <span class="font-semibold text-gray-900">{{ (int)($product->stock ?? 0) }}</span>
                        </div>
                    </div>
                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $isActive ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                        {{ $product->status ?? ($isActive ? 'Aktif' : 'Nonaktif') }}
                    </span>
                </div>

                <div class="mt-4 flex items-center gap-3">
                    <a href="{{ $editHref }}" class="inline-flex flex-1 items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                        Edit
                    </a>
                    <form method="POST" action="{{ $deleteAction }}" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full inline-flex items-center justify-center rounded-lg border border-red-200 bg-red-50 px-4 py-2 text-sm font-semibold text-red-700 hover:bg-red-100">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl border border-gray-200 p-10 text-center">
                <div class="text-lg font-bold text-gray-900 mb-1">Belum ada produk</div>
                <div class="text-sm text-gray-600 mb-5">Klik tombol Tambah Produk untuk membuat produk baru.</div>
                <a href="{{ $createHref }}" class="inline-flex items-center justify-center rounded-lg bg-primary-900 hover:bg-primary-800 text-white px-5 py-2 text-sm font-semibold">
                    Tambah Produk
                </a>
            </div>
        @endforelse
    </div>

    <div class="hidden md:block bg-white rounded-2xl border border-gray-200 overflow-x-auto shadow-sm">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-left">
                <tr>
                    <th class="px-6 py-4 font-semibold text-gray-600">Produk</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">Seller</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">Harga</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">Stok</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">Status</th>
                    <th class="px-6 py-4 font-semibold text-gray-600 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($products ?? [] as $product)
                    @php
                        $isActive = ($product->status ?? null) === 'active' || ($product->status ?? null) === 'Aktif' || ($product->is_active ?? null) === true;
                        $editHref = \Illuminate\Support\Facades\Route::has('superadmin.products.edit')
                            ? route('superadmin.products.edit', ['product' => $product->id])
                            : url('/superadmin/products/' . ($product->id ?? 0) . '/edit');
                        $deleteAction = \Illuminate\Support\Facades\Route::has('superadmin.products.destroy')
                            ? route('superadmin.products.destroy', ['product' => $product->id])
                            : url('/superadmin/products/' . ($product->id ?? 0));
                    @endphp
                    <tr>
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-900">{{ $product->name ?? '-' }}</div>
                            @if(!empty($product->description))
                                <div class="text-xs text-gray-500 mt-1">{{ \Illuminate\Support\Str::limit((string)$product->description, 70) }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">{{ $product->seller->name ?? '-' }}</td>
                        <td class="px-6 py-4 font-semibold text-primary-900">Rp {{ number_format((float)($product->price ?? 0), 0, ',', '.') }}</td>
                        <td class="px-6 py-4">{{ (int)($product->stock ?? 0) }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $isActive ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ $product->status ?? ($isActive ? 'Aktif' : 'Nonaktif') }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ $editHref }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50">
                                    Edit
                                </a>
                                <form method="POST" action="{{ $deleteAction }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-xs font-semibold text-red-700 hover:bg-red-100">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-6 py-10 text-center text-gray-500" colspan="6">Belum ada data produk.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
