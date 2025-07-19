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
            $lang = $request->header('Accept-Language', 'fr');
            $categories = $this->categorieService->getPaginatedCategories($perPage);
            $data = $categories->getCollection()->map(function ($categorie) use ($lang) {
                return [
                    'id' => $categorie->id,
                    'nom' => $lang === 'en' ? $categorie->nom_en : $categorie->nom,
                    'description' => $lang === 'en' ? $categorie->description_en : $categorie->description,
                    'slug' => $categorie->slug,
                    'produits_count' => $categorie->produits_count ?? null,
                    'nom_en' => $categorie->nom_en,
                    'description_en' => $categorie->description_en,
                    'created_at' => $categorie->created_at,
                    'updated_at' => $categorie->updated_at,
                ];
            });
            $paginated = $categories->toArray();
            $paginated['data'] = $data;
            return response()->json([
                'success' => true,
                'message' => __('categories.messages.index_success'),
                'data' => $paginated
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
    public function all(Request $request): JsonResponse
    {
        try {
            $lang = $request->header('Accept-Language', 'fr');
            $categories = $this->categorieService->getAllCategories();
            $data = $categories->map(function ($categorie) use ($lang) {
                return [
                    'id' => $categorie->id,
                    'nom' => $lang === 'en' ? $categorie->nom_en : $categorie->nom,
                    'description' => $lang === 'en' ? $categorie->description_en : $categorie->description,
                    'slug' => $categorie->slug,
                    'produits_count' => $categorie->produits_count ?? null,
                    'nom_en' => $categorie->nom_en,
                    'description_en' => $categorie->description_en,
                    'created_at' => $categorie->created_at,
                    'updated_at' => $categorie->updated_at,
                ];
            });
            return response()->json([
                'success' => true,
                'message' => __('categories.messages.all_success'),
                'data' => $data
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
    public function show(Request $request, Categorie $categorie): JsonResponse
    {
        try {
            $lang = $request->header('Accept-Language', 'fr');
            return response()->json([
                'success' => true,
                'message' => __('categories.messages.show_success'),
                'data' => [
                    'id' => $categorie->id,
                    'nom' => $lang === 'en' ? $categorie->nom_en : $categorie->nom,
                    'description' => $lang === 'en' ? $categorie->description_en : $categorie->description,
                    'slug' => $categorie->slug,
                    'produits_count' => $categorie->produits_count ?? null,
                    'nom_en' => $categorie->nom_en,
                    'description_en' => $categorie->description_en,
                    'created_at' => $categorie->created_at,
                    'updated_at' => $categorie->updated_at,
                ]
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
    public function showBySlug(Request $request, string $slug): JsonResponse
    {
        try {
            $lang = $request->header('Accept-Language', 'fr');
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
                'data' => [
                    'id' => $categorie->id,
                    'nom' => $lang === 'en' ? $categorie->nom_en : $categorie->nom,
                    'description' => $lang === 'en' ? $categorie->description_en : $categorie->description,
                    'slug' => $categorie->slug,
                    'produits_count' => $categorie->produits_count ?? null,
                    'nom_en' => $categorie->nom_en,
                    'description_en' => $categorie->description_en,
                    'created_at' => $categorie->created_at,
                    'updated_at' => $categorie->updated_at,
                ]
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
     * Créer une nouvelle catégorie avec traduction automatique
     */
    public function store(CreateCategorieRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $translator = new \Stichoza\GoogleTranslate\GoogleTranslate('en');
            $translator->setSource('fr');
            $data['nom_en'] = $translator->translate($data['nom']);
            if (!empty($data['description'])) {
                $data['description_en'] = $translator->translate($data['description']);
            }
            $categorie = $this->categorieService->createCategory($data);
            return response()->json([
                'success' => true,
                'message' => __('categories.messages.store_success'),
                'data' => $categorie
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
     * Mettre à jour une catégorie avec traduction automatique
     */
    public function update(UpdateCategorieRequest $request, Categorie $categorie): JsonResponse
    {
        try {
            $data = $request->validated();
            $translator = new \Stichoza\GoogleTranslate\GoogleTranslate('en');
            $translator->setSource('fr');
            $data['nom_en'] = $translator->translate($data['nom']);
            if (!empty($data['description'])) {
                $data['description_en'] = $translator->translate($data['description']);
            }
            $updatedCategorie = $this->categorieService->updateCategory($categorie, $data);
            return response()->json([
                'success' => true,
                'message' => __('categories.messages.update_success'),
                'data' => $updatedCategorie
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
