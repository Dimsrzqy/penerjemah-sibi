<?php

namespace Database\Seeders;

use App\Models\Quiz;
use Illuminate\Database\Seeder;

class QuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $quizBank = [
            // ==================== MUDAH (1 Kata) ====================
            ['word_target' => 'SAYA', 'difficulty' => 'mudah'],
            ['word_target' => 'KAMU', 'difficulty' => 'mudah'],
            ['word_target' => 'DIA', 'difficulty' => 'mudah'],
            ['word_target' => 'KAMI', 'difficulty' => 'mudah'],
            ['word_target' => 'KITA', 'difficulty' => 'mudah'],
            ['word_target' => 'KALIAN', 'difficulty' => 'mudah'],
            ['word_target' => 'MEREKA', 'difficulty' => 'mudah'],
            ['word_target' => 'IBU', 'difficulty' => 'mudah'],
            ['word_target' => 'AYAH', 'difficulty' => 'mudah'],
            ['word_target' => 'KAKEK', 'difficulty' => 'mudah'],
            ['word_target' => 'ADIK', 'difficulty' => 'mudah'],
            ['word_target' => 'KELUARGA', 'difficulty' => 'mudah'],
            ['word_target' => 'KULIAH', 'difficulty' => 'mudah'],
            ['word_target' => 'SENANG', 'difficulty' => 'mudah'],
            ['word_target' => 'BERTEMU', 'difficulty' => 'mudah'],
            ['word_target' => 'CANTIK', 'difficulty' => 'mudah'],
            ['word_target' => 'GANTENG', 'difficulty' => 'mudah'],
            ['word_target' => 'PELIT', 'difficulty' => 'mudah'],
            ['word_target' => 'KURUS', 'difficulty' => 'mudah'],
            ['word_target' => 'GEMUK', 'difficulty' => 'mudah'],
            ['word_target' => 'LUCU', 'difficulty' => 'mudah'],
            ['word_target' => 'PINTAR', 'difficulty' => 'mudah'],
            ['word_target' => 'BAIK', 'difficulty' => 'mudah'],
            ['word_target' => 'SAKIT', 'difficulty' => 'mudah'],
            ['word_target' => 'SABAR', 'difficulty' => 'mudah'],
            ['word_target' => 'MAU', 'difficulty' => 'mudah'],
            ['word_target' => 'PULANG', 'difficulty' => 'mudah'],
            ['word_target' => 'TINGGAL', 'difficulty' => 'mudah'],
            ['word_target' => 'NAMA', 'difficulty' => 'mudah'],
            ['word_target' => 'UMUR', 'difficulty' => 'mudah'],
            ['word_target' => 'KAMPUS', 'difficulty' => 'mudah'],
            ['word_target' => 'SEKOLAH', 'difficulty' => 'mudah'],
            ['word_target' => 'SEMESTER', 'difficulty' => 'mudah'],
            ['word_target' => 'KELAS', 'difficulty' => 'mudah'],
            ['word_target' => 'HOBI', 'difficulty' => 'mudah'],
            ['word_target' => 'JURUSAN', 'difficulty' => 'mudah'],
            ['word_target' => 'PENDIDIKAN', 'difficulty' => 'mudah'],
            ['word_target' => 'KABAR', 'difficulty' => 'mudah'],
            ['word_target' => 'DARI', 'difficulty' => 'mudah'],
            ['word_target' => 'PAGI', 'difficulty' => 'mudah'],
            ['word_target' => 'SIANG', 'difficulty' => 'mudah'],
            ['word_target' => 'SORE', 'difficulty' => 'mudah'],
            ['word_target' => 'MALAM', 'difficulty' => 'mudah'],
            ['word_target' => 'SENIN', 'difficulty' => 'mudah'],
            ['word_target' => 'SELASA', 'difficulty' => 'mudah'],
            ['word_target' => 'RABU', 'difficulty' => 'mudah'],
            ['word_target' => 'KAMIS', 'difficulty' => 'mudah'],
            ["word_target" => "JUM'AT", 'difficulty' => 'mudah'],
            ['word_target' => 'MINGGU', 'difficulty' => 'mudah'],
            ['word_target' => 'SABTU', 'difficulty' => 'mudah'],
            ['word_target' => 'APA', 'difficulty' => 'mudah'],
            ['word_target' => 'BERAPA', 'difficulty' => 'mudah'],
            ['word_target' => 'KEMANA', 'difficulty' => 'mudah'],
            ['word_target' => 'DIMANA', 'difficulty' => 'mudah'],
            ['word_target' => 'SIAPA', 'difficulty' => 'mudah'],
            ['word_target' => 'KENAPA', 'difficulty' => 'mudah'],
            ['word_target' => 'HALLO', 'difficulty' => 'mudah'],
            ['word_target' => 'TERIMA KASIH', 'difficulty' => 'mudah'],
            ['word_target' => 'SELAMAT', 'difficulty' => 'mudah'],
            ['word_target' => 'SAMPAI JUMPA', 'difficulty' => 'mudah'],

            // ==================== SEDANG (Frasa 2 Kata) ====================
            ['word_target' => 'SELAMAT PAGI', 'difficulty' => 'sedang'],
            ['word_target' => 'SELAMAT SIANG', 'difficulty' => 'sedang'],
            ['word_target' => 'SELAMAT SORE', 'difficulty' => 'sedang'],
            ['word_target' => 'SELAMAT MALAM', 'difficulty' => 'sedang'],
            ['word_target' => 'TERIMA KASIH', 'difficulty' => 'sedang'],
            ['word_target' => 'SAMPAI JUMPA', 'difficulty' => 'sedang'],
            ['word_target' => 'KABAR BAIK', 'difficulty' => 'sedang'],
            ['word_target' => 'BERAPA UMUR', 'difficulty' => 'sedang'],
            ['word_target' => 'SIAPA NAMA', 'difficulty' => 'sedang'],
            ['word_target' => 'DIMANA KAMPUS', 'difficulty' => 'sedang'],
            ['word_target' => 'DIMANA SEKOLAH', 'difficulty' => 'sedang'],
            ['word_target' => 'KEMANA PULANG', 'difficulty' => 'sedang'],
            ['word_target' => 'APA KABAR', 'difficulty' => 'sedang'],
            ['word_target' => 'APA HOBI', 'difficulty' => 'sedang'],
            ['word_target' => 'SAYA KULIAH', 'difficulty' => 'sedang'],
            ['word_target' => 'KAMU KULIAH', 'difficulty' => 'sedang'],
            ['word_target' => 'ADIK SEKOLAH', 'difficulty' => 'sedang'],
            ['word_target' => 'AYAH PULANG', 'difficulty' => 'sedang'],
            ['word_target' => 'IBU SENANG', 'difficulty' => 'sedang'],
            ['word_target' => 'KAKEK SAKIT', 'difficulty' => 'sedang'],
            ['word_target' => 'KELUARGA BAIK', 'difficulty' => 'sedang'],
            ['word_target' => 'SENIN PAGI', 'difficulty' => 'sedang'],
            ['word_target' => 'SELASA SIANG', 'difficulty' => 'sedang'],
            ['word_target' => 'RABU SORE', 'difficulty' => 'sedang'],
            ['word_target' => 'KAMIS MALAM', 'difficulty' => 'sedang'],
            ['word_target' => 'SABTU MALAM', 'difficulty' => 'sedang'],
            ['word_target' => 'MINGGU PAGI', 'difficulty' => 'sedang'],
            ['word_target' => 'BERTEMU KALIAN', 'difficulty' => 'sedang'],
            ['word_target' => 'KITA BERTEMU', 'difficulty' => 'sedang'],

            // ==================== SUSAH (Kalimat Berpola SPOK) ====================
            // S: Saya/Kamu/Dia/Kami/Kita/Kalian/Berikutnya, P: Kuliah/Pulang/Bertemu/Sakit, O: Kampus/Sekolah/Keluarga, K: Pagi/Siang/Sore/Malam/Dari...
            ['word_target' => 'SAYA KULIAH DARI PAGI', 'difficulty' => 'susah'], // S + P + K
            ['word_target' => 'AYAH PULANG DARI KAMPUS', 'difficulty' => 'susah'], // S + P + K(O)
            ['word_target' => 'IBU PULANG DARI SEKOLAH', 'difficulty' => 'susah'], // S + P + K(O)
            ['word_target' => 'SAYA KULIAH JURUSAN PENDIDIKAN', 'difficulty' => 'susah'], // S + P + O
            ['word_target' => 'MEREKA PULANG SEKOLAH SIANG', 'difficulty' => 'susah'], // S + P + O + K
            ['word_target' => 'KAMI BERTEMU KELUARGA SABTU', 'difficulty' => 'susah'], // S + P + O + K
            ['word_target' => 'KITA BERTEMU KAKEK MINGGU', 'difficulty' => 'susah'], // S + P + O + K
            ['word_target' => 'KAKEK SAKIT DARI SORE', 'difficulty' => 'susah'], // S + P + K
            ['word_target' => 'KALIAN KULIAH DARI SIANG', 'difficulty' => 'susah'], // S + P + K
            ['word_target' => 'SIAPA NAMA ADIK KAMU', 'difficulty' => 'susah'], // KT + O + S
            ['word_target' => 'BERAPA UMUR ADIK KAMU', 'difficulty' => 'susah'], // KT + O + S
            ['word_target' => 'DIMANA KAMPUS KAMU', 'difficulty' => 'susah'], // KT + O + S
            ['word_target' => 'DIMANA SEKOLAH ADIK', 'difficulty' => 'susah'], // KT + O + S
            ['word_target' => 'KEMANA KAMU PULANG MALAM', 'difficulty' => 'susah'], // KT + S + P + K
            ['word_target' => 'KENAPA ADIK SAKIT SENIN', 'difficulty' => 'susah'], // KT + S + P + K
            ['word_target' => 'APA KABAR KELUARGA KAMU', 'difficulty' => 'susah'], // KT + O + S
            ['word_target' => 'APA HOBI ADIK KAMU', 'difficulty' => 'susah'], // KT + O + S
            ['word_target' => 'SAYA SENANG BERTEMU KALIAN', 'difficulty' => 'susah'], // S + P + O
            ['word_target' => 'IBU SENANG BERTEMU KAKEK', 'difficulty' => 'susah'], // S + P + O
            ['word_target' => 'ADIK TINGGAL KELAS SENIN', 'difficulty' => 'susah'], // S + P + O + K
        ];

        foreach ($quizBank as $q) {
            Quiz::updateOrCreate(
                [
                    'word_target' => $q['word_target'],
                    'difficulty' => $q['difficulty'],
                ],
                $q
            );
        }
    }
}
