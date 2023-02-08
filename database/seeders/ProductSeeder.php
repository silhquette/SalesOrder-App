<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::factory()->create([
            'code' => 'AAAA',
            'name' => 'Produk contoh',
            'dimension' => '12 x 3 x 43 mm',
            'unit' => 'pcs'
        ]);
    }
}
