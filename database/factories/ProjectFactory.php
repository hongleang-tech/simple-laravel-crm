<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('-1 month', '+1 month');
        $end = (clone $start)->modify('+' . $this->faker->numberBetween(7, 120) . ' days');

        return [
            'client_id' => Client::factory(),
            'name' => $this->faker->catchPhrase(),
            'description' => $this->faker->paragraphs(2, true),
            'status' => $this->faker->randomElement(['planned', 'in_progress', 'on_hold', 'completed', 'cancelled']),
            'start_date' => $start->format('Y-m-d'),
            'end_date' => $end->format('Y-m-d'),
            'budget' => $this->faker->randomFloat(2, 1000, 250000),
            'user_id' => User::factory(),
        ];
    }
}