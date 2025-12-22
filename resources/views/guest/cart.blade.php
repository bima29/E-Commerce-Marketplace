@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
    @php
        $homeHref = \Illuminate\Support\Facades\Route::has('home')
            ? route('home')
            : url('/');

        $checkoutHref = \Illuminate\Support\Facades\Route::has('checkout.index')
            ? route('checkout.index')
            : url('/checkout');

        $updateAction = \Illuminate\Support\Facades\Route::has('cart.update')
            ? route('cart.update')
            : url('/cart/update');
    @endphp

    <div class="container mx-auto px-4 lg:px-8 py-6">
        <div class="mb-8">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Keranjang Belanja</h1>
                    <p class="text-sm text-gray-600">Periksa item kamu sebelum checkout.</p>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ $homeHref }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                        <span>Lanjut Belanja</span>
                    </a>

                    <a href="{{ $checkoutHref }}" class="inline-flex items-center rounded-lg bg-gradient-to-r from-primary-900 to-primary-800 px-5 py-2 text-sm font-semibold text-white shadow-md hover:from-primary-800 hover:to-primary-700">
                        <span>Checkout</span>
                    </a>
                </div>
            </div>

            <div class="mt-4 flex items-center gap-2 text-sm text-gray-500">
                <span class="font-medium text-primary-900">Cart</span>
                <span class="text-gray-300">/</span>
                <span>Checkout</span>
            </div>
        </div>

        @if (empty($cart ?? []))
            <div class="bg-white rounded-2xl border border-gray-200 p-12 text-center">
                <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-primary-50 flex items-center justify-center">
                    <i class="fas fa-shopping-cart text-primary-900 text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Keranjang kamu kosong</h3>
                <p class="text-gray-600 mb-6">Ayo tambahkan produk favoritmu.</p>
                <a href="{{ $homeHref }}" class="inline-flex items-center rounded-lg bg-primary-900 px-6 py-3 text-sm font-semibold text-white hover:bg-primary-800">
                    Mulai Belanja
                </a>
            </div>
        @else
            @php
                $total = 0;
                foreach (($cart ?? []) as $item) {
                    $total += ((float)($item['price'] ?? 0)) * ((int)($item['qty'] ?? 0));
                }
                $shipping = 0;
                $grandTotal = $total + $shipping;
            @endphp

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <div class="lg:col-span-8">
                    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                            <div class="font-bold text-gray-900">Item di Keranjang</div>
                            <div class="text-sm text-gray-500"><span data-cart-item-lines>{{ count($cart ?? []) }}</span> item</div>
                        </div>

                        <form method="POST" action="{{ $updateAction }}" class="js-cart-update">
                            @csrf

                            <div class="hidden md:block overflow-x-auto">
                                <table class="min-w-full text-sm">
                                    <thead class="bg-gray-50 text-left">
                                        <tr>
                                            <th class="px-6 py-4 font-semibold text-gray-600">Produk</th>
                                            <th class="px-6 py-4 font-semibold text-gray-600">Harga</th>
                                            <th class="px-6 py-4 font-semibold text-gray-600">Qty</th>
                                            <th class="px-6 py-4 font-semibold text-gray-600">Subtotal</th>
                                            <th class="px-6 py-4 font-semibold text-gray-600"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y">
                                        @foreach (($cart ?? []) as $item)
                                            @php
                                                $qty = (int)($item['qty'] ?? 0);
                                                $price = (float)($item['price'] ?? 0);
                                                $subtotal = $price * $qty;

                                                $removeAction = \Illuminate\Support\Facades\Route::has('cart.remove')
                                                    ? route('cart.remove', ['product' => $item['id']])
                                                    : url('/cart/remove/' . ($item['id'] ?? 0));
                                            @endphp
                                            <tr data-cart-item-row="{{ $item['id'] }}">
                                                <td class="px-6 py-4">
                                                    <div class="flex items-center gap-4">
                                                        <div class="h-12 w-12 rounded-xl bg-primary-50 flex items-center justify-center">
                                                            <i class="fas fa-box text-primary-900"></i>
                                                        </div>
                                                        <div>
                                                            <div class="font-semibold text-gray-900">{{ $item['name'] ?? '-' }}</div>
                                                            <div class="text-xs text-gray-500">ID: {{ $item['id'] ?? '-' }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 font-semibold text-primary-900">
                                                    Rp {{ number_format((int)$price, 0, ',', '.') }}
                                                </td>
                                                <td class="px-6 py-4">
                                                    <input
                                                        type="number"
                                                        min="1"
                                                        name="items[{{ $item['id'] }}]"
                                                        value="{{ $qty > 0 ? $qty : 1 }}"
                                                        data-cart-item-qty="{{ $item['id'] }}"
                                                        class="w-24 rounded-lg border-gray-300 text-sm focus:border-primary-900 focus:ring-primary-900"
                                                    />
                                                </td>
                                                <td class="px-6 py-4 font-semibold text-gray-900">
                                                    <span data-cart-item-subtotal="{{ $item['id'] }}">Rp {{ number_format((int)$subtotal, 0, ',', '.') }}</span>
                                                </td>
                                                <td class="px-6 py-4 text-right">
                                                    <button
                                                        type="submit"
                                                        formaction="{{ $removeAction }}"
                                                        class="inline-flex items-center gap-2 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm font-semibold text-red-700 hover:bg-red-100"
                                                    >
                                                        <i class="fas fa-trash"></i>
                                                        <span>Hapus</span>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="md:hidden divide-y">
                                @foreach (($cart ?? []) as $item)
                                    @php
                                        $qty = (int)($item['qty'] ?? 0);
                                        $price = (float)($item['price'] ?? 0);
                                        $subtotal = $price * $qty;

                                        $removeAction = \Illuminate\Support\Facades\Route::has('cart.remove')
                                            ? route('cart.remove', ['product' => $item['id']])
                                            : url('/cart/remove/' . ($item['id'] ?? 0));
                                    @endphp

                                    <div class="p-5" data-cart-item-row="{{ $item['id'] }}">
                                        <div class="flex items-start justify-between gap-4">
                                            <div class="flex items-center gap-3">
                                                <div class="h-12 w-12 rounded-xl bg-primary-50 flex items-center justify-center">
                                                    <i class="fas fa-box text-primary-900"></i>
                                                </div>
                                                <div>
                                                    <div class="font-semibold text-gray-900">{{ $item['name'] ?? '-' }}</div>
                                                    <div class="text-xs text-gray-500">ID: {{ $item['id'] ?? '-' }}</div>
                                                </div>
                                            </div>

                                            <button
                                                type="submit"
                                                formaction="{{ $removeAction }}"
                                                class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-red-200 bg-red-50 text-red-700"
                                                aria-label="Hapus"
                                            >
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>

                                        <div class="mt-4 grid grid-cols-2 gap-3">
                                            <div class="rounded-xl bg-gray-50 p-3">
                                                <div class="text-xs text-gray-500">Harga</div>
                                                <div class="font-semibold text-primary-900">Rp {{ number_format((int)$price, 0, ',', '.') }}</div>
                                            </div>
                                            <div class="rounded-xl bg-gray-50 p-3">
                                                <div class="text-xs text-gray-500">Subtotal</div>
                                                <div class="font-semibold text-gray-900" data-cart-item-subtotal="{{ $item['id'] }}">Rp {{ number_format((int)$subtotal, 0, ',', '.') }}</div>
                                            </div>
                                        </div>

                                        <div class="mt-3">
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Jumlah</label>
                                            <input
                                                type="number"
                                                min="1"
                                                name="items[{{ $item['id'] }}]"
                                                value="{{ $qty > 0 ? $qty : 1 }}"
                                                data-cart-item-qty="{{ $item['id'] }}"
                                                class="w-full rounded-lg border-gray-300 text-sm focus:border-primary-900 focus:ring-primary-900"
                                            />
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="px-6 py-4 border-t border-gray-200 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <div class="text-sm text-gray-600">
                                    Ubah quantity lalu klik <span class="font-semibold text-gray-900">Update</span>.
                                </div>
                                <div class="flex items-center gap-3">
                                    <a href="{{ $homeHref }}" class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                                        Lanjut Belanja
                                    </a>
                                    <button type="submit" class="rounded-lg bg-primary-900 px-5 py-2 text-sm font-semibold text-white hover:bg-primary-800">
                                        Update
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="lg:col-span-4">
                    <div class="bg-white rounded-2xl border border-gray-200 p-6 lg:sticky lg:top-24">
                        <div class="flex items-center justify-between mb-4">
                            <div class="text-lg font-bold text-gray-900">Ringkasan</div>
                            <div class="text-sm text-gray-500">Checkout</div>
                        </div>

                        <div class="space-y-3 text-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="font-semibold text-gray-900" data-cart-summary-subtotal>Rp {{ number_format((int)$total, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Ongkir</span>
                                <span class="font-semibold text-gray-900" data-cart-summary-shipping>Rp {{ number_format((int)$shipping, 0, ',', '.') }}</span>
                            </div>
                            <div class="border-t pt-3 flex items-center justify-between">
                                <span class="font-bold text-gray-900">Total</span>
                                <span class="font-bold text-primary-900" data-cart-summary-total>Rp {{ number_format((int)$grandTotal, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <a href="{{ $checkoutHref }}" class="mt-6 w-full inline-flex items-center justify-center rounded-lg bg-gradient-to-r from-primary-900 to-primary-800 px-4 py-3 text-sm font-semibold text-white shadow-md hover:from-primary-800 hover:to-primary-700">
                            Lanjut ke Checkout
                        </a>

                        <div class="mt-4 text-xs text-gray-500">
                            Checkout di project ini masih simulasi (tanpa payment gateway).
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div id="js-toast-container" class="fixed top-5 right-5 space-y-3" style="z-index: 9999;"></div>

    <script>
        (function () {
            const form = document.querySelector('form.js-cart-update');
            if (!form) return;

            const showToast = (message, type = 'success') => {
                let container = document.getElementById('js-toast-container');
                if (!container) {
                    container = document.createElement('div');
                    container.id = 'js-toast-container';
                    container.className = 'fixed top-5 right-5 space-y-3';
                    container.style.zIndex = '9999';
                    document.body.appendChild(container);
                }

                const isError = type === 'error';
                const toast = document.createElement('div');
                toast.className = isError
                    ? 'w-full max-w-sm rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-800 shadow-lg transition-opacity duration-200 opacity-100'
                    : 'w-full max-w-sm rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-800 shadow-lg transition-opacity duration-200 opacity-100';

                toast.innerHTML = `
                    <div class="flex items-start justify-between gap-4">
                        <div class="text-sm font-semibold">${message}</div>
                        <button type="button" class="-mr-1 -mt-1 inline-flex h-7 w-7 items-center justify-center rounded-md ${isError ? 'text-red-800/70 hover:text-red-900 hover:bg-red-100' : 'text-green-800/70 hover:text-green-900 hover:bg-green-100'}" aria-label="Tutup">
                            &times;
                        </button>
                    </div>
                `;

                const removeToast = () => {
                    toast.classList.remove('opacity-100');
                    toast.classList.add('opacity-0');
                    window.setTimeout(() => toast.remove(), 200);
                };

                const closeBtn = toast.querySelector('button');
                if (closeBtn) {
                    closeBtn.addEventListener('click', removeToast);
                }

                container.appendChild(toast);
                window.setTimeout(removeToast, 5000);
            };

            const formatRupiah = (value) => {
                const n = Math.round(Number(value || 0));
                return 'Rp ' + n.toLocaleString('id-ID');
            };

            form.addEventListener('submit', async function (e) {
                const submitter = e.submitter || null;
                const submitterAction = submitter ? submitter.getAttribute('formaction') : null;

                e.preventDefault();

                if (submitter) {
                    submitter.disabled = true;
                }

                try {
                    const isRemove = !!(submitterAction && submitterAction !== form.action);
                    const actionUrl = isRemove ? submitterAction : form.action;

                    let disabledForSubmit = [];
                    let formData;

                    if (isRemove) {
                        formData = new FormData();
                        const token = form.querySelector('input[name="_token"]')?.value;
                        if (token) {
                            formData.append('_token', token);
                        }
                    } else {
                        const qtyInputs = Array.from(form.querySelectorAll('[data-cart-item-qty]'));
                        disabledForSubmit = [];
                        qtyInputs.forEach((input) => {
                            const isVisible = !!(input.offsetParent) && input.getClientRects().length > 0;
                            if (!isVisible && !input.disabled) {
                                input.disabled = true;
                                disabledForSubmit.push(input);
                            }
                        });

                        formData = new FormData(form);
                    }

                    const res = await fetch(actionUrl, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        },
                        body: formData,
                    });

                    const payload = await res.json().catch(() => null);
                    if (!res.ok) {
                        showToast(payload?.message || 'Gagal update cart', 'error');
                        return;
                    }

                    showToast(payload?.message || (isRemove ? 'Item dihapus dari cart' : 'Cart diperbarui'));

                    const items = payload?.items || {};

                    document.querySelectorAll('[data-cart-item-row]').forEach(row => {
                        const id = row.getAttribute('data-cart-item-row');
                        if (!Object.prototype.hasOwnProperty.call(items, id)) {
                            row.remove();
                        }
                    });

                    document.querySelectorAll('[data-cart-item-subtotal]').forEach(el => {
                        const id = el.getAttribute('data-cart-item-subtotal');
                        if (Object.prototype.hasOwnProperty.call(items, id)) {
                            el.textContent = formatRupiah(items[id].subtotal);
                        }
                    });

                    document.querySelectorAll('[data-cart-item-qty]').forEach(el => {
                        const id = el.getAttribute('data-cart-item-qty');
                        if (Object.prototype.hasOwnProperty.call(items, id)) {
                            el.value = items[id].qty;
                        }
                    });

                    const itemLinesEl = document.querySelector('[data-cart-item-lines]');
                    if (itemLinesEl && typeof payload?.itemLines !== 'undefined') {
                        itemLinesEl.textContent = payload.itemLines;
                    }

                    const subtotalEl = document.querySelector('[data-cart-summary-subtotal]');
                    if (subtotalEl && typeof payload?.subtotal !== 'undefined') {
                        subtotalEl.textContent = formatRupiah(payload.subtotal);
                    }
                    const shippingEl = document.querySelector('[data-cart-summary-shipping]');
                    if (shippingEl && typeof payload?.shipping !== 'undefined') {
                        shippingEl.textContent = formatRupiah(payload.shipping);
                    }
                    const totalEl = document.querySelector('[data-cart-summary-total]');
                    if (totalEl && typeof payload?.total !== 'undefined') {
                        totalEl.textContent = formatRupiah(payload.total);
                    }

                    if (typeof payload?.cartCount !== 'undefined') {
                        document.querySelectorAll('[data-cart-count-badge]').forEach(el => {
                            el.textContent = payload.cartCount;
                        });
                    }

                    disabledForSubmit.forEach((input) => {
                        input.disabled = false;
                    });
                } catch (err) {
                    alert('Terjadi kesalahan. Coba lagi.');
                } finally {
                    const stillDisabled = Array.from(form.querySelectorAll('[data-cart-item-qty][disabled]'));
                    stillDisabled.forEach((input) => {
                        input.disabled = false;
                    });

                    if (submitter) {
                        submitter.disabled = false;
                    }
                }
            });
        })();
    </script>
@endsection
