<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\ContactController;

// Routes d'authentification
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/refresh', [AuthController::class, 'refresh']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

// Routes pour les catégories
Route::prefix('categories')->group(function () {
    Route::get('/', [CategorieController::class, 'index']);
    Route::get('/all', [CategorieController::class, 'all']);
    Route::get('/search', [CategorieController::class, 'search']);
    Route::post('/', [CategorieController::class, 'store']);
    Route::get('/{categorie}', [CategorieController::class, 'show']);
    Route::get('/slug/{slug}', [CategorieController::class, 'showBySlug']);
    Route::put('/{categorie}', [CategorieController::class, 'update']);
    Route::delete('/{categorie}', [CategorieController::class, 'destroy']);
});

Route::prefix('contacts')->group(function () {
    Route::get('/', [ContactController::class, 'index']);
    Route::get('/search', [ContactController::class, 'search']);
    Route::get('/{contact}', [ContactController::class, 'show']);
    Route::post('/', [ContactController::class, 'store']);
    Route::put('/{contact}', [ContactController::class, 'update']);
    Route::delete('/{contact}', [ContactController::class, 'destroy']);
});
