<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (\App\Models\QuizScore::count() === 0) {
            $sampleGuests = [
                ['name' => 'Guest_892', 'score' => 15250],
                ['name' => 'Guest_401', 'score' => 14800],
                ['name' => 'Guest_991', 'score' => 13900],
                ['name' => 'Guest_112', 'score' => 12450],
                ['name' => 'Guest_773', 'score' => 11200],
                ['name' => 'Guest_119', 'score' => 10850],
                ['name' => 'Guest_402', 'score' => 9700],
                ['name' => 'Guest_881', 'score' => 8950],
            ];

            foreach ($sampleGuests as $item) {
                $guest = \App\Models\Guest::create([
                    'name' => $item['name'],
                    'session_token' => \Illuminate\Support\Str::random(40),
                ]);

                \App\Models\QuizScore::create([
                    'guest_id' => $guest->guest_id,
                    'score' => $item['score'],
                ]);
            }
        }

        $this->call(QuizSeeder::class);
        $this->call(KamusSeeder::class);
    }
}
