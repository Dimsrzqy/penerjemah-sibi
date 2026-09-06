<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\Quiz;
use App\Models\QuizScore;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class QuizController extends Controller
{
    /**
     * Tampilkan halaman utama kuis.
     */
    public function index(): View
    {
        $sessionGuest = null;
        if (session()->has('current_guest_id') && session()->has('current_guest_name')) {
            $sessionGuest = [
                'guest_id' => session('current_guest_id'),
                'name' => session('current_guest_name'),
            ];
        }

        return view('quiz', compact('sessionGuest'));
    }

    /**
     * Daftarkan guest baru setiap kali pengguna memasukkan nama.
     * Tidak digabung meskipun memiliki nama yang sama dengan guest sebelumnya.
     */
    public function storeGuest(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
        ]);

        $guest = Guest::create([
            'name' => trim($validated['name']),
            'session_token' => Str::random(40),
        ]);

        session([
            'current_guest_id' => $guest->guest_id,
            'current_guest_name' => $guest->name,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Guest berhasil didaftarkan.',
            'guest' => [
                'guest_id' => $guest->guest_id,
                'name' => $guest->name,
            ],
        ]);
    }

    /**
     * Ambil daftar soal kuis berdasarkan tingkat kesulitan.
     * Batas skor maksimal:
     * - mudah: 100 poin
     * - sedang: 250 poin
     * - susah: 500 poin
     * Poin per soal dihitung proporsional: max_score / jumlah_soal
     */
    public function getQuestions(string $difficulty): JsonResponse
    {
        $difficulty = strtolower($difficulty);
        if (!in_array($difficulty, ['mudah', 'sedang', 'susah'], true)) {
            $difficulty = 'mudah';
        }

        $maxScores = [
            'mudah' => 100,
            'sedang' => 250,
            'susah' => 500,
        ];
        $maxScore = $maxScores[$difficulty] ?? 100;

        try {
            // Membaca langsung dari tabel quizzes di database (mengambil hingga 10 soal acak)
            $questions = Quiz::whereRaw('LOWER(difficulty) = ?', [$difficulty])
                ->inRandomOrder()
                ->take(10)
                ->get(['quizzes_id', 'word_target', 'difficulty']);

            // Jika pengguna belum mengisi data pada tabel quizzes untuk tingkat kesulitan ini,
            // gunakan fallback memori sementara (tanpa mengotori database pengguna)
            if ($questions->isEmpty()) {
                $questions = collect($this->getDefaultQuestions($difficulty));
            } else {
                // Pastikan target kata bersih dan huruf kapital agar sinkron dengan model Deep Learning
                $questions->transform(function ($item) {
                    $item->word_target = strtoupper(trim($item->word_target));
                    return $item;
                });
            }
        } catch (\Throwable $e) {
            $questions = collect($this->getDefaultQuestions($difficulty));
        }

        $count = $questions->count();
        $pointsPerQuestion = $count > 0 ? (int) floor($maxScore / $count) : 0;

        return response()->json([
            'success' => true,
            'source' => 'table_quizzes',
            'difficulty' => $difficulty,
            'max_score' => $maxScore,
            'points_per_question' => $pointsPerQuestion,
            'count' => $count,
            'questions' => $questions,
        ]);
    }

    /**
     * Simpan perolehan skor akhir kuis ke tabel quiz_scores.
     */
    public function storeScore(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'guest_id' => ['required', 'integer', 'exists:guests,guest_id'],
            'score' => ['required', 'integer', 'min:0'],
        ]);

        try {
            $scoreRecord = QuizScore::create([
                'guest_id' => $validated['guest_id'],
                'score' => $validated['score'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Skor kuis berhasil disimpan ke tabel quiz_scores.',
                'score_id' => $scoreRecord->quiz_scores_id,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan skor: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Reset sesi pengguna jika terjadi inactivity timeout atau pengguna berganti nama.
     */
    public function resetSession(): JsonResponse
    {
        session()->forget(['current_guest_id', 'current_guest_name']);

        return response()->json([
            'success' => true,
            'message' => 'Sesi pengguna berhasil direset.',
        ]);
    }

    /**
     * Data bank soal default jika tabel database belum memiliki data.
     */
    private function getDefaultQuestions(string $difficulty): array
    {
        $defaults = [
            'mudah' => [
                ['quizzes_id' => 1, 'word_target' => 'MAKAN', 'difficulty' => 'mudah'],
                ['quizzes_id' => 2, 'word_target' => 'RUMAH', 'difficulty' => 'mudah'],
                ['quizzes_id' => 3, 'word_target' => 'TEMAN', 'difficulty' => 'mudah'],
                ['quizzes_id' => 4, 'word_target' => 'BELAJAR', 'difficulty' => 'mudah'],
                ['quizzes_id' => 5, 'word_target' => 'HALO', 'difficulty' => 'mudah'],
            ],
            'sedang' => [
                ['quizzes_id' => 6, 'word_target' => 'TERIMA KASIH', 'difficulty' => 'sedang'],
                ['quizzes_id' => 7, 'word_target' => 'KABAR BAIK', 'difficulty' => 'sedang'],
                ['quizzes_id' => 8, 'word_target' => 'BELAJAR ISYARAT', 'difficulty' => 'sedang'],
                ['quizzes_id' => 9, 'word_target' => 'TEMAN BAIK', 'difficulty' => 'sedang'],
                ['quizzes_id' => 10, 'word_target' => 'MAKAN BERSAMA', 'difficulty' => 'sedang'],
            ],
            'susah' => [
                ['quizzes_id' => 11, 'word_target' => 'SAYA MAKAN NASI', 'difficulty' => 'susah'],
                ['quizzes_id' => 12, 'word_target' => 'SAYA BELAJAR BAHASA ISYARAT', 'difficulty' => 'susah'],
                ['quizzes_id' => 13, 'word_target' => 'TEMAN DATANG KE RUMAH', 'difficulty' => 'susah'],
                ['quizzes_id' => 14, 'word_target' => 'IBU MEMASAK DI DAPUR', 'difficulty' => 'susah'],
                ['quizzes_id' => 15, 'word_target' => 'KAMI BERJUMPA DI KEDAI', 'difficulty' => 'susah'],
            ],
        ];

        return $defaults[$difficulty] ?? $defaults['mudah'];
    }
}

