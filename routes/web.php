<?php

use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\PeopleController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [GoogleAuthController::class, 'show'])->name('login')->middleware('guest');
Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback']);
Route::post('/logout', [GoogleAuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::view('/', 'periodic')->name('home');
    Route::get('/api/people', [PeopleController::class, 'index']);
});
