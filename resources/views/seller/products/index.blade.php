@extends('layouts.seller')

@section('title', 'Produk Saya')

@section('content')
    @php
        $createHref = \Illuminate\Support\Facades\Route::has('seller.products.create')
            ? route('seller.products.create')
            : url('/seller/products/create');
    @endphp

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-semibold">Produk Saya</h1>
        <a href="{{ $createHref }}" class="inline-flex items-center rounded bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800">
            Tambah Produk
        </a>
    </div>

    <div class="rounded border bg-white overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-left">
                <tr>
                    <th class="px-4 py-3 font-medium text-gray-600">Nama Produk</th>
                    <th class="px-4 py-3 font-medium text-gray-600">Harga</th>
                    <th class="px-4 py-3 font-medium text-gray-600">Stok</th>
                    <th class="px-4 py-3 font-medium text-gray-600">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($products ?? [] as $product)
                    <tr>
                        <td class="px-4 py-3">{{ $product->name ?? '-' }}</td>
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
                        <td class="px-4 py-6 text-gray-500" colspan="4">Belum ada data produk.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
