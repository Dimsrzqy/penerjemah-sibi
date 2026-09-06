<?php

use App\Models\QuizScore;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    try {
        $leaderboard = QuizScore::with('guest')
            ->orderByDesc('score')
            ->take(10)
            ->get();
    } catch (\Throwable $e) {
        $leaderboard = collect();
    }

    return view('index', compact('leaderboard'));
})->name('beranda');

Route::get('/penerjemah', function () {
    return view('penerjemah');
})->name('penerjemah');

Route::get('/kamus', function () {
    return view('kamus');
})->name('kamus');

use App\Http\Controllers\QuizController;

Route::get('/quiz', [QuizController::class, 'index'])->name('quiz');
Route::post('/quiz/guest', [QuizController::class, 'storeGuest'])->name('quiz.guest');
Route::get('/quiz/questions/{difficulty}', [QuizController::class, 'getQuestions'])->name('quiz.questions');
Route::post('/quiz/score', [QuizController::class, 'storeScore'])->name('quiz.score');
Route::post('/quiz/reset-session', [QuizController::class, 'resetSession'])->name('quiz.reset-session');

