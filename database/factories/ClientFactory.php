<?php

namespace Database\Factories;

use App\Enums\ClientStatus;
use App\Models\Address;
use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'email' => $this->faker->unique()->companyEmail(),
            'phone_number' => fake()->numerify("04########"),
            'company' => $this->faker->company(),
            'status' => $this->faker->randomElement(ClientStatus::getAllByKey('value')),
            'user_id' => User::factory(),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Client $client) {
            $client->address()->create(Address::factory()->make()->toArray());
        });
    }
}
