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

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-semibold">Daftar Produk</h1>
        <a href="{{ $createHref }}" class="inline-flex items-center rounded bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800">
            Tambah Produk
        </a>
    </div>

    <div class="rounded border bg-white p-4 mb-4">
        <form method="GET" action="{{ $indexHref }}" class="flex flex-col gap-3 md:flex-row md:items-end">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700">Filter Seller (opsional)</label>
                <select name="seller_id" class="mt-1 w-full rounded border-gray-300 focus:border-gray-900 focus:ring-gray-900">
                    <option value="">Semua Seller</option>
                    @foreach (($sellers ?? []) as $seller)
                        <option value="{{ $seller->id }}" {{ (string)request('seller_id') === (string)$seller->id ? 'selected' : '' }}>
                            {{ $seller->name ?? ('Seller #' . $seller->id) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <button class="inline-flex items-center rounded bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800" type="submit">Terapkan</button>
            </div>
        </form>
    </div>

    <div class="rounded border bg-white overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-left">
                <tr>
                    <th class="px-4 py-3 font-medium text-gray-600">Nama Produk</th>
                    <th class="px-4 py-3 font-medium text-gray-600">Seller</th>
                    <th class="px-4 py-3 font-medium text-gray-600">Harga</th>
                    <th class="px-4 py-3 font-medium text-gray-600">Stok</th>
                    <th class="px-4 py-3 font-medium text-gray-600">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($products ?? [] as $product)
                    <tr>
                        <td class="px-4 py-3">{{ $product->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $product->seller->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $product->price ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $product->stock ?? '-' }}</td>
                        <td class="px-4 py-3">
                            @php
                                $isActive = ($product->status ?? null) === 'active' || ($product->status ?? null) === 'Aktif' || ($product->is_active ?? null) === true;
                            @endphp
                            <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium {{ $isActive ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ $product->status ?? ($isActive ? 'Aktif' : 'Nonaktif') }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-4 py-6 text-gray-500" colspan="5">Belum ada data produk.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
