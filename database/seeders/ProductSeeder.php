<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Seller;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Validator;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $activeSellerIds = Seller::query()->where('status', 'active')->pluck('id')->values();

        if ($activeSellerIds->count() < 2) {
            return;
        }

        $products = [
            [
                'seller_id' => $activeSellerIds[0],
                'name' => 'Kaos Premium',
                'price' => 75000,
                'stock' => 50,
                'status' => 'active',
            ],
            [
                'seller_id' => $activeSellerIds[0],
                'name' => 'Hoodie Navy',
                'price' => 199000,
                'stock' => 25,
                'status' => 'active',
            ],
            [
                'seller_id' => $activeSellerIds[1],
                'name' => 'Sepatu Sneakers',
                'price' => 350000,
                'stock' => 12,
                'status' => 'active',
            ],
            [
                'seller_id' => $activeSellerIds[1],
                'name' => 'Tas Selempang',
                'price' => 125000,
                'stock' => 30,
                'status' => 'inactive',
            ],
            [
                'seller_id' => $activeSellerIds[1],
                'name' => 'Topi Baseball',
                'price' => 45000,
                'stock' => 100,
                'status' => 'active',
            ],
        ];

        foreach ($products as $data) {
            Product::query()->create($data);
        }

        $inactiveSeller = Seller::query()->where('status', 'inactive')->first();

        if ($inactiveSeller) {
            $invalidProduct = [
                'seller_id' => $inactiveSeller->id,
                'name' => 'Produk Gagal (Seller Nonaktif)',
                'price' => 10000,
                'stock' => 1,
                'status' => 'active',
            ];

            $validator = Validator::make($invalidProduct, [
                'name' => ['required', 'string', 'max:255'],
                'seller_id' => ['required', 'integer'],
                'price' => ['required', 'numeric', 'gt:0'],
                'stock' => ['required', 'integer', 'min:0'],
                'status' => ['required', 'in:active,inactive'],
            ]);

            if ($validator->passes()) {
                if ($inactiveSeller->status !== 'active') {
                    if ($this->command) {
                        $this->command->warn('Skip seed invalid product: seller status inactive');
                    }
                } else {
                    Product::query()->create($invalidProduct);
                }
            }
        }
    }
}
