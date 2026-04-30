<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::create([
            'barcode' => 'BR001',
            'name' => 'Beras 5kg',
            'category_id' => 1,
            'price' => 75000,
            'stock' => 50,
            'unit' => 'kg'
        ]);
        
        Product::create([
            'barcode' => 'GL001',
            'name' => 'Gula 1kg',
            'category_id' => 1,
            'price' => 15000,
            'stock' => 100,
            'unit' => 'kg'
        ]);
        
        Product::create([
            'barcode' => 'MN001',
            'name' => 'Minyak Goreng 1L',
            'category_id' => 1,
            'price' => 20000,
            'stock' => 80,
            'unit' => 'liter'
        ]);
        
        Product::create([
            'barcode' => 'TL001',
            'name' => 'Telur',
            'category_id' => 1,
            'price' => 3000,
            'stock' => 200,
            'unit' => 'butir'
        ]);
    }
}