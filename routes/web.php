<?php

use App\Models\QuizScore;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    try {
        // Ambil 10 perolehan skor tertinggi langsung dari tabel quiz_scores dengan relasi guest
        $leaderboard = QuizScore::with('guest')
            ->where('score', '>', 0)
            ->orderByDesc('score')
            ->take(10)
            ->get();
    } catch (\Throwable $e) {
        \Illuminate\Support\Facades\Log::error('Leaderboard DB connection error: ' . $e->getMessage());
        $leaderboard = collect();
    }

    return view('index', compact('leaderboard'));
})->name('beranda');

Route::get('/penerjemah', function () {
    return view('penerjemah');
})->name('penerjemah');

use App\Http\Controllers\KamusController;
use App\Http\Controllers\QuizController;

Route::get('/kamus', [KamusController::class, 'index'])->name('kamus');


Route::get('/quiz', [QuizController::class, 'index'])->name('quiz');
Route::post('/quiz/guest', [QuizController::class, 'storeGuest'])->name('quiz.guest');
Route::get('/quiz/questions/{difficulty}', [QuizController::class, 'getQuestions'])->name('quiz.questions');
Route::post('/quiz/score', [QuizController::class, 'storeScore'])->name('quiz.score');
Route::post('/quiz/reset-session', [QuizController::class, 'resetSession'])->name('quiz.reset-session');

