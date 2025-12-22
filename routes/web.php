<?php

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;

Route::get('/', function (Request $request) {
    $q = trim((string) $request->query('q', ''));
    $availability = (string) $request->query('availability', 'all');
    $sort = (string) $request->query('sort', 'newest');

    if (class_exists(\App\Models\Product::class)) {
        $query = Product::query();

        $query->where('status', 'active');

        if (method_exists($query->getModel(), 'seller')) {
            $query->with('seller');
        }

        if ($q !== '') {
            $query->where('name', 'like', '%' . $q . '%');
        }

        if ($availability === 'in_stock') {
            $query->where('stock', '>', 0);
        } elseif ($availability === 'out_of_stock') {
            $query->where('stock', '<=', 0);
        }

        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'oldest':
                $query->orderBy('id', 'asc');
                break;
            case 'newest':
            default:
                $query->orderBy('id', 'desc');
                break;
        }

        $products = $query->paginate(12)->withQueryString();
    } else {
        $products = collect();
    }

    return view('guest.home', compact('products', 'q', 'availability', 'sort'));
})->name('home');

Route::get('/cart', function () {
    $sessionId = session()->getId();
    $userId = Auth::id();

    if ($userId) {
        $cartModel = Cart::query()->firstOrCreate(
            ['user_id' => $userId],
            ['session_id' => $sessionId]
        );
    } else {
        $cartModel = Cart::query()->firstOrCreate(
            ['user_id' => null, 'session_id' => $sessionId],
            ['session_id' => $sessionId]
        );
    }

    $items = $cartModel->items()->with('product')->get();
    $cart = [];
    foreach ($items as $item) {
        $p = $item->product;
        if (!$p) {
            continue;
        }
        $cart[$p->id] = [
            'id' => $p->id,
            'name' => $p->name ?? '-',
            'price' => (float) ($item->price ?? 0),
            'qty' => (int) ($item->qty ?? 0),
        ];
    }

    return view('guest.cart', compact('cart'));
})->name('cart.index');

Route::post('/cart/add/{product}', function (Request $request, $product) {
    $wantsJson = $request->expectsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest';
    $qty = (int) $request->input('qty', 1);
    if ($qty < 1) {
        if ($wantsJson) {
            return response()->json(['message' => 'Qty minimal 1'], 422);
        }
        return back()->with('error', 'Qty minimal 1');
    }

    $p = Product::query()->find($product);
    if (!$p) {
        if ($wantsJson) {
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);
        }
        return back()->with('error', 'Produk tidak ditemukan');
    }
    if (($p->status ?? null) !== 'active') {
        if ($wantsJson) {
            return response()->json(['message' => 'Produk sedang tidak tersedia'], 422);
        }
        return back()->with('error', 'Produk sedang tidak tersedia');
    }
    if (((int) ($p->stock ?? 0)) <= 0) {
        if ($wantsJson) {
            return response()->json(['message' => 'Stok produk habis'], 422);
        }
        return back()->with('error', 'Stok produk habis');
    }

    $sessionId = session()->getId();
    $userId = Auth::id();

    if ($userId) {
        $cartModel = Cart::query()->firstOrCreate(
            ['user_id' => $userId],
            ['session_id' => $sessionId]
        );
    } else {
        $cartModel = Cart::query()->firstOrCreate(
            ['user_id' => null, 'session_id' => $sessionId],
            ['session_id' => $sessionId]
        );
    }

    $existing = CartItem::query()
        ->where('cart_id', $cartModel->id)
        ->where('product_id', $p->id)
        ->first();

    $newQty = $qty + (int) ($existing?->qty ?? 0);
    $newQty = min($newQty, (int) ($p->stock ?? 0));

    CartItem::query()->updateOrCreate(
        ['cart_id' => $cartModel->id, 'product_id' => $p->id],
        ['qty' => $newQty, 'price' => $p->price]
    );

    if ($wantsJson) {
        $cartCount = (int) CartItem::query()
            ->where('cart_id', $cartModel->id)
            ->sum('qty');

        return response()->json([
            'message' => 'Produk ditambahkan ke cart',
            'cartCount' => $cartCount,
        ]);
    }

    return back()->with('success', 'Produk ditambahkan ke cart');
})->name('cart.add');

