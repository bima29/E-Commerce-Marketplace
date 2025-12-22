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
                            <div class="text-sm text-gray-500">{{ count($cart ?? []) }} item</div>
                        </div>

                        <form method="POST" action="{{ $updateAction }}">
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
                                            <tr>
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
                                                        class="w-24 rounded-lg border-gray-300 text-sm focus:border-primary-900 focus:ring-primary-900"
                                                    />
                                                </td>
                                                <td class="px-6 py-4 font-semibold text-gray-900">
                                                    Rp {{ number_format((int)$subtotal, 0, ',', '.') }}
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

                                    <div class="p-5">
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
                                                <div class="font-semibold text-gray-900">Rp {{ number_format((int)$subtotal, 0, ',', '.') }}</div>
                                            </div>
                                        </div>

                                        <div class="mt-3">
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Jumlah</label>
                                            <input
                                                type="number"
                                                min="1"
                                                name="items[{{ $item['id'] }}]"
                                                value="{{ $qty > 0 ? $qty : 1 }}"
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
                                <span class="font-semibold text-gray-900">Rp {{ number_format((int)$total, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Ongkir</span>
                                <span class="font-semibold text-gray-900">Rp {{ number_format((int)$shipping, 0, ',', '.') }}</span>
                            </div>
                            <div class="border-t pt-3 flex items-center justify-between">
                                <span class="font-bold text-gray-900">Total</span>
                                <span class="font-bold text-primary-900">Rp {{ number_format((int)$grandTotal, 0, ',', '.') }}</span>
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
@endsection
