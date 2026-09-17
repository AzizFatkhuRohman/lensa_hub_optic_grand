<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('login', [AuthController::class, 'index']);
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('logout',[AuthController::class,'logout']);
Route::middleware('auth')->group(function () {
    Route::get('/', [IndexController::class, 'index']);
    Route::prefix('settings')->group(function () {
        Route::prefix('menus')->group(function () {
            Route::get('/', [MenuController::class, 'index'])->name('settings.menus.index');
            Route::post('parent_id', [MenuController::class, 'parent_id']);
            Route::get('front_table', [MenuController::class, 'front_table']);
            Route::post('store', [MenuController::class, 'store']);
            Route::post('show', [MenuController::class, 'show']);
            Route::post('update', [MenuController::class, 'update']);
            Route::post('delete', [MenuController::class, 'delete']);
        });
        Route::prefix('users')->group(function () {
            Route::prefix('user')->group(function () {
                Route::get('/', [UserController::class, 'index']);
                Route::get('front_table', [UserController::class, 'front_table']);
                Route::post('store', [UserController::class, 'store']);
                Route::post('role_id', [UserController::class, 'role_id']);
                Route::post('company_id',[UserController::class,'company_id']);
                Route::post('menu_list',[UserController::class,'menu_list']);
                Route::post('store_menu_access',[UserController::class,'store_menu_access']);
                Route::post('menu_access',[UserController::class,'menu_access']);
                Route::post('delete_menu_access',[UserController::class,'delete_menu_access']);
                Route::post('show', [UserController::class, 'show']);
                Route::post('update', [UserController::class, 'update']);
                Route::post('delete', [UserController::class, 'delete']);
            });
            Route::prefix('role')->group(function () {
                Route::get('/', [RoleController::class, 'index']);
                Route::get('front_table', [RoleController::class, 'front_table']);
                Route::post('store', [RoleController::class, 'store']);
                Route::post('show', [RoleController::class, 'show']);
                Route::post('update', [RoleController::class, 'update']);
                Route::post('delete', [RoleController::class, 'delete']);
            });
        });
    });
});
