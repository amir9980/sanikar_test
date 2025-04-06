<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

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
        return [
            'user_id' => User::factory(),
            'title' => 'factoryTask_' . fake()->unique()->word,
            'description' => fake()->paragraph(),
            'start_date' => now()->toDateTimeString(),
            'end_date' => now()->addDays(rand(1, 10))->toDateTimeString(),
        ];
    }

    public function titlePrefix(string $prefix)
    {
        return $this->state(function (array $attributes) use ($prefix) {
            return [
                'title' => $prefix . '_' . fake()->unique()->word,
            ];
        });
    }
}
