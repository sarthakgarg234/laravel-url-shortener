<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ShortUrlController;
use App\Http\Controllers\InvitationController;

Route::get('/', fn () => redirect()->route('dashboard'))
    ->middleware('auth');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:5,1');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    Route::post('/short-urls', [ShortUrlController::class, 'store'])
        ->name('short-urls.store');

    Route::post('/invitations', [InvitationController::class, 'store'])
        ->name('invitations.store');
});

Route::get('/s/{code}', [ShortUrlController::class, 'resolve'])
    ->name('short-urls.resolve');