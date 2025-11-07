<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Middleware\JwtMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('api')->group(function () {
    // Authentication routes (public)
    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
    });

    // Protected routes (require JWT)
    Route::middleware(JwtMiddleware::class)->group(function () {
        // Auth routes
        Route::prefix('auth')->group(function () {
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::post('/refresh', [AuthController::class, 'refresh']);
            Route::get('/me', [AuthController::class, 'me']);
        });

        // Task routes
        Route::prefix('tasks')->group(function () {
            // Statistics must come before resource routes to avoid conflicts
            Route::get('/statistics', [TaskController::class, 'statistics']);
            Route::get('/upcoming', [TaskController::class, 'upcoming']);
            Route::get('/filter', [TaskController::class, 'filter']);

            // Resource routes
            Route::get('/', [TaskController::class, 'index']);
            Route::post('/', [TaskController::class, 'store']);
            Route::get('/{id}', [TaskController::class, 'show']);
            Route::put('/{id}', [TaskController::class, 'update']);
            Route::delete('/{id}', [TaskController::class, 'destroy']);

            // Additional routes
            Route::patch('/{id}/status', [TaskController::class, 'changeStatus']);
            Route::patch('/bulk-status', [TaskController::class, 'bulkUpdateStatus']);
        });
    });

    // Health check
    Route::get('/health', function () {
        return response()->json([
            'success' => true,
            'message' => 'API is running',
            'timestamp' => now(),
        ]);
    });
});
