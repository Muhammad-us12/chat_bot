<?php

namespace Database\Factories;

use App\Domain\Bargain\Entities\Bargain;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Domain\Bargain\Entities\Bargain>
 */
class BargainFactory extends Factory
{

    protected $model = Bargain::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'value' => $this->faker->randomNumber(2),
            'type' => \Illuminate\Support\Arr::random(['fixed', 'percentage']),
            'product_group_id' => $this->faker->randomDigitNotNull()
        ];
    }
}
