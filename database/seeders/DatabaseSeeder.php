<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run():void
    {
        User::factory()->create();
        
        $this->call([
            ProductSeeder::class,
            CustomerSeeder::class,
            SalesOrderSeeder::class,
            OrderSeeder::class,
        ]);
    }
}
