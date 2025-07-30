<?php

namespace App\Http\Controllers;

use App\Services\ArticleBlogService;
use App\Http\Requests\ArticleBlog\StoreArticleBlogRequest;
use App\Http\Requests\ArticleBlog\UpdateArticleBlogRequest;
use App\Http\Resources\ArticleBlogResource;
use App\Models\ArticleBlog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ArticleBlogController extends Controller
{
    protected $articleBlogService;

    public function __construct(ArticleBlogService $articleBlogService)
    {
        $this->articleBlogService = $articleBlogService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $filters = [
                'search' => $request->query('search'),
            ];
            $perPage = $request->query('per_page', 15);
            $articles = $this->articleBlogService->list($filters, $perPage);
            
            return response()->json([
                'success' => true,
                'message' => 'Articles récupérés avec succès',
                'data' => ArticleBlogResource::collection($articles)
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des articles', [
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des articles',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $article = $this->articleBlogService->find($id);
            
            if (!$article) {
                return response()->json([
                    'success' => false,
                    'message' => 'Article non trouvé'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Article récupéré avec succès',
                'data' => new ArticleBlogResource($article)
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération de l\'article', [
                'article_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération de l\'article',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(StoreArticleBlogRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            
            // Gestion de l'image
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image');
            }
            
            $article = $this->articleBlogService->create($data);
            
            return response()->json([
                'success' => true,
                'message' => 'Article créé avec succès',
                'data' => new ArticleBlogResource($article)
            ], 201);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création de l\'article', [
                'error' => $e->getMessage(),
                'data' => $request->validated()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de l\'article',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        try {
            $article = $this->articleBlogService->find($id);
            
            if (!$article) {
                return response()->json([
                    'success' => false,
                    'message' => 'Article non trouvé'
                ], 404);
            }
            
            // Récupérer toutes les données de la requête
            $allData = $request->all();
            $files = $request->allFiles();
            
            Log::info('Données reçues dans la requête', [
                'article_id' => $id,
                'all_data' => $allData,
                'files' => array_keys($files),
                'request_method' => $request->method(),
                'content_type' => $request->header('Content-Type'),
                'has_titre' => $request->has('titre'),
                'titre_value' => $request->input('titre'),
                'has_contenu' => $request->has('contenu'),
                'contenu_value' => $request->input('contenu'),
                'raw_input' => $request->getContent(),
            ]);
            
            // Préparer les données pour la validation
            $data = [];
            
            // Champs textuels
            $textFields = ['titre', 'contenu', 'meta_titre', 'meta_description', 'date_publication', 'user_id', 'slug'];
            foreach ($textFields as $field) {
                if ($request->has($field) && $request->input($field) !== null) {
                    $data[$field] = trim($request->input($field));
                }
            }
            
            // Gestion de l'image
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image');
            }
            
            Log::info('Données préparées pour validation', [
                'article_id' => $id,
                'prepared_data' => $data
            ]);
            
            // Validation manuelle
            $validator = \Validator::make($data, [
                'titre' => 'sometimes|string|max:255|min:2',
                'contenu' => 'sometimes|string|min:10',
                'meta_titre' => 'sometimes|nullable|string|max:255',
                'meta_description' => 'sometimes|nullable|string',
                'date_publication' => 'sometimes|nullable|date',
                'user_id' => 'sometimes|integer|exists:users,id',
                'slug' => 'sometimes|string|max:255|min:2|unique:article_blogs,slug,' . $id,
                'image' => 'sometimes|nullable|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur de validation',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            $validatedData = $validator->validated();
            
            // Filtrer les données vides
            $validatedData = array_filter($validatedData, function($value) {
                return $value !== null && $value !== '';
            });
            
            Log::info('Données validées et filtrées', [
                'article_id' => $id,
                'validated_data' => $validatedData
            ]);
            
            $article = $this->articleBlogService->update($article, $validatedData);
            
            return response()->json([
                'success' => true,
                'message' => 'Article mis à jour avec succès',
                'data' => new ArticleBlogResource($article)
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour de l\'article', [
                'article_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour de l\'article',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $article = $this->articleBlogService->find($id);
            
            if (!$article) {
                return response()->json([
                    'success' => false,
                    'message' => 'Article non trouvé'
                ], 404);
            }
            
            $this->articleBlogService->delete($article);
            
            return response()->json([
                'success' => true,
                'message' => 'Article supprimé avec succès'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression de l\'article', [
                'article_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression de l\'article',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
