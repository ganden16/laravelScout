<?php

use App\Http\Controllers\Api\PostController;
use Illuminate\Support\Facades\Route;

Route::prefix('posts')->name('posts.')->group(function () {
    Route::get('/', [PostController::class, 'getAll']);
    Route::get('/{post}', [PostController::class, 'findOne'])->middleware(['userpost', 'auth:sanctum']);
    Route::post('/', [PostController::class, 'add'])->middleware(['auth:sanctum']);
    Route::put('/{id}', [PostController::class, 'update'])->middleware(['userpost', 'auth:sanctum']);
    Route::delete('/{id}', [PostController::class, 'delete'])->middleware(['userpost', 'auth:sanctum']);
});

Route::prefix('auth')->controller('AuthController')->namespace('App\Http\Controllers\Api')->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::get('/me', 'me')->middleware('auth:sanctum');
    Route::put('/', 'updateUser')->middleware('auth:sanctum');
    Route::delete('/logout', 'logout')->middleware('auth:sanctum');
});
