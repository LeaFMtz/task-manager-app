<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\UserController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('dashboard', [TaskController::class, 'index'])
        ->name('dashboard');

    Route::get('all-tasks', [TaskController::class, 'allTasks'])
        ->middleware('can:task-view-all')
        ->name('tasks.all');

    Route::get('users', [UserController::class, 'index'])
        ->middleware('can:task-view-all')
        ->name('users.index');

    Route::apiResource('tasks', TaskController::class);
    Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});

require __DIR__ . '/settings.php';
