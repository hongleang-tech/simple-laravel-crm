<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Project;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $due = $this->faker->dateTimeBetween('now', '+2 months');

        return [
            'name' => $this->faker->realText(10),
            'completed' => false,
            'due_date' => $due->format('Y-m-d'),
            'project_id' => Project::factory(),
        ];
    }
}
