@extends('layouts.superadmin')

@section('title', 'Sellers')

@section('content')
    @php
        $createHref = \Illuminate\Support\Facades\Route::has('superadmin.sellers.create')
            ? route('superadmin.sellers.create')
            : url('/superadmin/sellers/create');
    @endphp

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-semibold">Daftar Seller</h1>
        <a href="{{ $createHref }}" class="inline-flex items-center rounded bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800">
            Tambah Seller
        </a>
    </div>

    <div class="rounded border bg-white overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-left">
                <tr>
                    <th class="px-4 py-3 font-medium text-gray-600">Nama Toko</th>
                    <th class="px-4 py-3 font-medium text-gray-600">Email</th>
                    <th class="px-4 py-3 font-medium text-gray-600">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($sellers ?? [] as $seller)
                    <tr>
                        <td class="px-4 py-3">{{ $seller->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $seller->email ?? '-' }}</td>
                        <td class="px-4 py-3">
                            @php
                                $isActive = ($seller->status ?? null) === 'active' || ($seller->status ?? null) === 'Aktif' || ($seller->is_active ?? null) === true;
                            @endphp
                            <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium {{ $isActive ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ $seller->status ?? ($isActive ? 'Aktif' : 'Nonaktif') }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-4 py-6 text-gray-500" colspan="3">Belum ada data seller.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
