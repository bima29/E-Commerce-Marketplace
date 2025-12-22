@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
    @php
        $submitAction = \Illuminate\Support\Facades\Route::has('checkout.store')
            ? route('checkout.store')
            : url('/checkout');

        $cartHref = \Illuminate\Support\Facades\Route::has('cart.index')
            ? route('cart.index')
            : url('/cart');

        $homeHref = \Illuminate\Support\Facades\Route::has('home')
            ? route('home')
            : url('/');
    @endphp

    <div class="container mx-auto px-4 lg:px-8 py-6">
        <div class="mb-8">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Checkout</h1>
                    <p class="text-sm text-gray-600">Lengkapi data pengiriman untuk membuat pesanan.</p>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ $cartHref }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                </div>
            </div>

            <div class="mt-4 flex items-center gap-2 text-sm text-gray-500">
                <a class="hover:text-primary-900" href="{{ $homeHref }}">Products</a>
                <span class="text-gray-300">/</span>
                <a class="hover:text-primary-900" href="{{ $cartHref }}">Cart</a>
                <span class="text-gray-300">/</span>
                <span class="font-medium text-primary-900">Checkout</span>
            </div>
        </div>

        @if (empty($cart ?? []))
            <div class="bg-white rounded-2xl border border-gray-200 p-12 text-center">
                <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-primary-50 flex items-center justify-center">
                    <i class="fas fa-credit-card text-primary-900 text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Tidak bisa checkout</h3>
                <p class="text-gray-600 mb-6">Keranjang kamu masih kosong.</p>
                <a href="{{ $homeHref }}" class="inline-flex items-center rounded-lg bg-primary-900 px-6 py-3 text-sm font-semibold text-white hover:bg-primary-800">
                    Lihat Produk
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
                <div class="lg:col-span-7">
                    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <div class="font-bold text-gray-900">Data Pengiriman</div>
                            <div class="text-sm text-gray-500">Pastikan data benar untuk menghindari kesalahan.</div>
                        </div>

                        <form method="POST" action="{{ $submitAction }}" class="p-6 space-y-5">
                            @csrf

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                            <i class="fas fa-user"></i>
                                        </span>
                                        <input
                                            name="name"
                                            value="{{ old('name') }}"
                                            class="w-full pl-10 pr-3 py-3 rounded-lg border border-gray-300 focus:border-primary-900 focus:ring-primary-900"
                                            placeholder="Nama lengkap"
                                        />
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                            <i class="fas fa-envelope"></i>
                                        </span>
                                        <input
                                            type="email"
                                            name="email"
                                            value="{{ old('email') }}"
                                            class="w-full pl-10 pr-3 py-3 rounded-lg border border-gray-300 focus:border-primary-900 focus:ring-primary-900"
                                            placeholder="email@contoh.com"
                                        />
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Alamat</label>
                                <div class="relative">
                                    <span class="absolute top-3 left-0 pl-3 flex items-center text-gray-400">
                                        <i class="fas fa-location-dot"></i>
                                    </span>
                                    <textarea
                                        name="address"
                                        rows="4"
                                        class="w-full pl-10 pr-3 py-3 rounded-lg border border-gray-300 focus:border-primary-900 focus:ring-primary-900"
                                        placeholder="Alamat lengkap (jalan, kecamatan, kota)"
                                    >{{ old('address') }}</textarea>
                                </div>
                            </div>

                            <div class="rounded-xl bg-primary-50 border border-primary-100 p-4 text-sm text-gray-700">
                                <div class="font-semibold text-primary-900 mb-1">Catatan</div>
                                <div>Checkout pada project ini masih simulasi (tanpa payment gateway).</div>
                            </div>

                            <div class="flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
                                <a href="{{ $cartHref }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    Kembali ke Cart
                                </a>

                                <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-gradient-to-r from-primary-900 to-primary-800 px-6 py-3 text-sm font-semibold text-white shadow-md hover:from-primary-800 hover:to-primary-700">
                                    <i class="fas fa-bag-shopping mr-2"></i>
                                    Buat Pesanan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="lg:col-span-5">
                    <div class="bg-white rounded-2xl border border-gray-200 p-6 lg:sticky lg:top-24">
                        <div class="flex items-center justify-between mb-4">
                            <div class="text-lg font-bold text-gray-900">Ringkasan Pesanan</div>
                            <div class="text-sm text-gray-500">{{ count($cart ?? []) }} item</div>
                        </div>

                        <div class="space-y-3">
                            @foreach (($cart ?? []) as $item)
                                @php
                                    $qty = (int)($item['qty'] ?? 0);
                                    $price = (float)($item['price'] ?? 0);
                                    $subtotal = $price * $qty;
                                @endphp
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex items-start gap-3">
                                        <div class="h-10 w-10 rounded-xl bg-primary-50 flex items-center justify-center shrink-0">
                                            <i class="fas fa-box text-primary-900"></i>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-900 leading-snug">{{ $item['name'] ?? '-' }}</div>
                                            <div class="text-xs text-gray-500">Qty: {{ $qty }}</div>
                                        </div>
                                    </div>
                                    <div class="font-semibold text-gray-900">Rp {{ number_format((int)$subtotal, 0, ',', '.') }}</div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-5 border-t border-gray-200 pt-4 space-y-2 text-sm">
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

                        <div class="mt-4 text-xs text-gray-500">
                            Dengan menekan “Buat Pesanan”, kamu menyetujui pesanan simulasi ini.
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
