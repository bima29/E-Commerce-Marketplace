<footer class="bg-gradient-to-b from-white to-gray-50 border-t border-gray-200 mt-auto">
    <div class="container mx-auto px-4 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div>
                <div class="flex items-center space-x-3 mb-6">
                    <div class="h-10 w-10 rounded-lg bg-primary-900 flex items-center justify-center">
                        <i class="fas fa-shopping-bag text-white text-xl"></i>
                    </div>
                    <span class="text-2xl font-bold text-primary-900">Market<span class="text-primary-600">Place</span></span>
                </div>
                <p class="text-gray-600 mb-6">
                    Marketplace terpercaya dengan produk berkualitas dari seller terbaik di Indonesia.
                </p>
                <div class="flex space-x-4">
                    <a href="#" class="h-10 w-10 rounded-full bg-primary-50 hover:bg-primary-100 flex items-center justify-center text-primary-900 transition-colors">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="h-10 w-10 rounded-full bg-primary-50 hover:bg-primary-100 flex items-center justify-center text-primary-900 transition-colors">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="h-10 w-10 rounded-full bg-primary-50 hover:bg-primary-100 flex items-center justify-center text-primary-900 transition-colors">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="h-10 w-10 rounded-full bg-primary-50 hover:bg-primary-100 flex items-center justify-center text-primary-900 transition-colors">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-6">Tautan Cepat</h3>
                <ul class="space-y-3">
                    <li>
                        <a href="{{ route('home') ?? '/' }}" class="text-gray-600 hover:text-primary-900 transition-colors flex items-center">
                            <i class="fas fa-chevron-right text-xs mr-2 text-primary-600"></i>
                            Semua Produk
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-gray-600 hover:text-primary-900 transition-colors flex items-center">
                            <i class="fas fa-chevron-right text-xs mr-2 text-primary-600"></i>
                            Produk Terlaris
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-gray-600 hover:text-primary-900 transition-colors flex items-center">
                            <i class="fas fa-chevron-right text-xs mr-2 text-primary-600"></i>
                            Promo Spesial
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-gray-600 hover:text-primary-900 transition-colors flex items-center">
                            <i class="fas fa-chevron-right text-xs mr-2 text-primary-600"></i>
                            Seller Terbaik
                        </a>
                    </li>
                </ul>
            </div>

            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-6">Tetap Terhubung</h3>
                <p class="text-gray-600 mb-4">
                    Dapatkan update promo dan produk terbaru langsung ke email Anda.
                </p>
                <form class="space-y-3">
                    <div class="relative">
                        <input 
                            type="email" 
                            placeholder="Email Anda"
                            class="w-full pl-4 pr-12 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-900 focus:border-transparent"
                        >
                        <button type="submit" class="absolute right-2 top-2 bg-primary-900 hover:bg-primary-800 text-white px-4 py-1.5 rounded-md font-medium transition-colors">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="border-t border-gray-200 mt-10 pt-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-600 text-sm mb-4 md:mb-0">
                    <i class="far fa-copyright mr-1"></i> {{ date('Y') }} <span class="font-semibold text-primary-900">MarketPlace</span>. All rights reserved.
                </p>
                <div class="flex items-center space-x-6 text-sm text-gray-600">
                    <a href="#" class="hover:text-primary-900 transition-colors">Syarat & Ketentuan</a>
                    <a href="#" class="hover:text-primary-900 transition-colors">Kebijakan Privasi</a>
                    <a href="#" class="hover:text-primary-900 transition-colors">Peta Situs</a>
                </div>
            </div>
        </div>
    </div>
</footer>