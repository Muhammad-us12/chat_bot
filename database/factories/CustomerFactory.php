<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Store>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $browsers = ['Google Chrome','Mozila','Safari'];
        $operatingSystems = ['Windows','Linux','Android','Iphone'];
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->companyEmail,
            'country' => $this->faker->country,
            'city' => $this->faker->city,
            'browser' => $this->faker->randomElements($browsers, 1)[0],
            'os' => $this->faker->randomElements($operatingSystems, 1)[0],
        ];
    }
}
