<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'name' => 'Laptop Gaming ROG',
                'slug' => Str::slug('Laptop Gaming ROG'),
                'description' => 'Laptop gaming high performance dengan RTX 4060',
                'category_id' => 1,
                'price' => 25000000,
                'img' => null,
                'stock' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kaos Polos Hitam',
                'slug' => Str::slug('Kaos Polos Hitam'),
                'description' => 'Kaos katun nyaman dipakai sehari-hari',
                'category_id' => 2,
                'price' => 75000,
                'img' => null,
                'stock' => 50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kopi Arabika 250g',
                'slug' => Str::slug('Kopi Arabika 250g'),
                'description' => 'Biji kopi arabika sangrai medium',
                'category_id' => 3,
                'price' => 90000,
                'img' => null,
                'stock' => 30,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bola Futsal',
                'slug' => Str::slug('Bola Futsal'),
                'description' => 'Bola futsal ukuran standar',
                'category_id' => 4,
                'price' => 150000,
                'img' => null,
                'stock' => 20,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}