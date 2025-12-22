@extends('layouts.superadmin')

@section('title', 'Tambah Seller')

@section('content')
    @php
        $storeAction = \Illuminate\Support\Facades\Route::has('superadmin.sellers.store')
            ? route('superadmin.sellers.store')
            : url('/superadmin/sellers');

        $backHref = \Illuminate\Support\Facades\Route::has('superadmin.sellers.index')
            ? route('superadmin.sellers.index')
            : url('/superadmin/sellers');
    @endphp

    <div class="rounded-2xl bg-gradient-to-r from-primary-900 via-primary-800 to-primary-700 p-6 md:p-8 mb-6 shadow-xl">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-white">Tambah Seller</h1>
                <p class="text-sm text-primary-100">Buat toko baru untuk seller.</p>
            </div>
            <a href="{{ $backHref }}" class="inline-flex items-center justify-center rounded-lg bg-white text-primary-900 hover:bg-gray-100 px-4 py-2 text-sm font-semibold">
                Kembali
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
        <form method="POST" action="{{ $storeAction }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-gray-700">Nama Toko</label>
                <input name="name" value="{{ old('name') }}" class="mt-2 w-full rounded-lg border-gray-300 focus:border-primary-900 focus:ring-primary-900" placeholder="Contoh: Toko ABC" />
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="mt-2 w-full rounded-lg border-gray-300 focus:border-primary-900 focus:ring-primary-900" placeholder="seller@email.com" />
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700">Status</label>
                <select name="status" class="mt-2 w-full rounded-lg border-gray-300 focus:border-primary-900 focus:ring-primary-900">
                    <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700">Password Akun Seller</label>
                    <input type="password" name="password" class="mt-2 w-full rounded-lg border-gray-300 focus:border-primary-900 focus:ring-primary-900" placeholder="Minimal 6 karakter" autocomplete="new-password" />
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="mt-2 w-full rounded-lg border-gray-300 focus:border-primary-900 focus:ring-primary-900" placeholder="Ulangi password" autocomplete="new-password" />
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-primary-900 hover:bg-primary-800 px-5 py-2 text-sm font-semibold text-white">
                    Simpan
                </button>
            </div>
        </form>
    </div>
@endsection
