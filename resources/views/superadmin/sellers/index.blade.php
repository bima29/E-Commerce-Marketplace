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
                    <form id="js-delete-seller-form-{{ $seller->id }}" method="POST" action="{{ $deleteAction }}">
                        @csrf
                        @method('DELETE')
                        <button
                            type="button"
                            class="w-full inline-flex items-center justify-center rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-xs font-semibold text-red-700 hover:bg-red-100 js-delete-seller-btn"
                            data-form-id="js-delete-seller-form-{{ $seller->id }}"
                            data-seller-id="{{ $seller->id }}"
                            data-seller-name="{{ $seller->name ?? '-' }}"
                            data-seller-email="{{ $seller->email ?? '-' }}"
                            data-seller-status="{{ $seller->status ?? ($isActive ? 'Aktif' : 'Nonaktif') }}"
                        >
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
                                <form id="js-delete-seller-form-{{ $seller->id }}" method="POST" action="{{ $deleteAction }}">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="button"
                                        class="inline-flex items-center rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-xs font-semibold text-red-700 hover:bg-red-100 js-delete-seller-btn"
                                        data-form-id="js-delete-seller-form-{{ $seller->id }}"
                                        data-seller-id="{{ $seller->id }}"
                                        data-seller-name="{{ $seller->name ?? '-' }}"
                                        data-seller-email="{{ $seller->email ?? '-' }}"
                                        data-seller-status="{{ $seller->status ?? ($isActive ? 'Aktif' : 'Nonaktif') }}"
                                    >
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

    <div id="js-delete-seller-modal" class="fixed inset-0 hidden" style="z-index: 9999;">
        <div class="absolute inset-0 bg-gray-900/50"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="w-full max-w-lg rounded-2xl bg-white shadow-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 flex items-start justify-between gap-4">
                    <div>
                        <div class="text-lg font-bold text-gray-900">Konfirmasi Hapus Seller</div>
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
                                <div id="js-delete-seller-id" class="font-semibold text-gray-900">-</div>
                            </div>
                            <div class="flex items-center justify-between gap-4">
                                <div class="text-gray-600">Nama Toko</div>
                                <div id="js-delete-seller-name" class="font-semibold text-gray-900 text-right">-</div>
                            </div>
                            <div class="flex items-center justify-between gap-4">
                                <div class="text-gray-600">Email</div>
                                <div id="js-delete-seller-email" class="font-semibold text-gray-900 text-right">-</div>
                            </div>
                            <div class="flex items-center justify-between gap-4">
                                <div class="text-gray-600">Status</div>
                                <div id="js-delete-seller-status" class="font-semibold text-gray-900">-</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-5 border-t border-gray-200">
                    <div class="flex flex-col sm:flex-row gap-3 justify-end">
                        <button type="button" class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 hover:text-gray-900 px-5 py-3 text-sm font-semibold" data-modal-close>
                            Batal
                        </button>
                        <button id="js-delete-seller-confirm" type="button" class="inline-flex items-center justify-center gap-2 rounded-lg bg-red-600 hover:bg-red-700 text-white px-5 py-3 text-sm font-semibold">
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
            const modal = document.getElementById('js-delete-seller-modal');
            const confirmBtn = document.getElementById('js-delete-seller-confirm');
            if (!modal || !confirmBtn) return;

            const idEl = document.getElementById('js-delete-seller-id');
            const nameEl = document.getElementById('js-delete-seller-name');
            const emailEl = document.getElementById('js-delete-seller-email');
            const statusEl = document.getElementById('js-delete-seller-status');

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

            document.querySelectorAll('.js-delete-seller-btn').forEach((btn) => {
                btn.addEventListener('click', function () {
                    activeFormId = this.getAttribute('data-form-id');

                    if (idEl) idEl.textContent = this.getAttribute('data-seller-id') || '-';
                    if (nameEl) nameEl.textContent = this.getAttribute('data-seller-name') || '-';
                    if (emailEl) emailEl.textContent = this.getAttribute('data-seller-email') || '-';
                    if (statusEl) statusEl.textContent = this.getAttribute('data-seller-status') || '-';

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
