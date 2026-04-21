<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name'=>'Admin',
            'email'=>'admin@donut.test',
            'password'=>Hash::make('password'),
            'role'=>'admin'
        ]);

        // Sample buyer
        User::create([
            'name'=>'Buyer',
            'email'=>'buyer@donut.test',
            'password'=>Hash::make('password'),
            'role'=>'buyer'
        ]);

        // Sample products
        Product::create([
            'name'=>'Classic Donut',
            'description'=>'Donat klasik manis dengan gula halus.',
            'price'=>12000,
            'stock'=>50,
            'image_url'=>'https://via.placeholder.com/600x300?text=Classic+Donut'
        ]);

        Product::create([
            'name'=>'Chocolate Donut',
            'description'=>'Donat lapis cokelat lezat.',
            'price'=>15000,
            'stock'=>30,
            'image_url'=>'https://via.placeholder.com/600x300?text=Chocolate+Donut'
        ]);
    }
}
