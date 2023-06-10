<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'id' => $this->faker->uuid(),
            'npwp' => "23.123.534-3.523.734",
            'name' => "PT " . strtoupper($this->faker->streetName()),
            'email' => $this->faker->email(),
            'term' => rand(10, 30),
            'address' => strtoupper($this->faker->address()),
            'phone' => $this->faker->phoneNumber(),
            'contact' => strtoupper($this->faker->name()),
            'code' => 'CS-' . strtoupper(Str::limit(Str::uuid(),8,'')),
        ];
    }
}
