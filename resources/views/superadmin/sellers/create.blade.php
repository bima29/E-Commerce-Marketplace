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

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-semibold">Tambah Seller</h1>
        <a href="{{ $backHref }}" class="text-sm text-gray-600 hover:text-gray-900">Kembali</a>
    </div>

    <div class="rounded border bg-white p-6">
        <form method="POST" action="{{ $storeAction }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700">Nama Toko</label>
                <input name="name" value="{{ old('name') }}" class="mt-1 w-full rounded border-gray-300 focus:border-gray-900 focus:ring-gray-900" placeholder="Contoh: Toko ABC" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="mt-1 w-full rounded border-gray-300 focus:border-gray-900 focus:ring-gray-900" placeholder="seller@email.com" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" class="mt-1 w-full rounded border-gray-300 focus:border-gray-900 focus:ring-gray-900">
                    <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <div class="pt-2">
                <button type="submit" class="inline-flex items-center rounded bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800">
                    Simpan
                </button>
            </div>
        </form>
    </div>
@endsection
