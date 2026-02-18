<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Main Branch
        $branch = \App\Models\Branch::create([
            'name' => 'Toko Utama',
            'code' => 'MAIN',
            'address' => 'Jl. Merdeka No. 123',
            'phone' => '08123456789',
            'email' => 'toko.utama@example.com',
            'is_active' => true,
        ]);

        // 2. Create Users
        \App\Models\User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@pos.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'super_admin',
            'branch_id' => null,
            'is_active' => true,
        ]);

        \App\Models\User::create([
            'name' => 'Admin Cabang',
            'email' => 'admin@pos.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'branch_admin',
            'branch_id' => $branch->id,
            'is_active' => true,
        ]);

        \App\Models\User::create([
            'name' => 'Kasir 1',
            'email' => 'kasir@pos.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'cashier',
            'branch_id' => $branch->id,
            'is_active' => true,
        ]);

        // 3. Create Categories
        $categories = [
            ['name' => 'Makanan', 'slug' => 'makanan'],
            ['name' => 'Minuman', 'slug' => 'minuman'],
            ['name' => 'Elektronik', 'slug' => 'elektronik'],
        ];

        foreach ($categories as $cat) {
            \App\Models\Category::create($cat);
        }

        // 4. Create Initial Products
        $makananId = \App\Models\Category::where('slug', 'makanan')->first()->id;
        $minumanId = \App\Models\Category::where('slug', 'minuman')->first()->id;

        \App\Models\Product::create([
            'branch_id' => $branch->id,
            'category_id' => $makananId,
            'name' => 'Roti Bakar',
            'slug' => 'roti-bakar',
            'sku' => 'ROTI-001',
            'barcode' => '8991234567801',
            'buy_price' => 5000,
            'sell_price' => 12000,
            'member_price' => 11000,
            'wholesale_price' => 10000,
            'stock' => 100,
            'min_stock' => 10,
        ]);

        \App\Models\Product::create([
            'branch_id' => $branch->id,
            'category_id' => $minumanId,
            'name' => 'Es Teh Manis',
            'slug' => 'es-teh-manis',
            'sku' => 'TEH-001',
            'barcode' => '8991234567802',
            'buy_price' => 1000,
            'sell_price' => 5000,
            'member_price' => 4500,
            'wholesale_price' => 4000,
            'stock' => 200,
            'min_stock' => 20,
        ]);
    }
}
