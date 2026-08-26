<?php

namespace Database\Factories;

use App\Models\Quiz;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Quiz>
 */
class QuizFactory extends Factory
{
    protected $model = Quiz::class;

    public function definition(): array
    {
        return [
            'word_target' => fake()->word(),
            'difficulty' => fake()->randomElement(['easy', 'medium', 'hard']),
        ];
    }
}
