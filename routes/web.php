<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
})->name('beranda');

Route::get('/penerjemah', function () {
    return view('penerjemah');
})->name('penerjemah');

Route::get('/kamus', function () {
    return view('kamus');
})->name('kamus');

Route::get('/quiz', function () {
    return view('quiz');
})->name('quiz');
