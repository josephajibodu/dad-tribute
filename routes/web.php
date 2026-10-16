<?php

use App\Http\Controllers\Admin\SessionController as AdminSessionController;
use App\Http\Controllers\Admin\TributeController as AdminTributeController;
use App\Http\Controllers\TributeController;
use App\Http\Middleware\EnsureAdminIsSignedIn;
use Illuminate\Support\Facades\Route;

Route::get('/', [TributeController::class, 'index'])->name('tribute');

Route::post('/tributes', [TributeController::class, 'store'])
    ->middleware('throttle:6,1')
    ->name('tributes.store');

Route::get('/admin/login', [AdminSessionController::class, 'create'])->name('admin.login');
Route::post('/admin/login', [AdminSessionController::class, 'store'])->name('admin.login.store');
Route::post('/admin/logout', [AdminSessionController::class, 'destroy'])->name('admin.logout');

Route::get('/admin/tributes', [AdminTributeController::class, 'index'])
    ->middleware(EnsureAdminIsSignedIn::class)
    ->name('admin.tributes');
