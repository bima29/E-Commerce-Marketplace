<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $products = class_exists(\App\Models\Product::class)
        ? \App\Models\Product::query()->when(
            method_exists((new \App\Models\Product()), 'seller'),
            fn ($q) => $q->with('seller')
        )->get()
        : collect();

    return view('guest.home', compact('products'));
})->name('home');

Route::get('/cart', function () {
    $cart = session('cart', []);
    return view('guest.cart', compact('cart'));
})->name('cart.index');

Route::post('/cart/add/{product}', function (Request $request, $product) {
    $qty = (int) $request->input('qty', 1);
    if ($qty < 1) {
        return back()->with('error', 'Qty minimal 1');
    }

    $cart = session('cart', []);

    if (class_exists(\App\Models\Product::class)) {
        $p = \App\Models\Product::query()->find($product);
        if (!$p) {
            return back()->with('error', 'Produk tidak ditemukan');
        }

        $id = $p->id;
        $cart[$id] = [
            'id' => $id,
            'name' => $p->name ?? '-',
            'price' => (float) ($p->price ?? 0),
            'qty' => (int) (($cart[$id]['qty'] ?? 0) + $qty),
        ];
        session(['cart' => $cart]);
        return back()->with('success', 'Produk ditambahkan ke cart');
    }

    return back()->with('error', 'Model Product belum tersedia');
})->name('cart.add');

Route::post('/cart/remove/{product}', function ($product) {
    $cart = session('cart', []);
    unset($cart[$product]);
    session(['cart' => $cart]);
    return back()->with('success', 'Item dihapus dari cart');
})->name('cart.remove');

Route::post('/cart/update', function (Request $request) {
    $items = (array) $request->input('items', []);
    $cart = session('cart', []);

    foreach ($items as $id => $qty) {
        $qty = (int) $qty;
        if (!isset($cart[$id])) {
            continue;
        }
        if ($qty < 1) {
            unset($cart[$id]);
            continue;
        }
        $cart[$id]['qty'] = $qty;
    }

    session(['cart' => $cart]);
    return back()->with('success', 'Cart diperbarui');
})->name('cart.update');

Route::get('/checkout', function () {
    $cart = session('cart', []);
    return view('guest.checkout', compact('cart'));
})->name('checkout.index');

Route::post('/checkout', function (Request $request) {
    $cart = session('cart', []);
    if (empty($cart)) {
        return back()->with('error', 'Cart masih kosong');
    }

    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'max:255'],
        'address' => ['required', 'string', 'max:500'],
    ]);

    session()->forget('cart');
    return redirect()->route('home')->with('success', 'Checkout berhasil (simulasi).');
})->name('checkout.store');

Route::get('/login/seller', function () {
    return redirect()->route('seller.products.index');
});

Route::get('/login/superadmin', function () {
    return redirect()->route('superadmin.sellers.index');
});

Route::prefix('superadmin')->name('superadmin.')->group(function () {
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
                'email' => ['required', 'email', 'max:255'],
                'status' => ['required', 'in:active,inactive'],
            ]);

            if (class_exists(\App\Models\Seller::class)) {
                try {
                    \App\Models\Seller::query()->create($validated);
                } catch (\Throwable $e) {
                    return back()->withInput()->with('error', $e->getMessage());
                }
            }

            return redirect()->route('superadmin.sellers.index')->with('success', 'Seller berhasil disimpan');
        })->name('store');
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
    });
});

Route::prefix('seller')->name('seller.')->group(function () {
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', function () {
            $products = class_exists(\App\Models\Product::class)
                ? \App\Models\Product::query()->get()
                : collect();

            return view('seller.products.index', compact('products'));
        })->name('index');

        Route::get('/create', function () {
            return view('seller.products.create');
        })->name('create');

        Route::post('/', function (Request $request) {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'price' => ['required', 'numeric', 'gt:0'],
                'stock' => ['required', 'integer', 'min:0'],
                'status' => ['required', 'in:active,inactive'],
            ]);

            if (class_exists(\App\Models\Product::class)) {
                try {
                    \App\Models\Product::query()->create($validated);
                } catch (\Throwable $e) {
                    return back()->withInput()->with('error', $e->getMessage());
                }
            }

            return redirect()->route('seller.products.index')->with('success', 'Produk berhasil disimpan');
        })->name('store');
    });
});
