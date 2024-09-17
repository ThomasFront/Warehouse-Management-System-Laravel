<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CheckAdminRole;
use App\Http\Middleware\CheckCurrentUser;
use App\Http\Middleware\CheckMessageOwner;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'auth',
], function () {
    Route::post('register', [AuthController::class, 'register'])->middleware('auth:api', CheckAdminRole::class);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
    Route::post('refresh', [AuthController::class, 'refresh'])->middleware('auth:api');
    Route::get('me', [AuthController::class, 'me'])->middleware('auth:api');
});

Route::group([
    'prefix' => 'categories',
], function () {
    Route::get('', [CategoryController::class, 'index'])->middleware('auth:api');
    Route::post('', [CategoryController::class, 'store'])->middleware('auth:api');
    Route::get('{category}', [CategoryController::class, 'show'])->middleware('auth:api');
    Route::delete('{category}', [CategoryController::class, 'destroy'])->middleware('auth:api');
    Route::patch('{category}', [CategoryController::class, 'update'])->middleware('auth:api');
});

Route::group([
    'prefix' => 'users'
], function() {
    Route::get('', [UserController::class, 'index'])->middleware('auth:api');
    Route::get('{user}', [UserController::class, 'show'])->middleware('auth:api');
    Route::post('avatar', [UserController::class, 'uploadAvatar'])->middleware('auth:api');
    Route::delete('{user}', [UserController::class, 'destroy'])->middleware('auth:api', CheckAdminRole::class);
    Route::patch('{user}', [UserController::class, 'update'])->middleware('auth:api', CheckAdminRole::class);
    Route::patch('{user}/profile', [UserController::class, 'editUserProfile'])->middleware('auth:api', CheckCurrentUser::class);
});

Route::group([
    'prefix' => 'messages'
], function() {
    Route::get('', [MessageController::class, 'index'])->middleware('auth:api');
    Route::post('', [MessageController::class, 'store'])->middleware('auth:api');
    Route::delete('{message}', [MessageController::class, 'destroy'])->middleware('auth:api', CheckMessageOwner::class);
});

Route::get('menu', [MenuController::class, 'index'])->middleware('auth:api');
