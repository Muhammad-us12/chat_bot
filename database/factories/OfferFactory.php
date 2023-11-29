<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Store>
 */
class OfferFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => $this->faker->randomNumber(3),
            'variant_id' => $this->faker->randomNumber(3),
            'variant_name' => $this->faker->word(10),
            'product_name' => $this->faker->word(10),
            'product_id' => $this->faker->randomNumber(3),
            'store_id' => $this->faker->randomNumber(3),
            'variant_offered_amount' => $this->faker->randomNumber(3),
            'variant_actual_amount' => $this->faker->randomNumber(3),
            'status' => 'pending',
        ];
    }
}
