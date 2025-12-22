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
                    <form id="js-delete-product-form-mobile-{{ $product->id }}" method="POST" action="{{ $deleteAction }}" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button
                            type="button"
                            class="w-full inline-flex items-center justify-center rounded-lg border border-red-200 bg-red-50 px-4 py-2 text-sm font-semibold text-red-700 hover:bg-red-100 js-delete-product-btn"
                            data-form-id="js-delete-product-form-mobile-{{ $product->id }}"
                            data-product-id="{{ $product->id }}"
                            data-product-name="{{ $product->name ?? '-' }}"
                            data-product-seller="{{ $product->seller->name ?? '-' }}"
                            data-product-price="Rp {{ number_format((float)($product->price ?? 0), 0, ',', '.') }}"
                            data-product-stock="{{ (int)($product->stock ?? 0) }}"
                            data-product-status="{{ $product->status ?? ($isActive ? 'Aktif' : 'Nonaktif') }}"
                        >
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
                                <form id="js-delete-product-form-desktop-{{ $product->id }}" method="POST" action="{{ $deleteAction }}">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="button"
                                        class="inline-flex items-center rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-xs font-semibold text-red-700 hover:bg-red-100 js-delete-product-btn"
                                        data-form-id="js-delete-product-form-desktop-{{ $product->id }}"
                                        data-product-id="{{ $product->id }}"
                                        data-product-name="{{ $product->name ?? '-' }}"
                                        data-product-seller="{{ $product->seller->name ?? '-' }}"
                                        data-product-price="Rp {{ number_format((float)($product->price ?? 0), 0, ',', '.') }}"
                                        data-product-stock="{{ (int)($product->stock ?? 0) }}"
                                        data-product-status="{{ $product->status ?? ($isActive ? 'Aktif' : 'Nonaktif') }}"
                                    >
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

    <div id="js-delete-product-modal" class="fixed inset-0 hidden" style="z-index: 9999;">
        <div class="absolute inset-0 bg-gray-900/50"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="w-full max-w-lg rounded-2xl bg-white shadow-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 flex items-start justify-between gap-4">
                    <div>
                        <div class="text-lg font-bold text-gray-900">Konfirmasi Hapus Produk</div>
                        <div class="text-sm text-gray-600 mt-1">Data yang dihapus tidak bisa dikembalikan.</div>
                    </div>
                    <button type="button" class="h-9 w-9 inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white hover:bg-gray-50 text-gray-700" data-modal-close>
                        &times;
                    </button>
                </div>

                <div class="px-6 py-5">
                    <div class="rounded-xl border border-red-200 bg-red-50 p-4">
                        <div class="text-sm font-semibold text-red-800">Kamu akan menghapus:</div>
                        <div class="mt-3 grid grid-cols-1 gap-3 text-sm">
                            <div class="flex items-center justify-between gap-4">
                                <div class="text-gray-600">ID</div>
                                <div id="js-delete-product-id" class="font-semibold text-gray-900">-</div>
                            </div>
                            <div class="flex items-center justify-between gap-4">
                                <div class="text-gray-600">Nama Produk</div>
                                <div id="js-delete-product-name" class="font-semibold text-gray-900 text-right">-</div>
                            </div>
                            <div class="flex items-center justify-between gap-4">
                                <div class="text-gray-600">Seller</div>
                                <div id="js-delete-product-seller" class="font-semibold text-gray-900 text-right">-</div>
                            </div>
                            <div class="flex items-center justify-between gap-4">
                                <div class="text-gray-600">Harga</div>
                                <div id="js-delete-product-price" class="font-semibold text-gray-900">-</div>
                            </div>
                            <div class="flex items-center justify-between gap-4">
                                <div class="text-gray-600">Stok</div>
                                <div id="js-delete-product-stock" class="font-semibold text-gray-900">-</div>
                            </div>
                            <div class="flex items-center justify-between gap-4">
                                <div class="text-gray-600">Status</div>
                                <div id="js-delete-product-status" class="font-semibold text-gray-900">-</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-5 border-t border-gray-200">
                    <div class="flex flex-col sm:flex-row gap-3 justify-end">
                        <button type="button" class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 hover:text-gray-900 px-5 py-3 text-sm font-semibold" data-modal-close>
                            Batal
                        </button>
                        <button id="js-delete-product-confirm" type="button" class="inline-flex items-center justify-center gap-2 rounded-lg bg-red-600 hover:bg-red-700 text-white px-5 py-3 text-sm font-semibold">
                            Ya, Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        (function () {
            const modal = document.getElementById('js-delete-product-modal');
            const confirmBtn = document.getElementById('js-delete-product-confirm');
            if (!modal || !confirmBtn) return;

            const idEl = document.getElementById('js-delete-product-id');
            const nameEl = document.getElementById('js-delete-product-name');
            const sellerEl = document.getElementById('js-delete-product-seller');
            const priceEl = document.getElementById('js-delete-product-price');
            const stockEl = document.getElementById('js-delete-product-stock');
            const statusEl = document.getElementById('js-delete-product-status');

            let activeFormId = null;

            const openModal = () => {
                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
                confirmBtn.focus();
            };

            const closeModal = () => {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
                activeFormId = null;
            };

            document.querySelectorAll('.js-delete-product-btn').forEach((btn) => {
                btn.addEventListener('click', function () {
                    activeFormId = this.getAttribute('data-form-id');

                    if (idEl) idEl.textContent = this.getAttribute('data-product-id') || '-';
                    if (nameEl) nameEl.textContent = this.getAttribute('data-product-name') || '-';
                    if (sellerEl) sellerEl.textContent = this.getAttribute('data-product-seller') || '-';
                    if (priceEl) priceEl.textContent = this.getAttribute('data-product-price') || '-';
                    if (stockEl) stockEl.textContent = this.getAttribute('data-product-stock') || '-';
                    if (statusEl) statusEl.textContent = this.getAttribute('data-product-status') || '-';

                    openModal();
                });
            });

            modal.querySelectorAll('[data-modal-close]').forEach((btn) => {
                btn.addEventListener('click', closeModal);
            });

            modal.addEventListener('click', function (e) {
                if (e.target === modal || e.target === modal.firstElementChild) {
                    closeModal();
                }
            });

            document.addEventListener('keydown', function (e) {
                if (modal.classList.contains('hidden')) return;
                if (e.key === 'Escape') {
                    closeModal();
                }
            });

            confirmBtn.addEventListener('click', function () {
                if (!activeFormId) return;
                const form = document.getElementById(activeFormId);
                if (form) form.submit();
            });
        })();
    </script>
    @endpush
@endsection