Route::post('/cart/remove/{product}', function ($product) {
    $wantsJson = request()->expectsJson() || request()->header('X-Requested-With') === 'XMLHttpRequest';
    $sessionId = session()->getId();
    $userId = Auth::id();

    $cartQuery = Cart::query();
    if ($userId) {
        $cartQuery->where('user_id', $userId);
    } else {
        $cartQuery->whereNull('user_id')->where('session_id', $sessionId);
    }
    $cartModel = $cartQuery->first();
    if ($cartModel) {
        CartItem::query()
            ->where('cart_id', $cartModel->id)
            ->where('product_id', $product)
            ->delete();
    }

    if ($wantsJson) {
        if (!$cartModel) {
            return response()->json([
                'message' => 'Item dihapus dari cart',
                'cartCount' => 0,
                'itemLines' => 0,
                'subtotal' => 0,
                'shipping' => 0,
                'total' => 0,
                'items' => (object) [],
            ]);
        }

        $cartItems = $cartModel->items()->with('product')->get();

        $payloadItems = [];
        $subtotal = 0;
        $cartCount = 0;
        $itemLines = 0;

        foreach ($cartItems as $ci) {
            $p = $ci->product;
            if (!$p) {
                continue;
            }

            $qty = (int) ($ci->qty ?? 0);
            $price = (float) ($ci->price ?? 0);
            $lineSubtotal = $price * $qty;

            $itemLines++;
            $cartCount += $qty;
            $subtotal += $lineSubtotal;

            $payloadItems[$p->id] = [
                'qty' => $qty,
                'price' => $price,
                'subtotal' => $lineSubtotal,
            ];
        }

        $shipping = 0;
        $total = $subtotal + $shipping;

        return response()->json([
            'message' => 'Item dihapus dari cart',
            'cartCount' => $cartCount,
            'itemLines' => $itemLines,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'total' => $total,
            'items' => $payloadItems,
        ]);
    }

    return back()->with('success', 'Item dihapus dari cart');
})->name('cart.remove');

Route::post('/cart/update', function (Request $request) {
    $wantsJson = $request->expectsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest';
    $items = (array) $request->input('items', []);

    $sessionId = session()->getId();
    $userId = Auth::id();

    $cartQuery = Cart::query();
    if ($userId) {
        $cartQuery->where('user_id', $userId);
    } else {
        $cartQuery->whereNull('user_id')->where('session_id', $sessionId);
    }
    $cartModel = $cartQuery->first();
    if (!$cartModel) {
        if ($wantsJson) {
            return response()->json([
                'message' => 'Cart diperbarui',
                'cartCount' => 0,
                'itemLines' => 0,
                'subtotal' => 0,
                'shipping' => 0,
                'total' => 0,
                'items' => (object) [],
            ]);
        }
        return back()->with('success', 'Cart diperbarui');
    }

    foreach ($items as $productId => $qty) {
        $qty = (int) $qty;

        $cartItem = CartItem::query()
            ->where('cart_id', $cartModel->id)
            ->where('product_id', $productId)
            ->first();
        if (!$cartItem) {
            continue;
        }

        if ($qty < 1) {
            $cartItem->delete();
            continue;
        }

        $product = Product::query()->find($productId);
        if ($product) {
            $qty = min($qty, (int) ($product->stock ?? 0));
            $cartItem->price = $product->price;
        }

        $cartItem->qty = $qty;
        $cartItem->save();
    }

    if ($wantsJson) {
        $cartItems = $cartModel->items()->with('product')->get();

        $payloadItems = [];
        $subtotal = 0;
        $cartCount = 0;
        $itemLines = 0;

        foreach ($cartItems as $ci) {
            $product = $ci->product;
            if (!$product) {
                continue;
            }

            $qty = (int) ($ci->qty ?? 0);
            $price = (float) ($ci->price ?? 0);
            $lineSubtotal = $price * $qty;

            $itemLines++;
            $cartCount += $qty;
            $subtotal += $lineSubtotal;

            $payloadItems[$product->id] = [
                'qty' => $qty,
                'price' => $price,
                'subtotal' => $lineSubtotal,
            ];
        }

        $shipping = 0;
        $total = $subtotal + $shipping;

        return response()->json([
            'message' => 'Cart diperbarui',
            'cartCount' => $cartCount,
            'itemLines' => $itemLines,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'total' => $total,
            'items' => $payloadItems,
        ]);
    }

    return back()->with('success', 'Cart diperbarui');
})->name('cart.update');

