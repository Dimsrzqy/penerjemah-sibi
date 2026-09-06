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

        if (\App\Models\Quiz::count() === 0) {
            $quizBank = [
                // Mudah (1 Kata)
                ['word_target' => 'MAKAN', 'difficulty' => 'mudah'],
                ['word_target' => 'RUMAH', 'difficulty' => 'mudah'],
                ['word_target' => 'TEMAN', 'difficulty' => 'mudah'],
                ['word_target' => 'BELAJAR', 'difficulty' => 'mudah'],
                ['word_target' => 'HALO', 'difficulty' => 'mudah'],
                ['word_target' => 'MAAF', 'difficulty' => 'mudah'],

                // Sedang (2 Kata Berkaitan)
                ['word_target' => 'TERIMA KASIH', 'difficulty' => 'sedang'],
                ['word_target' => 'KABAR BAIK', 'difficulty' => 'sedang'],
                ['word_target' => 'BELAJAR ISYARAT', 'difficulty' => 'sedang'],
                ['word_target' => 'TEMAN BAIK', 'difficulty' => 'sedang'],
                ['word_target' => 'MAKAN BERSAMA', 'difficulty' => 'sedang'],
                ['word_target' => 'SAMA SAMA', 'difficulty' => 'sedang'],

                // Susah (3-4 Kata berpola SPOK)
                ['word_target' => 'SAYA MAKAN NASI', 'difficulty' => 'susah'],
                ['word_target' => 'SAYA BELAJAR BAHASA ISYARAT', 'difficulty' => 'susah'],
                ['word_target' => 'TEMAN DATANG KE RUMAH', 'difficulty' => 'susah'],
                ['word_target' => 'IBU MEMASAK DI DAPUR', 'difficulty' => 'susah'],
                ['word_target' => 'KAMI BERJUMPA DI KEDAI', 'difficulty' => 'susah'],
            ];

            foreach ($quizBank as $q) {
                \App\Models\Quiz::create($q);
            }
        }
    }
}
