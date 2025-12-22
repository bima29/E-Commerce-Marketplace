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

    <div class="mb-6 md:mb-8">
        <!-- Hero Header dengan efek gradient yang lebih smooth -->
        <div class="rounded-2xl bg-gradient-to-r from-primary-900 via-primary-800 to-primary-700 p-6 md:p-8 shadow-xl overflow-hidden relative">
            <!-- Efek dekoratif -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -translate-y-16 translate-x-8"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/5 rounded-full translate-y-8 -translate-x-8"></div>
            
            <div class="relative z-10">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="space-y-2">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-white/10 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <h1 class="text-2xl md:text-3xl font-bold text-white">Tambah Seller Baru</h1>
                        </div>
                        <p class="text-sm text-primary-100 max-w-2xl">Buat toko baru untuk seller dengan mengisi formulir di bawah ini.</p>
                    </div>
                    <a href="{{ $backHref }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-white/10 hover:bg-white/20 backdrop-blur-sm border border-white/20 text-white hover:text-white px-5 py-3 text-sm font-medium transition-all duration-200 hover:scale-[1.02]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Kembali ke Daftar
                    </a>
                </div>
            </div>
        </div>

        <!-- Progress indicator -->
        <div class="mt-6 px-2">
            <div class="flex items-center justify-center">
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-full bg-primary-900 text-white flex items-center justify-center">
                        <span class="text-sm font-semibold">1</span>
                    </div>
                    <div class="w-16 md:w-24 h-1 bg-primary-900"></div>
                    <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center">
                        <span class="text-sm font-semibold">2</span>
                    </div>
                </div>
            </div>
            <div class="flex justify-between text-xs text-gray-600 mt-2 px-2">
                <span class="font-medium text-primary-900">Informasi Toko</span>
                <span>Verifikasi Data</span>
            </div>
        </div>
    </div>

    <!-- Form Container dengan card yang lebih menarik -->
    <div class="bg-white rounded-2xl border border-gray-200 p-6 md:p-8 shadow-sm mb-8">
        <!-- Form Header -->
        <div class="mb-8 pb-6 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-primary-50 rounded-lg">
                    <svg class="w-5 h-5 text-primary-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Informasi Toko Seller</h2>
                    <p class="text-sm text-gray-600">Lengkapi data toko untuk seller baru</p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ $storeAction }}" class="space-y-8">
            @csrf

            <!-- Grid untuk form fields -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Kolom Kiri -->
                <div class="space-y-6">
                    <!-- Nama Toko -->
                    <div class="group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <svg class="w-4 h-4 text-primary-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            Nama Toko
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input 
                                name="name" 
                                value="{{ old('name') }}" 
                                class="mt-1 w-full rounded-lg border-gray-300 focus:border-primary-900 focus:ring-2 focus:ring-primary-900/20 px-4 py-3 transition-all duration-200 group-hover:border-primary-500" 
                                placeholder="Contoh: Toko ABC Digital"
                                required
                            />
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                </svg>
                            </div>
                        </div>
                        <p class="mt-2 text-xs text-gray-500">Nama toko yang akan ditampilkan kepada pelanggan</p>
                    </div>

                    <!-- Email -->
                    <div class="group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <svg class="w-4 h-4 text-primary-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Email Seller
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input 
                                type="email" 
                                name="email" 
                                value="{{ old('email') }}" 
                                class="mt-1 w-full rounded-lg border-gray-300 focus:border-primary-900 focus:ring-2 focus:ring-primary-900/20 px-4 py-3 transition-all duration-200 group-hover:border-primary-500" 
                                placeholder="seller@example.com"
                                required
                            />
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                </svg>
                            </div>
                        </div>
                        <p class="mt-2 text-xs text-gray-500">Email aktif untuk login dan notifikasi</p>
                    </div>
                </div>

                <!-- Kolom Kanan -->
                <div class="space-y-6">
                    <!-- Status -->
                    <div class="group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <svg class="w-4 h-4 text-primary-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Status Toko
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select name="status" class="mt-1 w-full rounded-lg border-gray-300 focus:border-primary-900 focus:ring-2 focus:ring-primary-900/20 px-4 py-3 appearance-none transition-all duration-200 group-hover:border-primary-500">
                                <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-3 space-y-2">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-green-500"></div>
                                <span class="text-xs text-gray-600">Aktif: Toko dapat langsung beroperasi</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-gray-400"></div>
                                <span class="text-xs text-gray-600">Nonaktif: Toko tidak dapat beroperasi sementara</span>
                            </div>
                        </div>
                    </div>

                    <!-- Password Section -->
                    <div class="space-y-4">
                        <div class="group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                <svg class="w-4 h-4 text-primary-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                Password Akun
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input 
                                    type="password" 
                                    name="password" 
                                    class="mt-1 w-full rounded-lg border-gray-300 focus:border-primary-900 focus:ring-2 focus:ring-primary-900/20 px-4 py-3 pr-10 transition-all duration-200 group-hover:border-primary-500" 
                                    placeholder="Minimal 8 karakter"
                                    autocomplete="new-password"
                                    required
                                />
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L6.59 6.59m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                <svg class="w-4 h-4 text-primary-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                                Konfirmasi Password
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input 
                                    type="password" 
                                    name="password_confirmation" 
                                    class="mt-1 w-full rounded-lg border-gray-300 focus:border-primary-900 focus:ring-2 focus:ring-primary-900/20 px-4 py-3 pr-10 transition-all duration-200 group-hover:border-primary-500" 
                                    placeholder="Ulangi password yang sama"
                                    autocomplete="new-password"
                                    required
                                />
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="pt-6 border-t border-gray-100">
                <div class="flex flex-col sm:flex-row gap-4 justify-end">
                    <a href="{{ $backHref }}" class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 hover:text-gray-900 px-6 py-3 text-sm font-medium transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-gradient-to-r from-primary-900 to-primary-800 hover:from-primary-800 hover:to-primary-700 text-white px-6 py-3 text-sm font-semibold shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-[1.02]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Seller Baru
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Info Panel -->
    <div class="bg-primary-50 border border-primary-100 rounded-2xl p-6 mb-6">
        <div class="flex items-start gap-4">
            <div class="p-2 bg-primary-100 rounded-lg">
                <svg class="w-6 h-6 text-primary-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="font-semibold text-primary-900 mb-2">Tips Membuat Seller Baru</h3>
                <ul class="space-y-2 text-sm text-gray-700">
                    <li class="flex items-start gap-2">
                        <div class="w-1.5 h-1.5 rounded-full bg-primary-900 mt-1.5"></div>
                        <span>Pastikan email yang digunakan aktif untuk verifikasi akun</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <div class="w-1.5 h-1.5 rounded-full bg-primary-900 mt-1.5"></div>
                        <span>Gunakan password yang kuat dengan kombinasi huruf, angka, dan simbol</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <div class="w-1.5 h-1.5 rounded-full bg-primary-900 mt-1.5"></div>
                        <span>Status "Aktif" akan membuat toko langsung dapat beroperasi</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Script untuk interaksi -->
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle password visibility (opsional - bisa ditambahkan jika diperlukan)
            const passwordInputs = document.querySelectorAll('input[type="password"]');
            passwordInputs.forEach(input => {
                const parent = input.parentElement;
                const eyeIcon = parent.querySelector('svg');
                
                if (eyeIcon) {
                    eyeIcon.classList.remove('pointer-events-none');
                    eyeIcon.classList.add('cursor-pointer');
                    
                    eyeIcon.addEventListener('click', function() {
                        const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                        input.setAttribute('type', type);
                        
                        // Ganti icon
                        if (type === 'text') {
                            eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`;
                        } else {
                            eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L6.59 6.59m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>`;
                        }
                    });
                }
            });
        });
    </script>
    @endpush

    <style>
        /* Custom scrollbar untuk select */
        select::-webkit-scrollbar {
            width: 8px;
        }
        select::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }
        select::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }
        select::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        
        /* Animasi untuk focus state */
        input:focus, select:focus {
            box-shadow: 0 0 0 3px rgba(var(--primary-900-rgb, 30, 64, 175), 0.1);
        }
        
        /* Smooth transition untuk semua elemen */
        * {
            transition: all 0.2s ease;
        }
    </style>
@endsection