Route::get('/checkout', function () {
    $sessionId = session()->getId();
    $userId = Auth::id();

    $cartQuery = Cart::query();
    if ($userId) {
        $cartQuery->where('user_id', $userId);
    } else {
        $cartQuery->whereNull('user_id')->where('session_id', $sessionId);
    }
    $cartModel = $cartQuery->first();

    $cart = [];
    if ($cartModel) {
        $items = $cartModel->items()->with('product')->get();
        foreach ($items as $item) {
            $p = $item->product;
            if (!$p) {
                continue;
            }
            $cart[$p->id] = [
                'id' => $p->id,
                'name' => $p->name ?? '-',
                'price' => (float) ($item->price ?? 0),
                'qty' => (int) ($item->qty ?? 0),
            ];
        }
    }

    return view('guest.checkout', compact('cart'));
})->name('checkout.index');

Route::post('/checkout', function (Request $request) {
    $sessionId = session()->getId();
    $userId = Auth::id();

    $cartQuery = Cart::query();
    if ($userId) {
        $cartQuery->where('user_id', $userId);
    } else {
        $cartQuery->whereNull('user_id')->where('session_id', $sessionId);
    }
    $cartModel = $cartQuery->first();

    if (!$cartModel) {
        return back()->with('error', 'Cart masih kosong');
    }

    $cartItems = $cartModel->items()->with('product')->get();
    if ($cartItems->isEmpty()) {
        return back()->with('error', 'Cart masih kosong');
    }

    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'max:255'],
        'address' => ['required', 'string', 'max:500'],
    ]);

    try {
        DB::transaction(function () use ($request, $userId, $cartModel, $cartItems) {
            $subtotal = 0;
            foreach ($cartItems as $ci) {
                $product = $ci->product;
                if (!$product) {
                    continue;
                }
                $lineSubtotal = ((float) ($ci->price ?? 0)) * ((int) ($ci->qty ?? 0));
                $subtotal += $lineSubtotal;
            }

            $shipping = 0;
            $total = $subtotal + $shipping;

            $order = Order::query()->create([
                'user_id' => $userId,
                'name' => (string) $request->input('name'),
                'email' => (string) $request->input('email'),
                'address' => (string) $request->input('address'),
                'subtotal' => $subtotal,
                'shipping' => $shipping,
                'total' => $total,
                'status' => 'pending',
            ]);

            foreach ($cartItems as $ci) {
                $product = $ci->product;
                if (!$product) {
                    continue;
                }

                $qty = (int) ($ci->qty ?? 0);
                if ($qty < 1) {
                    continue;
                }

                $product->refresh();
                if (((int) ($product->stock ?? 0)) < $qty) {
                    throw new \RuntimeException('Stok tidak cukup untuk produk: ' . ($product->name ?? ''));
                }

                $price = (float) ($ci->price ?? $product->price ?? 0);
                $lineSubtotal = $price * $qty;

                OrderItem::query()->create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => (string) ($product->name ?? '-'),
                    'price' => $price,
                    'qty' => $qty,
                    'subtotal' => $lineSubtotal,
                ]);

                $product->stock = (int) ($product->stock ?? 0) - $qty;
                $product->sold_count = (int) ($product->sold_count ?? 0) + $qty;
                $product->save();
            }

            CartItem::query()->where('cart_id', $cartModel->id)->delete();
        });
    } catch (\Throwable $e) {
        return back()->withInput()->with('error', $e->getMessage());
    }

    return redirect()->route('home')->with('success', 'Checkout berhasil (simulasi).');
})->name('checkout.store');

