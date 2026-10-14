<?php

use App\Http\Controllers\TributeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [TributeController::class, 'index'])->name('tribute');

Route::post('/tributes', [TributeController::class, 'store'])
    ->middleware('throttle:6,1')
    ->name('tributes.store');
