<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// v1
Route::prefix('v1')->group(function () {
    // auth routes
    Route::prefix('auth')->as('auth.')->group(function () {
        Route::middleware('guest')->group(function () {
            Route::post('login', [AuthController::class, 'login'])->name('login');
            Route::post('register', [AuthController::class, 'register'])->name('register');
        });
        Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum')->name('logout');
    });

    Route::middleware('auth:sanctum')->group(function () {
        // task routes
        Route::prefix('tasks')->group(function () {
            Route::get('/', [TaskController::class, 'index'])->name('tasks.index');
            Route::post('/', [TaskController::class, 'store'])->name('tasks.store');
            Route::delete('/delete/{task}', [TaskController::class, 'delete'])->can('delete', 'task')->name('tasks.delete');
            Route::put('/update/{task}', [TaskController::class, 'update'])->can('update', 'task')->name('tasks.update');
        });
    });

});
