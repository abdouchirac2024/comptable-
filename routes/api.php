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

Route::prefix('avis')->group(function () {
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
