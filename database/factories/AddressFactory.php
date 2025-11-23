<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'address_1' => $this->faker->streetName(),
            'address_2' => null,
            'suburb' => $this->faker->city(),
            'postcode' => $this->faker->numerify('####'),
            'state' => $this->faker->state(),
            'country' => 'Australia',
        ];
    }
}
