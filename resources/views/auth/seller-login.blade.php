@extends('layouts.app')

@section('title', 'Login Seller')

@section('content')
@php
    $postAction = \Illuminate\Support\Facades\Route::has('seller.login.submit')
        ? route('seller.login.submit')
        : url('/login/seller');
@endphp

<div class="container mx-auto px-4 lg:px-8 py-10">
    <div class="max-w-md mx-auto bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h1 class="text-xl font-bold text-gray-900">Login Seller</h1>
            <p class="text-sm text-gray-600 mt-1">Masuk untuk mengelola produk seller.</p>
        </div>

        <form method="POST" action="{{ $postAction }}" class="p-6 space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded-lg border border-gray-300 px-3 py-3 focus:border-primary-900 focus:ring-primary-900" placeholder="seller@contoh.com" />
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                <input type="password" name="password" class="w-full rounded-lg border border-gray-300 px-3 py-3 focus:border-primary-900 focus:ring-primary-900" placeholder="Password" />
            </div>

            <button type="submit" class="w-full rounded-lg bg-primary-900 py-3 text-sm font-semibold text-white hover:bg-primary-800">
                Login
            </button>

            <div class="text-xs text-gray-500">
                Gunakan akun seed dari README (role seller).
            </div>
        </form>
    </div>
</div>
@endsection
