<?php

namespace Database\Factories;

use App\Models\Kamus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Kamus>
 */
class KamusFactory extends Factory
{
    protected $model = Kamus::class;

    public function definition(): array
    {
        return [
            'word' => fake()->word(),
            'category' => fake()->randomElement(['Abjad', 'Angka', 'Kata Dasar', 'Kalimat']),
            'video_path' => 'videos/' . fake()->word() . '.mp4',
            'description' => fake()->sentence(),
        ];
    }
}
