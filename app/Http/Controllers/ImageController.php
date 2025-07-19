<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Http\Resources\ImageResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Stichoza\GoogleTranslate\GoogleTranslate;

class ImageController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 15);
        $lang = $request->header('Accept-Language', 'fr');
        $images = Image::paginate($perPage);
        $data = $images->getCollection()->map(function ($image) use ($lang) {
            return (new ImageResource($image))->toArray(request());
        });
        $paginated = $images->toArray();
        $paginated['data'] = $data;
        $paginated['total_images'] = $images->total();
        return response()->json([
            'success' => true,
            'message' => 'Liste des images récupérée avec succès',
            'data' => $paginated,
        ]);
    }

    public function show(Request $request, Image $image): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Image récupérée avec succès',
            'data' => (new ImageResource($image))->toArray($request),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        \Log::info('POST /images reçu', ['input' => $request->all(), 'files' => $request->allFiles()]);
        try {
            $data = $request->validate([
                'produit_id' => 'required|exists:produits,id',
                'url_image' => 'required|file|mimes:jpg,jpeg,png,gif,webp',
                'Description' => 'nullable|string',
                'est_principale' => 'nullable',
            ]);
            // Cast manuel du booléen si présent
            if (isset($data['est_principale'])) {
                $data['est_principale'] = filter_var($data['est_principale'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? false;
            }
            // Sauvegarde du fichier
            if ($request->hasFile('url_image')) {
                $path = $request->file('url_image')->store('images', 'public');
                $data['url_image'] = $path;
            }
            $translator = new \Stichoza\GoogleTranslate\GoogleTranslate('en');
            $translator->setSource('fr');
            if (!empty($data['Description'])) {
                $data['description_en'] = $translator->translate($data['Description']);
            }
            $image = \App\Models\Image::create($data);
            return response()->json([
                'success' => true,
                'message' => 'Image créée avec succès',
                'data' => new \App\Http\Resources\ImageResource($image),
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::warning('Erreur de validation POST /images', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Erreur lors du POST /images', ['exception' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Erreur serveur lors de la création de l\'image',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, Image $image): JsonResponse
    {
        $data = $request->validate([
            'produit_id' => 'sometimes|exists:produits,id',
            'url_image' => 'sometimes|file|mimes:jpg,jpeg,png,gif,webp',
            'Description' => 'nullable|string',
            'est_principale' => 'boolean',
        ]);
        // Sauvegarde du fichier si présent
        if ($request->hasFile('url_image')) {
            $path = $request->file('url_image')->store('images', 'public');
            $data['url_image'] = $path;
        }
        $translator = new \Stichoza\GoogleTranslate\GoogleTranslate('en');
        $translator->setSource('fr');
        if (!empty($data['Description'])) {
            $data['description_en'] = $translator->translate($data['Description']);
        }
        $image->update($data);
        return response()->json([
            'success' => true,
            'message' => 'Image mise à jour avec succès',
            'data' => new \App\Http\Resources\ImageResource($image),
        ]);
    }

    public function destroy(Image $image): JsonResponse
    {
        $image->delete();
        return response()->json([
            'success' => true,
            'message' => 'Image supprimée avec succès',
        ]);
    }
}
