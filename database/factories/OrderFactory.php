<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\SalesOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $price = rand(4, 50) * 1000;
        $disc = rand(0, 50);
        $qty = rand(10, 100);
        return [
            'id' => $this->faker->uuid(),
            'sales_order_id' => SalesOrder::all()->random()->id,
            'product_id' => Product::all()->random()->id,
            'qty' => $qty,
            'discount' => $disc,
            'price' => $price,
            'amount' => $qty * $price - ($qty * $price * 0.01 * $disc), 
        ];
    }
}
