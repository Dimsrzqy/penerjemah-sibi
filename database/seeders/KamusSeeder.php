<?php

namespace Database\Seeders;

use App\Models\Kamus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class KamusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Subjek' => [
                'Saya', 'Kamu', 'Dia', 'Kami', 'Kita', 'Kalian',
                'Mereka', 'Ibu', 'Ayah', 'Kakek', 'Adik', 'Keluarga',
            ],
            'Predikat' => [
                'Kuliah', 'Senang', 'Bertemu', 'Cantik', 'Ganteng', 'Pelit',
                'Kurus', 'Gemuk', 'Lucu', 'Pintar', 'Baik', 'Sakit',
                'Sabar', 'Mau', 'Pulang', 'Tinggal',
            ],
            'Objek' => [
                'Nama', 'Umur', 'Kampus', 'Sekolah', 'Semester', 'Kelas',
                'Hobi', 'Jurusan', 'Pendidikan', 'Kabar',
            ],
            'Keterangan' => [
                'Dari', 'Pagi', 'Siang', 'Sore', 'Malam', 'Senin',
                'Selasa', 'Rabu', 'Kamis', "Jum'at", 'Minggu', 'Sabtu',
            ],
            'Kata Tanya' => [
                'Apa', 'Berapa', 'Kemana', 'Dimana', 'Siapa', 'Kenapa',
            ],
            'Salam / Lainnya' => [
                'Hallo', 'Terima Kasih', 'Selamat', 'Sampai Jumpa',
            ],
        ];

        foreach ($categories as $category => $words) {
            foreach ($words as $word) {
                $payload = [
                    'category' => $category,
                    'description' => "Gerakan isyarat SIBI untuk kata {$word}.",
                ];

                // Auto-detect video file in public/videos/
                $possibleFiles = [
                    strtolower($word) . '.mp4',
                    Str::slug($word) . '.mp4',
                    str_replace(' ', '', strtolower($word)) . '.mp4',
                    str_replace(["'", '’'], '', strtolower($word)) . '.mp4',
                ];

                foreach ($possibleFiles as $fileName) {
                    if (file_exists(public_path('videos/' . $fileName))) {
                        $payload['video_path'] = 'videos/' . $fileName;
                        break;
                    }
                }

                Kamus::updateOrCreate(
                    ['word' => $word],
                    $payload
                );
            }
        }
    }
}
