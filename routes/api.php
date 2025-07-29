<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\ArticleBlogController;
use App\Http\Controllers\AvisController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\LigneCommandeController;
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
    Route::post('/test-email', [ContactController::class, 'testEmail']);
});

Route::prefix('produits')->group(function () {
    Route::get('/', [ProduitController::class, 'index']);
    Route::get('/{produit}', [ProduitController::class, 'show']);
    Route::post('/', [ProduitController::class, 'store']);
    Route::put('/{produit}', [ProduitController::class, 'update']);
    Route::delete('/{produit}', [ProduitController::class, 'destroy']);
    Route::get('/total', [ProduitController::class, 'total']);
});

Route::prefix('images')->group(function () {
    Route::get('/', [ImageController::class, 'index']);
    Route::get('/{image}', [ImageController::class, 'show']);
    Route::post('/', [ImageController::class, 'store']);
    Route::put('/{image}', [ImageController::class, 'update']);
    Route::delete('/{image}', [ImageController::class, 'destroy']);
});

Route::prefix('articles')->group(function () {
    Route::get('/', [ArticleBlogController::class, 'index']);
    Route::get('/{article}', [ArticleBlogController::class, 'show']);
    Route::post('/', [ArticleBlogController::class, 'store']);
    Route::put('/{article}', [ArticleBlogController::class, 'update']);
    Route::delete('/{article}', [ArticleBlogController::class, 'destroy']);
});

Route::apiResource('article-blogs', ArticleBlogController::class);

Route::prefix('avis')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [AvisController::class, 'index']);
    Route::get('/{avi}', [AvisController::class, 'show']);
    Route::post('/', [AvisController::class, 'store']);
    Route::put('/{avi}', [AvisController::class, 'update']);
    Route::delete('/{avi}', [AvisController::class, 'destroy']);
});

Route::prefix('commandes')->group(function () {
    Route::get('/', [CommandeController::class, 'index']);
    Route::get('/{commande}', [CommandeController::class, 'show']);
    Route::post('/', [CommandeController::class, 'store']);
    Route::put('/{commande}', [CommandeController::class, 'update']);
    Route::delete('/{commande}', [CommandeController::class, 'destroy']);
});

Route::prefix('ligne-commandes')->group(function () {
    Route::get('/', [LigneCommandeController::class, 'index']);
    Route::get('/{ligneCommande}', [LigneCommandeController::class, 'show']);
    Route::post('/', [LigneCommandeController::class, 'store']);
    Route::put('/{ligneCommande}', [LigneCommandeController::class, 'update']);
    Route::delete('/{ligneCommande}', [LigneCommandeController::class, 'destroy']);
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

Route::post('services/{service}/update', [ServiceController::class, 'update']);
Route::apiResource('services', ServiceController::class);
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
