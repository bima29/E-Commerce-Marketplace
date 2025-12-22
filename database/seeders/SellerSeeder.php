<?php

namespace Database\Seeders;

use App\Models\Seller;
use Illuminate\Database\Seeder;

class SellerSeeder extends Seeder
{
    public function run(): void
    {
        Seller::query()->create([
            'name' => 'Toko Alpha',
            'email' => 'alpha@seller.test',
            'status' => 'active',
        ]);

        Seller::query()->create([
            'name' => 'Toko Beta',
            'email' => 'beta@seller.test',
            'status' => 'active',
        ]);

        Seller::query()->create([
            'name' => 'Toko Nonaktif',
            'email' => 'inactive@seller.test',
            'status' => 'inactive',
        ]);
    }
}
