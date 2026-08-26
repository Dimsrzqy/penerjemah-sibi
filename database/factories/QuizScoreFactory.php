<?php

namespace Database\Factories;

use App\Models\Guest;
use App\Models\QuizScore;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<QuizScore>
 */
class QuizScoreFactory extends Factory
{
    protected $model = QuizScore::class;

    public function definition(): array
    {
        return [
            'guest_id' => Guest::factory(),
            'score' => fake()->numberBetween(0, 100),
        ];
    }
}
