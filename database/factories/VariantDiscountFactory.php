<?php

namespace Database\Factories;

use App\Models\VariantDiscount;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class VariantDiscountFactory extends Factory
{
    protected $model = VariantDiscount::class;

    public function definition(): array
    {
        return [
            'variant_id' => $this->faker->randomNumber(9),
            'discount_percentage' => $this->faker->randomDigit
        ];
    }
}
