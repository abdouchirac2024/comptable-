<?php

namespace App\Http\Controllers;

use App\Services\ArticleBlogService;
use App\Http\Requests\ArticleBlog\StoreArticleBlogRequest;
use App\Http\Requests\ArticleBlog\UpdateArticleBlogRequest;
use App\Http\Resources\ArticleBlogResource;
use App\Models\ArticleBlog;
use Illuminate\Http\Request;

class ArticleBlogController extends Controller
{
    protected $articleBlogService;

    public function __construct(ArticleBlogService $articleBlogService)
    {
        $this->articleBlogService = $articleBlogService;
    }

    public function index(Request $request)
    {
        $filters = [
            'search' => $request->query('search'),
        ];
        $perPage = $request->query('per_page', 15);
        $articles = $this->articleBlogService->list($filters, $perPage);
        return ArticleBlogResource::collection($articles);
    }

    public function show($id)
    {
        $article = $this->articleBlogService->find($id);
        if (!$article) {
            return response()->json(['message' => 'Article non trouvé'], 404);
        }
        return new ArticleBlogResource($article);
    }

    public function store(StoreArticleBlogRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image');
        }
        $article = $this->articleBlogService->create($data);
        return new ArticleBlogResource($article);
    }

    public function update(UpdateArticleBlogRequest $request, $id)
    {
        $article = $this->articleBlogService->find($id);
        if (!$article) {
            return response()->json(['message' => 'Article non trouvé'], 404);
        }
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image');
        }
        $article = $this->articleBlogService->update($article, $data);
        return new ArticleBlogResource($article);
    }

    public function destroy($id)
    {
        $article = $this->articleBlogService->find($id);
        if (!$article) {
            return response()->json(['message' => 'Article non trouvé'], 404);
        }
        $this->articleBlogService->delete($article);
        return response()->json(['message' => 'Article supprimé avec succès']);
    }
}
