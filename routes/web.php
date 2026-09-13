<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('login', [AuthController::class, 'index']);
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::middleware('auth')->group(function () {
    Route::get('/', [IndexController::class, 'index']);
    Route::prefix('settings')->group(function () {
        Route::prefix('menus')->group(function () {
            Route::get('/', [MenuController::class, 'index'])->name('settings.menus.index');
            Route::post('/', [MenuController::class, 'store'])->name('settings.menus.store');
            Route::put('/{menu}', [MenuController::class, 'update'])->name('settings.menus.update');
            Route::delete('/{menu}', [MenuController::class, 'destroy'])->name('settings.menus.destroy');
        });
        Route::prefix('users')->group(function () {
            Route::get('user', [UserController::class, 'index']);
            Route::get('role', [RoleController::class, 'index']);
        });
    });
});
