<?php

namespace Database\Factories;

use Domain\Bargain\Entities\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'product_group_id' => $this->faker->randomNumber(3),
            'shopify_id' => $this->faker->randomNumber(3),
            'name' => $this->faker->word()
        ];
    }
}
