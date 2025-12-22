@extends('layouts.superadmin')

@section('title', 'Sellers')

@section('content')
    @php
        $createHref = \Illuminate\Support\Facades\Route::has('superadmin.sellers.create')
            ? route('superadmin.sellers.create')
            : url('/superadmin/sellers/create');

        $productsIndexHref = \Illuminate\Support\Facades\Route::has('superadmin.products.index')
            ? route('superadmin.products.index')
            : url('/superadmin/products');
    @endphp

    <div class="rounded-2xl bg-gradient-to-r from-primary-900 via-primary-800 to-primary-700 p-6 md:p-8 mb-6 shadow-xl">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-white">Seller / Toko</h1>
                <p class="text-sm text-primary-100">Kelola toko, status, dan akses produk per seller.</p>
            </div>
            <a href="{{ $createHref }}" class="inline-flex items-center justify-center rounded-lg bg-primary-900/20 text-white hover:bg-primary-900/30 ring-1 ring-white/30 px-4 py-2 text-sm font-semibold">
                Tambah Seller
            </a>
        </div>
    </div>

    <div class="md:hidden space-y-4">
        @forelse ($sellers ?? [] as $seller)
            @php
                $isActive = ($seller->status ?? null) === 'active' || ($seller->status ?? null) === 'Aktif' || ($seller->is_active ?? null) === true;
                $editHref = \Illuminate\Support\Facades\Route::has('superadmin.sellers.edit')
                    ? route('superadmin.sellers.edit', ['seller' => $seller->id])
                    : url('/superadmin/sellers/' . ($seller->id ?? 0) . '/edit');
                $deleteAction = \Illuminate\Support\Facades\Route::has('superadmin.sellers.destroy')
                    ? route('superadmin.sellers.destroy', ['seller' => $seller->id])
                    : url('/superadmin/sellers/' . ($seller->id ?? 0));
                $sellerProductsHref = $productsIndexHref . '?seller_id=' . ($seller->id ?? '');
            @endphp

            <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div class="min-w-0">
                        <div class="font-bold text-gray-900 truncate">{{ $seller->name ?? '-' }}</div>
                        <div class="mt-1 text-sm text-gray-600 truncate">{{ $seller->email ?? '-' }}</div>
                    </div>
                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $isActive ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                        {{ $seller->status ?? ($isActive ? 'Aktif' : 'Nonaktif') }}
                    </span>
                </div>

                <div class="mt-4 grid grid-cols-3 gap-2">
                    <a href="{{ $sellerProductsHref }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50">
                        Produk
                    </a>
                    <a href="{{ $editHref }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50">
                        Edit
                    </a>
                    <form method="POST" action="{{ $deleteAction }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full inline-flex items-center justify-center rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-xs font-semibold text-red-700 hover:bg-red-100">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl border border-gray-200 p-10 text-center">
                <div class="text-lg font-bold text-gray-900 mb-1">Belum ada seller</div>
                <div class="text-sm text-gray-600 mb-5">Klik tombol Tambah Seller untuk membuat toko baru.</div>
                <a href="{{ $createHref }}" class="inline-flex items-center justify-center rounded-lg bg-primary-900 hover:bg-primary-800 text-white px-5 py-2 text-sm font-semibold">
                    Tambah Seller
                </a>
            </div>
        @endforelse
    </div>

    <div class="hidden md:block bg-white rounded-2xl border border-gray-200 overflow-x-auto shadow-sm">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-left">
                <tr>
                    <th class="px-6 py-4 font-semibold text-gray-600">Nama Toko</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">Email</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">Status</th>
                    <th class="px-6 py-4 font-semibold text-gray-600 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($sellers ?? [] as $seller)
                    @php
                        $isActive = ($seller->status ?? null) === 'active' || ($seller->status ?? null) === 'Aktif' || ($seller->is_active ?? null) === true;
                        $editHref = \Illuminate\Support\Facades\Route::has('superadmin.sellers.edit')
                            ? route('superadmin.sellers.edit', ['seller' => $seller->id])
                            : url('/superadmin/sellers/' . ($seller->id ?? 0) . '/edit');
                        $deleteAction = \Illuminate\Support\Facades\Route::has('superadmin.sellers.destroy')
                            ? route('superadmin.sellers.destroy', ['seller' => $seller->id])
                            : url('/superadmin/sellers/' . ($seller->id ?? 0));
                        $sellerProductsHref = $productsIndexHref . '?seller_id=' . ($seller->id ?? '');
                    @endphp
                    <tr>
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-900">{{ $seller->name ?? '-' }}</div>
                            <div class="text-xs text-gray-500 mt-1">ID: {{ $seller->id ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4">{{ $seller->email ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $isActive ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ $seller->status ?? ($isActive ? 'Aktif' : 'Nonaktif') }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ $sellerProductsHref }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50">
                                    Produk
                                </a>
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
                        <td class="px-6 py-10 text-center text-gray-500" colspan="4">Belum ada data seller.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
