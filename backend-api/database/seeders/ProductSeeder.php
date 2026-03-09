<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'name' => 'Galon Aqua 19L',
            'price' => 18000,
            'stock' => 50,
            'description' => 'Isi ulang galon Aqua'
        ]);

        Product::create([
            'name' => 'Galon Le Minerale 19L',
            'price' => 17000,
            'stock' => 40,
            'description' => 'Isi ulang galon Le Minerale'
        ]);

        Product::create([
            'name' => 'Galon Vit 19L',
            'price' => 16000,
            'stock' => 60,
            'description' => 'Isi ulang galon Vit'
        ]);
    }
}