Route::get('/login/seller', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if (($user->role ?? null) === 'seller') {
            return redirect()->route('seller.products.index');
        }
        if (($user->role ?? null) === 'superadmin') {
            return redirect()->route('superadmin.sellers.index');
        }
    }

    return view('auth.seller-login');
})->name('seller.login');

Route::post('/login/seller', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required', 'string'],
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        $user = Auth::user();
        if (($user->role ?? null) !== 'seller') {
            Auth::logout();
            return back()->withInput()->with('error', 'Akun ini bukan seller.');
        }
        return redirect()->route('seller.products.index');
    }

    return back()->withInput()->with('error', 'Email atau password salah.');
})->name('seller.login.submit');

Route::get('/login/superadmin', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if (($user->role ?? null) === 'superadmin') {
            return redirect()->route('superadmin.sellers.index');
        }
        if (($user->role ?? null) === 'seller') {
            return redirect()->route('seller.products.index');
        }
    }

    return view('auth.superadmin-login');
})->name('superadmin.login');

Route::post('/login/superadmin', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required', 'string'],
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        $user = Auth::user();
        if (($user->role ?? null) !== 'superadmin') {
            Auth::logout();
            return back()->withInput()->with('error', 'Akun ini bukan superadmin.');
        }
        return redirect()->route('superadmin.sellers.index');
    }

    return back()->withInput()->with('error', 'Email atau password salah.');
})->name('superadmin.login.submit');

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('home');
})->name('logout');

