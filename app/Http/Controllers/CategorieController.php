<?php

namespace App\Http\Controllers;

use App\Http\Requests\Categorie\CreateCategorieRequest;
use App\Http\Requests\Categorie\UpdateCategorieRequest;
use App\Http\Resources\CategorieCollection;
use App\Http\Resources\CategorieResource;
use App\Models\Categorie;
use App\Services\CategorieService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategorieController extends Controller
{
    public function __construct(
        protected CategorieService $categorieService
    ) {
    }

    /**
     * Afficher la liste des catégories avec pagination
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 15);
            $categories = $this->categorieService->getPaginatedCategories($perPage);
            
            return response()->json([
                'success' => true,
                'message' => __('categories.messages.index_success'),
                'data' => new CategorieCollection($categories)
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des catégories', [
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => __('categories.messages.index_error')
            ], 500);
        }
    }

    /**
     * Afficher toutes les catégories (sans pagination)
     */
    public function all(): JsonResponse
    {
        try {
            $categories = $this->categorieService->getAllCategories();
            
            return response()->json([
                'success' => true,
                'message' => __('categories.messages.all_success'),
                'data' => CategorieResource::collection($categories)
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération de toutes les catégories', [
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => __('categories.messages.all_error')
            ], 500);
        }
    }

    /**
     * Afficher une catégorie spécifique
     */
    public function show(Categorie $categorie): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'message' => __('categories.messages.show_success'),
                'data' => new CategorieResource($categorie->loadCount('produits'))
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération de la catégorie', [
                'error' => $e->getMessage(),
                'categorie_id' => $categorie->id
            ]);
            
            return response()->json([
                'success' => false,
                'message' => __('categories.messages.show_error')
            ], 500);
        }
    }

    /**
     * Afficher une catégorie par son slug
     */
    public function showBySlug(string $slug): JsonResponse
    {
        try {
            $categorie = $this->categorieService->getCategoryBySlug($slug);
            
            if (!$categorie) {
                return response()->json([
                    'success' => false,
                    'message' => __('categories.messages.not_found')
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'message' => __('categories.messages.show_success'),
                'data' => new CategorieResource($categorie->loadCount('produits'))
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération de la catégorie par slug', [
                'error' => $e->getMessage(),
                'slug' => $slug
            ]);
            
            return response()->json([
                'success' => false,
                'message' => __('categories.messages.show_error')
            ], 500);
        }
    }

    /**
     * Créer une nouvelle catégorie
     */
    public function store(CreateCategorieRequest $request): JsonResponse
    {
        try {
            $categorie = $this->categorieService->createCategory($request->validated());
            
            return response()->json([
                'success' => true,
                'message' => __('categories.messages.store_success'),
                'data' => new CategorieResource($categorie)
            ], 201);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création de la catégorie', [
                'error' => $e->getMessage(),
                'data' => $request->validated()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => __('categories.messages.store_error')
            ], 500);
        }
    }

    /**
     * Mettre à jour une catégorie
     */
    public function update(UpdateCategorieRequest $request, Categorie $categorie): JsonResponse
    {
        try {
            $updatedCategorie = $this->categorieService->updateCategory($categorie, $request->validated());
            
            return response()->json([
                'success' => true,
                'message' => __('categories.messages.update_success'),
                'data' => new CategorieResource($updatedCategorie)
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour de la catégorie', [
                'error' => $e->getMessage(),
                'categorie_id' => $categorie->id,
                'data' => $request->validated()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => __('categories.messages.update_error')
            ], 500);
        }
    }

    /**
     * Supprimer une catégorie
     */
    public function destroy(Categorie $categorie): JsonResponse
    {
        try {
            $deleted = $this->categorieService->deleteCategory($categorie);
            
            if (!$deleted) {
                return response()->json([
                    'success' => false,
                    'message' => __('categories.messages.delete_error')
                ], 500);
            }
            
            return response()->json([
                'success' => true,
                'message' => __('categories.messages.delete_success')
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression de la catégorie', [
                'error' => $e->getMessage(),
                'categorie_id' => $categorie->id
            ]);
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Rechercher des catégories
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $term = $request->get('q');
            
            if (!$term) {
                return response()->json([
                    'success' => false,
                    'message' => __('categories.messages.search_term_required')
                ], 400);
            }
            
            $categories = $this->categorieService->searchCategories($term);
            
            return response()->json([
                'success' => true,
                'message' => __('categories.messages.search_success'),
                'data' => CategorieResource::collection($categories)
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la recherche de catégories', [
                'error' => $e->getMessage(),
                'term' => $request->get('q')
            ]);
            
            return response()->json([
                'success' => false,
                'message' => __('categories.messages.search_error')
            ], 500);
        }
    }
} 