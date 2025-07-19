<?php

namespace App\Http\Controllers;

use App\Models\ArticleBlog;
use App\Http\Resources\ArticleBlogResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Stichoza\GoogleTranslate\GoogleTranslate;

class ArticleBlogController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 15);
        $lang = $request->header('Accept-Language', 'fr');
        $articles = ArticleBlog::paginate($perPage);
        $data = $articles->getCollection()->map(function ($article) use ($lang) {
            return (new ArticleBlogResource($article))->toArray(request());
        });
        $paginated = $articles->toArray();
        $paginated['data'] = $data;
        $paginated['total_articles'] = $articles->total();
        return response()->json([
            'success' => true,
            'message' => 'Liste des articles récupérée avec succès',
            'data' => $paginated,
        ]);
    }

    public function show(Request $request, ArticleBlog $article): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Article récupéré avec succès',
            'data' => (new ArticleBlogResource($article))->toArray($request),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'titre' => 'required|string|max:255',
            'contenu' => 'required|string',
            'meta_titre' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'slug' => 'required|string|unique:article_blogs,slug',
            'date_publication' => 'nullable|date',
        ]);
        $translator = new GoogleTranslate('en');
        $translator->setSource('fr');
        $data['titre_en'] = $translator->translate($data['titre']);
        if (!empty($data['contenu'])) {
            $data['contenu_en'] = $translator->translate($data['contenu']);
        }
        $article = ArticleBlog::create($data);
        return response()->json([
            'success' => true,
            'message' => 'Article créé avec succès',
            'data' => (new ArticleBlogResource($article))->toArray($request),
        ], 201);
    }

    public function update(Request $request, ArticleBlog $article): JsonResponse
    {
        $data = $request->validate([
            'user_id' => 'sometimes|exists:users,id',
            'titre' => 'sometimes|string|max:255',
            'contenu' => 'sometimes|string',
            'meta_titre' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'slug' => 'sometimes|string|unique:article_blogs,slug,' . $article->id,
            'date_publication' => 'nullable|date',
        ]);
        $translator = new GoogleTranslate('en');
        $translator->setSource('fr');
        if (!empty($data['titre'])) {
            $data['titre_en'] = $translator->translate($data['titre']);
        }
        if (!empty($data['contenu'])) {
            $data['contenu_en'] = $translator->translate($data['contenu']);
        }
        $article->update($data);
        return response()->json([
            'success' => true,
            'message' => 'Article mis à jour avec succès',
            'data' => (new ArticleBlogResource($article))->toArray($request),
        ]);
    }

    public function destroy(ArticleBlog $article): JsonResponse
    {
        $article->delete();
        return response()->json([
            'success' => true,
            'message' => 'Article supprimé avec succès',
        ]);
    }
}