Route::prefix('superadmin')->name('superadmin.')->middleware(['role:superadmin'])->group(function () {
    Route::prefix('sellers')->name('sellers.')->group(function () {
        Route::get('/', function () {
            $sellers = class_exists(\App\Models\Seller::class)
                ? \App\Models\Seller::query()->get()
                : collect();

            return view('superadmin.sellers.index', compact('sellers'));
        })->name('index');

        Route::get('/create', function () {
            return view('superadmin.sellers.create');
        })->name('create');

        Route::post('/', function (Request $request) {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', 'unique:sellers,email', 'unique:users,email'],
                'status' => ['required', 'in:active,inactive'],
                'password' => ['required', 'string', 'min:6', 'confirmed'],
            ]);

            if (class_exists(\App\Models\Seller::class)) {
                try {
                    DB::transaction(function () use ($validated) {
                        $sellerModel = \App\Models\Seller::query()->create([
                            'name' => $validated['name'],
                            'email' => $validated['email'],
                            'status' => $validated['status'],
                        ]);

                        if (class_exists(\App\Models\User::class)) {
                            \App\Models\User::query()->create([
                                'name' => $validated['name'],
                                'email' => $validated['email'],
                                'password' => Hash::make($validated['password']),
                                'role' => 'seller',
                                'seller_id' => $sellerModel->id,
                            ]);
                        }
                    });
                } catch (\Throwable $e) {
                    return back()->withInput()->with('error', $e->getMessage());
                }
            }

            return redirect()->route('superadmin.sellers.index')->with('success', 'Seller berhasil disimpan');
        })->name('store');

        Route::get('/{seller}/edit', function ($seller) {
            if (!class_exists(\App\Models\Seller::class)) {
                abort(404);
            }

            $sellerModel = \App\Models\Seller::query()->find($seller);
            if (!$sellerModel) {
                abort(404);
            }

            return view('superadmin.sellers.edit', ['seller' => $sellerModel]);
        })->name('edit');

        Route::put('/{seller}', function (Request $request, $seller) {
            if (!class_exists(\App\Models\Seller::class)) {
                abort(404);
            }

            $sellerModel = \App\Models\Seller::query()->find($seller);
            if (!$sellerModel) {
                abort(404);
            }

            $linkedUser = null;
            if (class_exists(\App\Models\User::class)) {
                $linkedUser = \App\Models\User::query()
                    ->where('seller_id', $sellerModel->id)
                    ->where('role', 'seller')
                    ->first();
            }

            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', Rule::unique('sellers', 'email')->ignore($sellerModel->id)],
                'status' => ['required', 'in:active,inactive'],
                'password' => ['nullable', 'string', 'min:6', 'confirmed'],
            ]);

            if (class_exists(\App\Models\User::class)) {
                $emailUsedByOther = \App\Models\User::query()
                    ->where('email', $validated['email'])
                    ->when($linkedUser, function ($q) use ($linkedUser) {
                        $q->where('id', '!=', $linkedUser->id);
                    })
                    ->exists();
                if ($emailUsedByOther) {
                    return back()->withInput()->withErrors([
                        'email' => 'Email sudah digunakan untuk akun lain.',
                    ]);
                }
            }

            try {
                DB::transaction(function () use ($validated, $sellerModel, $linkedUser) {
                    $sellerModel->fill($validated);
                    $sellerModel->save();

                    if (!class_exists(\App\Models\User::class)) {
                        return;
                    }

                    $user = $linkedUser;
                    if ($user) {
                        $user->name = $validated['name'];
                        $user->email = $validated['email'];
                        $user->role = 'seller';
                        $user->seller_id = $sellerModel->id;
                        if (!empty($validated['password'])) {
                            $user->password = Hash::make($validated['password']);
                        }
                        $user->save();
                        return;
                    }

                    if (empty($validated['password'])) {
                        throw new \RuntimeException('Seller ini belum memiliki akun. Isi password untuk membuat akun seller.');
                    }

                    \App\Models\User::query()->create([
                        'name' => $validated['name'],
                        'email' => $validated['email'],
                        'password' => Hash::make((string) $validated['password']),
                        'role' => 'seller',
                        'seller_id' => $sellerModel->id,
                    ]);
                });
            } catch (\Throwable $e) {
                return back()->withInput()->with('error', $e->getMessage());
            }

            return redirect()->route('superadmin.sellers.index')->with('success', 'Seller berhasil diperbarui');
        })->name('update');

        Route::delete('/{seller}', function ($seller) {
            if (!class_exists(\App\Models\Seller::class)) {
                abort(404);
            }

            $sellerModel = \App\Models\Seller::query()->find($seller);
            if (!$sellerModel) {
                abort(404);
            }

            if (class_exists(\App\Models\User::class)) {
                \App\Models\User::query()
                    ->where('seller_id', $sellerModel->id)
                    ->where('role', 'seller')
                    ->delete();
            }

            $sellerModel->delete();

            return redirect()->route('superadmin.sellers.index')->with('success', 'Seller berhasil dihapus');
        })->name('destroy');
    });

    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', function (Request $request) {
            $sellers = class_exists(\App\Models\Seller::class)
                ? \App\Models\Seller::query()->get()
                : collect();

            if (class_exists(\App\Models\Product::class)) {
                $query = \App\Models\Product::query();
                if (method_exists($query->getModel(), 'seller')) {
                    $query->with('seller');
                }
                if ($request->filled('seller_id')) {
                    $query->where('seller_id', $request->input('seller_id'));
                }
                $products = $query->get();
            } else {
                $products = collect();
            }

            return view('superadmin.products.index', compact('products', 'sellers'));
        })->name('index');

        Route::get('/create', function () {
            $sellers = class_exists(\App\Models\Seller::class)
                ? \App\Models\Seller::query()->get()
                : collect();

            return view('superadmin.products.create', compact('sellers'));
        })->name('create');

        Route::post('/', function (Request $request) {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'seller_id' => ['required', 'integer'],
                'price' => ['required', 'numeric', 'gt:0'],
                'stock' => ['required', 'integer', 'min:0'],
                'status' => ['required', 'in:active,inactive'],
                'description' => ['nullable', 'string'],
                'image_url' => ['nullable', 'string', 'max:2048'],
            ]);

            if (class_exists(\App\Models\Seller::class) && class_exists(\App\Models\Product::class)) {
                try {
                    $seller = \App\Models\Seller::query()->findOrFail($validated['seller_id']);

                    $sellerStatus = $seller->status ?? null;
                    $isInactive = $sellerStatus === 'inactive' || $sellerStatus === 'Nonaktif';
                    if ($isInactive) {
                        return back()->withInput()->withErrors([
                            'seller_id' => 'Produk tidak dapat ditambahkan jika seller berstatus Nonaktif.',
                        ]);
                    }

                    \App\Models\Product::query()->create($validated);
                } catch (\Throwable $e) {
                    return back()->withInput()->with('error', $e->getMessage());
                }
            }

            return redirect()->route('superadmin.products.index')->with('success', 'Produk berhasil disimpan');
        })->name('store');

        Route::get('/{product}/edit', function ($product) {
            if (!class_exists(\App\Models\Product::class)) {
                abort(404);
            }

            $productModel = \App\Models\Product::query()->find($product);
            if (!$productModel) {
                abort(404);
            }

            $sellers = class_exists(\App\Models\Seller::class)
                ? \App\Models\Seller::query()->get()
                : collect();

            return view('superadmin.products.edit', ['product' => $productModel, 'sellers' => $sellers]);
        })->name('edit');

        Route::put('/{product}', function (Request $request, $product) {
            if (!class_exists(\App\Models\Product::class)) {
                abort(404);
            }

            $productModel = \App\Models\Product::query()->find($product);
            if (!$productModel) {
                abort(404);
            }

            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'seller_id' => ['required', 'integer'],
                'price' => ['required', 'numeric', 'gt:0'],
                'stock' => ['required', 'integer', 'min:0'],
                'status' => ['required', 'in:active,inactive'],
                'description' => ['nullable', 'string'],
                'image_url' => ['nullable', 'string', 'max:2048'],
            ]);

            if (class_exists(\App\Models\Seller::class)) {
                $seller = \App\Models\Seller::query()->find($validated['seller_id']);
                if (!$seller) {
                    return back()->withInput()->withErrors([
                        'seller_id' => 'Seller tidak ditemukan.',
                    ]);
                }

                $sellerStatus = $seller->status ?? null;
                $isInactive = $sellerStatus === 'inactive' || $sellerStatus === 'Nonaktif';
                if ($isInactive) {
                    return back()->withInput()->withErrors([
                        'seller_id' => 'Produk tidak dapat dipindahkan/disimpan ke seller berstatus Nonaktif.',
                    ]);
                }
            }

            $productModel->fill($validated);
            $productModel->save();

            return redirect()->route('superadmin.products.index')->with('success', 'Produk berhasil diperbarui');
        })->name('update');

        Route::delete('/{product}', function ($product) {
            if (!class_exists(\App\Models\Product::class)) {
                abort(404);
            }

            $productModel = \App\Models\Product::query()->find($product);
            if (!$productModel) {
                abort(404);
            }

            $productModel->delete();

            return redirect()->route('superadmin.products.index')->with('success', 'Produk berhasil dihapus');
        })->name('destroy');
    });
});

