<?php

namespace Database\Seeders;

use App\Models\Seller;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'superadmin@marketplace.test'],
            [
                'name' => 'Superadmin',
                'password' => Hash::make('password123'),
                'role' => 'superadmin',
                'seller_id' => null,
            ]
        );

        $sellerAlpha = Seller::query()->where('email', 'alpha@seller.test')->first();
        if ($sellerAlpha) {
            User::query()->updateOrCreate(
                ['email' => 'seller.alpha@marketplace.test'],
                [
                    'name' => 'Seller Alpha',
                    'password' => Hash::make('password123'),
                    'role' => 'seller',
                    'seller_id' => $sellerAlpha->id,
                ]
            );
        }

        $sellerBeta = Seller::query()->where('email', 'beta@seller.test')->first();
        if ($sellerBeta) {
            User::query()->updateOrCreate(
                ['email' => 'seller.beta@marketplace.test'],
                [
                    'name' => 'Seller Beta',
                    'password' => Hash::make('password123'),
                    'role' => 'seller',
                    'seller_id' => $sellerBeta->id,
                ]
            );
        }
    }
}
