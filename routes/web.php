<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');

    Route::resource('users', UserController::class)
        ->only(['index', 'show', 'create', 'update', 'store', 'destroy'])
        ->names('users');

    Route::resource('clients', ClientController::class)
        ->only(['index', 'show', 'create', 'update', 'store', 'destroy'])
        ->names('clients');

    Route::resource('projects', ProjectController::class)
        ->only(['index', 'show', 'create', 'update', 'store', 'destroy'])
        ->names('projects');
});

require __DIR__ . '/auth.php';
