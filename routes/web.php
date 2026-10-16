<?php

use App\Http\Controllers\Admin\TributeController as AdminTributeController;
use App\Http\Controllers\TributeController;
use App\Http\Middleware\RequireAdminPassword;
use Illuminate\Support\Facades\Route;

Route::get('/', [TributeController::class, 'index'])->name('tribute');

Route::post('/tributes', [TributeController::class, 'store'])
    ->middleware('throttle:6,1')
    ->name('tributes.store');

Route::get('/admin/tributes', [AdminTributeController::class, 'index'])
    ->middleware(['throttle:20,1', RequireAdminPassword::class])
    ->name('admin.tributes');
