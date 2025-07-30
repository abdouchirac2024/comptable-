<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ArticleBlogController;
use App\Http\Controllers\FormationController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\MissionController;
use App\Http\Controllers\PartenaireController;
use App\Http\Controllers\HeroSectionController;
use App\Http\Controllers\HeroSlideController;

// Routes d'authentification
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/refresh', [AuthController::class, 'refresh']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::middleware('auth:sanctum')->get('/user', [AuthController::class, 'user']);

// Routes pour les contacts
Route::prefix('contacts')->group(function () {
    Route::get('/', [ContactController::class, 'index']);
    Route::get('/search', [ContactController::class, 'search']);
    Route::get('/{contact}', [ContactController::class, 'show']);
    Route::post('/', [ContactController::class, 'store']);
    Route::put('/{contact}', [ContactController::class, 'update']);
    Route::delete('/{contact}', [ContactController::class, 'destroy']);
    Route::post('/test-email', [ContactController::class, 'testEmail']);
});

// Routes pour les articles de blog avec support form-data
Route::prefix('article-blogs')->group(function () {
    Route::get('/', [ArticleBlogController::class, 'index']);
    Route::get('/{article_blog}', [ArticleBlogController::class, 'show']);
    Route::post('/', [ArticleBlogController::class, 'store']);
    Route::put('/{article_blog}', [ArticleBlogController::class, 'update']);
    Route::post('/{article_blog}/update', [ArticleBlogController::class, 'update']); // Route alternative pour form-data
    Route::delete('/{article_blog}', [ArticleBlogController::class, 'destroy']);
});

// Routes pour les formations avec support form-data
Route::prefix('formations')->group(function () {
    Route::get('/', [FormationController::class, 'index']);
    Route::get('/{formation}', [FormationController::class, 'show']);
    Route::post('/', [FormationController::class, 'store']);
    Route::put('/{formation}', [FormationController::class, 'update']);
    Route::post('/{formation}/update', [FormationController::class, 'update']); // Route alternative pour form-data
    Route::delete('/{formation}', [FormationController::class, 'destroy']);
});

// Routes pour les services avec support form-data
Route::prefix('services')->group(function () {
    Route::get('/', [ServiceController::class, 'index']);
    Route::get('/{service}', [ServiceController::class, 'show']);
    Route::post('/', [ServiceController::class, 'store']);
    Route::put('/{service}', [ServiceController::class, 'update']);
    Route::post('/{service}/update', [ServiceController::class, 'update']); // Route alternative pour form-data
    Route::delete('/{service}', [ServiceController::class, 'destroy']);
});

// Routes pour les missions et partenaires
Route::apiResource('missions', MissionController::class);
Route::apiResource('partenaires', PartenaireController::class);

// Routes pour les sections Hero
Route::prefix('hero-sections')->group(function () {
    Route::get('/', [HeroSectionController::class, 'index']);
    Route::get('/active', [HeroSectionController::class, 'active']);
    Route::get('/{heroSection}', [HeroSectionController::class, 'show']);
    Route::post('/', [HeroSectionController::class, 'store']);
    Route::put('/{heroSection}', [HeroSectionController::class, 'update']);
    Route::post('/{heroSection}/update', [HeroSectionController::class, 'update']); // Route alternative pour form-data
    Route::delete('/{heroSection}', [HeroSectionController::class, 'destroy']);
    Route::post('/{heroSection}/activate', [HeroSectionController::class, 'activate']);
    Route::post('/{heroSection}/deactivate', [HeroSectionController::class, 'deactivate']);
});

// Routes pour les slides Hero
Route::prefix('hero-slides')->group(function () {
    Route::get('/', [HeroSlideController::class, 'index']);
    Route::get('/{heroSlide}', [HeroSlideController::class, 'show']);
    Route::get('/section/{heroSectionId}', [HeroSlideController::class, 'bySection']);
    Route::post('/', [HeroSlideController::class, 'store']);
    Route::put('/{heroSlide}', [HeroSlideController::class, 'update']);
    Route::post('/{heroSlide}/update', [HeroSlideController::class, 'update']); // Route alternative pour form-data
    Route::delete('/{heroSlide}', [HeroSlideController::class, 'destroy']);
    Route::post('/section/{heroSectionId}/reorder', [HeroSlideController::class, 'reorder']);
    Route::post('/{heroSlide}/activate', [HeroSlideController::class, 'activate']);
    Route::post('/{heroSlide}/deactivate', [HeroSlideController::class, 'deactivate']);
    
    // Routes pour les images multiples
    Route::post('/{heroSlide}/add-image', [HeroSlideController::class, 'addImage']);
    Route::delete('/{heroSlide}/remove-image', [HeroSlideController::class, 'removeImage']);
    Route::post('/{heroSlide}/reorder-images', [HeroSlideController::class, 'reorderImages']);
});
