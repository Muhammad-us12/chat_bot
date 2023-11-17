<?php

namespace Database\Factories;

use Domain\Bargain\Entities\ProductGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ProductGroupFactory extends Factory
{
    protected $model = ProductGroup::class;

    public function definition(): array
    {
        return [
            'store_id' => $this->faker->randomNumber(3),
            'name' => $this->faker->word(),
            'type' => $this->faker->word()
        ];
    }
}
