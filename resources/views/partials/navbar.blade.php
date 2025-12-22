<nav class="sticky top-0 z-50 bg-white border-b border-gray-200 shadow-sm">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <a href="/" class="flex items-center space-x-2">
                <div class="h-9 w-9 rounded-lg bg-primary-900 flex items-center justify-center">
                    <i class="fas fa-shopping-bag text-white text-lg"></i>
                </div>
                <span class="text-xl font-bold text-primary-900 hidden sm:inline">ECOMMERCE<span class="text-primary-600"> TEST</span></span>
                <span class="text-xl font-bold text-primary-900 sm:hidden">MP</span>
            </a>

            @php
                $productsHref = Route::has('home') ? route('home') : url('/');
                $cartHref = Route::has('cart.index') ? route('cart.index') : url('/cart');

                $cartCount = 0;
                $sessionId = session()->getId();
                $userId = \Illuminate\Support\Facades\Auth::id();
                $cartQuery = \App\Models\Cart::query();
                if ($userId) {
                    $cartQuery->where('user_id', $userId);
                } else {
                    $cartQuery->whereNull('user_id')->where('session_id', $sessionId);
                }
                $cartModel = $cartQuery->first();
                if ($cartModel) {
                    $cartCount = (int) \App\Models\CartItem::query()
                        ->where('cart_id', $cartModel->id)
                        ->sum('qty');
                }

                $isProductsActive = request()->routeIs('home') || request()->is('/');
                $isCartActive = request()->routeIs('cart.*') || request()->is('cart') || request()->is('cart/*');
                $isSellerActive = request()->is('seller') || request()->is('seller/*') || request()->is('login/seller');
                $isAdminActive = request()->is('superadmin') || request()->is('superadmin/*') || request()->is('login/superadmin');

                $navLinkBase = 'text-gray-700 hover:text-primary-900 transition-colors duration-200 font-medium';
                $navLinkActive = 'text-primary-900 font-semibold';
                $navLinkInactive = '';

                $productsClass = $navLinkBase.' '.($isProductsActive ? $navLinkActive : $navLinkInactive);
                $cartClass = $navLinkBase.' '.($isCartActive ? $navLinkActive : $navLinkInactive);
                $sellerClass = $navLinkBase.' '.($isSellerActive ? $navLinkActive : $navLinkInactive);

                $mobileItemBase = 'flex items-center py-2 px-3 rounded-lg transition-colors';
                $mobileActive = 'bg-primary-50 text-primary-900 font-semibold';
                $mobileInactive = 'text-gray-700 hover:text-primary-900 hover:bg-primary-50/50';
            @endphp

            <div class="hidden md:flex items-center space-x-8">
                <div class="flex items-center space-x-6">
                    <a href="{{ $productsHref }}" class="{{ $productsClass }}">
                        <i class="fas fa-store mr-2"></i>Produk
                    </a>
                    <a href="{{ $cartHref }}" class="{{ $cartClass }} relative">
                        <i class="fas fa-shopping-cart mr-2"></i>Keranjang
                        <span data-cart-count-badge class="absolute -top-2 -right-4 bg-primary-900 text-white text-xs rounded-full h-5 min-w-5 px-1 flex items-center justify-center">
                            {{ $cartCount }}
                        </span>
                    </a>
                </div>
                
                <div class="h-5 w-px bg-gray-300"></div>
                
                <div class="flex items-center space-x-4">
                    @auth
                        @php
                            $dashboardHref = '/';
                            if ((auth()->user()->role ?? null) === 'seller') {
                                $dashboardHref = Route::has('seller.products.index') ? route('seller.products.index') : url('/seller/products');
                            }
                            if ((auth()->user()->role ?? null) === 'superadmin') {
                                $dashboardHref = Route::has('superadmin.sellers.index') ? route('superadmin.sellers.index') : url('/superadmin/sellers');
                            }

                            $logoutAction = Route::has('logout') ? route('logout') : url('/logout');
                        @endphp

                        <a href="{{ $dashboardHref }}" class="text-gray-700 hover:text-primary-900 transition-colors duration-200 font-semibold">
                            <i class="fas fa-gauge mr-2"></i>Dashboard
                        </a>

                        <form method="POST" action="{{ $logoutAction }}">
                            @csrf
                            <button type="submit" class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                                <i class="fas fa-right-from-bracket mr-2"></i>Logout
                            </button>
                        </form>
                    @else
                        <a href="/login/seller" class="{{ $sellerClass }}">
                            <i class="fas fa-user-tie mr-2"></i>Seller
                        </a>
                        <a href="/login/superadmin" class="bg-gradient-to-r from-primary-900 to-primary-800 hover:from-primary-800 hover:to-primary-700 text-white px-5 py-2 rounded-lg font-semibold transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 {{ $isAdminActive ? 'ring-2 ring-primary-200' : '' }}">
                            <i class="fas fa-shield-alt mr-2"></i>Admin
                        </a>
                    @endauth
                </div>
            </div>

            <button id="mobile-menu-button" class="md:hidden text-gray-700 hover:text-primary-900 p-2">
                <i class="fas fa-bars text-xl"></i>
            </button>
        </div>

        <div id="mobile-menu" class="md:hidden hidden border-t border-gray-200 py-4 px-4 bg-white">
            <div class="flex flex-col space-y-4">
                <a href="{{ $productsHref }}" class="{{ $mobileItemBase }} {{ $isProductsActive ? $mobileActive : $mobileInactive }}">
                    <i class="fas fa-store w-6 mr-3"></i>
                    <span class="font-medium">Produk</span>
                </a>
                <a href="{{ $cartHref }}" class="{{ $mobileItemBase }} {{ $isCartActive ? $mobileActive : $mobileInactive }}">
                    <i class="fas fa-shopping-cart w-6 mr-3"></i>
                    <span class="font-medium">Keranjang</span>
                    <span data-cart-count-badge class="ml-auto bg-primary-900 text-white text-xs rounded-full h-6 min-w-6 px-2 flex items-center justify-center">{{ $cartCount }}</span>
                </a>
                <div class="border-t border-gray-200 pt-4 mt-2">
                    @auth
                        @php
                            $dashboardHref = '/';
                            if ((auth()->user()->role ?? null) === 'seller') {
                                $dashboardHref = Route::has('seller.products.index') ? route('seller.products.index') : url('/seller/products');
                            }
                            if ((auth()->user()->role ?? null) === 'superadmin') {
                                $dashboardHref = Route::has('superadmin.sellers.index') ? route('superadmin.sellers.index') : url('/superadmin/sellers');
                            }

                            $logoutAction = Route::has('logout') ? route('logout') : url('/logout');
                        @endphp

                        <a href="{{ $dashboardHref }}" class="{{ $mobileItemBase }} {{ $mobileInactive }} mb-3">
                            <i class="fas fa-gauge w-6 mr-3"></i>
                            <span class="font-medium">Dashboard</span>
                        </a>

                        <form method="POST" action="{{ $logoutAction }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center bg-primary-900 text-white hover:bg-primary-800 px-4 py-3 rounded-lg font-medium">
                                <i class="fas fa-right-from-bracket w-6 mr-3"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    @else
                        <a href="/login/seller" class="{{ $mobileItemBase }} {{ $isSellerActive ? $mobileActive : $mobileInactive }} mb-3">
                            <i class="fas fa-user-tie w-6 mr-3"></i>
                            <span class="font-medium">Login Seller</span>
                        </a>
                        <a href="/login/superadmin" class="flex items-center bg-primary-900 text-white hover:bg-primary-800 px-4 py-3 rounded-lg font-medium {{ $isAdminActive ? 'ring-2 ring-primary-200' : '' }}">
                            <i class="fas fa-shield-alt w-6 mr-3"></i>
                            <span>Super Admin</span>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
    document.getElementById('mobile-menu-button').addEventListener('click', function() {
        const menu = document.getElementById('mobile-menu');
        const icon = this.querySelector('i');
        
        if (menu.classList.contains('hidden')) {
            menu.classList.remove('hidden');
            icon.classList.remove('fa-bars');
            icon.classList.add('fa-times');
        } else {
            menu.classList.add('hidden');
            icon.classList.remove('fa-times');
            icon.classList.add('fa-bars');
        }
    });
</script>