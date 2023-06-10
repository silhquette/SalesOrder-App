<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Customer;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PurchaseOrder>
 */
class SalesOrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $customer = Customer::all()->random();

        return [
            'id' => $this->faker->uuid(),
            'customer_id' => $customer->id,
            'nomor_po' => strtoupper($this->getCode(8)),
            'due_time' => Carbon::now()->addDays($customer->term),
            'tanggal_po' => Carbon::now(),
            'ppn' => rand(0, 11),
            'order_code' => '2306001'
        ];
    }

    /**
     * Get a random stirng
     *
     * @return string
     */
    public function getCode($n) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
     
        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }
     
        return $randomString;
    }
}