Route::prefix('seller')->name('seller.')->middleware(['role:seller'])->group(function () {
    Route::prefix('store')->name('store.')->group(function () {
        Route::get('/', function () {
            $user = Auth::user();
            $sellerId = $user?->seller_id;
            if (!$sellerId || !class_exists(\App\Models\Seller::class)) {
                return back()->with('error', 'Seller belum terhubung dengan akun ini.');
            }

            $seller = \App\Models\Seller::query()->find($sellerId);
            if (!$seller) {
                return back()->with('error', 'Data toko tidak ditemukan.');
            }

            return view('seller.store.edit', compact('seller'));
        })->name('edit');

        Route::put('/', function (Request $request) {
            $user = Auth::user();
            $sellerId = $user?->seller_id;
            if (!$sellerId || !class_exists(\App\Models\Seller::class)) {
                return back()->with('error', 'Seller belum terhubung dengan akun ini.');
            }

            $seller = \App\Models\Seller::query()->find($sellerId);
            if (!$seller) {
                return back()->with('error', 'Data toko tidak ditemukan.');
            }

            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
            ]);

            $seller->name = $validated['name'];
            $seller->save();

            return back()->with('success', 'Toko berhasil diperbarui');
        })->name('update');
    });

    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', function () {
            $user = Auth::user();
            $sellerId = $user?->seller_id;

            if (class_exists(\App\Models\Product::class) && $sellerId) {
                $products = \App\Models\Product::query()->where('seller_id', $sellerId)->get();
            } else {
                $products = collect();
            }

            $seller = null;
            if (class_exists(\App\Models\Seller::class) && $sellerId) {
                $seller = \App\Models\Seller::query()->find($sellerId);
            }

            return view('seller.products.index', compact('products', 'seller'));
        })->name('index');

        Route::get('/create', function () {
            return view('seller.products.create');
        })->name('create');

        Route::post('/', function (Request $request) {
            $user = Auth::user();
            $sellerId = $user?->seller_id;
            if (!$sellerId) {
                return back()->withInput()->with('error', 'Seller belum terhubung dengan akun ini.');
            }

            if (class_exists(\App\Models\Seller::class)) {
                $seller = \App\Models\Seller::query()->find($sellerId);
                if ($seller && (($seller->status ?? null) === 'inactive')) {
                    return back()->withInput()->with('error', 'Produk tidak dapat ditambahkan jika seller berstatus Nonaktif.');
                }
            }

            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'price' => ['required', 'numeric', 'gt:0'],
                'stock' => ['required', 'integer', 'min:0'],
                'status' => ['required', 'in:active,inactive'],
                'description' => ['nullable', 'string'],
                'image_url' => ['nullable', 'string', 'max:2048'],
            ]);

            $validated['seller_id'] = $sellerId;

            if (class_exists(\App\Models\Product::class)) {
                try {
                    \App\Models\Product::query()->create($validated);
                } catch (\Throwable $e) {
                    return back()->withInput()->with('error', $e->getMessage());
                }
            }

            return redirect()->route('seller.products.index')->with('success', 'Produk berhasil disimpan');
        })->name('store');

        Route::get('/{product}/edit', function ($product) {
            $user = Auth::user();
            $sellerId = $user?->seller_id;
            if (!$sellerId || !class_exists(\App\Models\Product::class)) {
                return back()->with('error', 'Seller belum terhubung dengan akun ini.');
            }

            $productModel = \App\Models\Product::query()
                ->where('seller_id', $sellerId)
                ->where('id', $product)
                ->first();

            if (!$productModel) {
                abort(404);
            }

            return view('seller.products.edit', ['product' => $productModel]);
        })->name('edit');

        Route::put('/{product}', function (Request $request, $product) {
            $user = Auth::user();
            $sellerId = $user?->seller_id;
            if (!$sellerId || !class_exists(\App\Models\Product::class)) {
                return back()->with('error', 'Seller belum terhubung dengan akun ini.');
            }

            $productModel = \App\Models\Product::query()
                ->where('seller_id', $sellerId)
                ->where('id', $product)
                ->first();

            if (!$productModel) {
                abort(404);
            }

            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'price' => ['required', 'numeric', 'gt:0'],
                'stock' => ['required', 'integer', 'min:0'],
                'status' => ['required', 'in:active,inactive'],
                'description' => ['nullable', 'string'],
                'image_url' => ['nullable', 'string', 'max:2048'],
            ]);

            $productModel->fill($validated);
            $productModel->save();

            return redirect()->route('seller.products.index')->with('success', 'Produk berhasil diperbarui');
        })->name('update');

        Route::delete('/{product}', function ($product) {
            $user = Auth::user();
            $sellerId = $user?->seller_id;
            if (!$sellerId || !class_exists(\App\Models\Product::class)) {
                return back()->with('error', 'Seller belum terhubung dengan akun ini.');
            }

            $productModel = \App\Models\Product::query()
                ->where('seller_id', $sellerId)
                ->where('id', $product)
                ->first();

            if (!$productModel) {
                abort(404);
            }

            $productModel->delete();

            return redirect()->route('seller.products.index')->with('success', 'Produk berhasil dihapus');
        })->name('destroy');
    });
});
