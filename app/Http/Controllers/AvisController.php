<?php

namespace App\Http\Controllers;

use App\Models\Avis;
use App\Http\Resources\AvisResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Stichoza\GoogleTranslate\GoogleTranslate;

class AvisController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 15);
        $lang = $request->header('Accept-Language', 'fr');
        $avis = Avis::paginate($perPage);
        $data = $avis->getCollection()->map(function ($a) use ($lang) {
            return (new AvisResource($a))->toArray(request());
        });
        $paginated = $avis->toArray();
        $paginated['data'] = $data;
        $paginated['total_avis'] = $avis->total();
        return response()->json([
            'success' => true,
            'message' => 'Liste des avis récupérée avec succès',
            'data' => $paginated,
        ]);
    }

    public function show(Request $request, Avis $avi): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Avis récupéré avec succès',
            'data' => (new AvisResource($avi))->toArray($request),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'produit_id' => 'required|exists:produits,id',
            'user_id' => 'required|exists:users,id',
            'titre' => 'nullable|string|max:255',
            'commentaire' => 'required|string',
            'est_approuve' => 'boolean',
            'note' => 'required|integer|min:1|max:5',
        ]);
        $translator = new GoogleTranslate('en');
        $translator->setSource('fr');
        if (!empty($data['titre'])) {
            $data['titre_en'] = $translator->translate($data['titre']);
        }
        if (!empty($data['commentaire'])) {
            $data['commentaire_en'] = $translator->translate($data['commentaire']);
        }
        $avis = Avis::create($data);
        return response()->json([
            'success' => true,
            'message' => 'Avis créé avec succès',
            'data' => (new AvisResource($avis))->toArray($request),
        ], 201);
    }

    public function update(Request $request, Avis $avi): JsonResponse
    {
        $data = $request->validate([
            'produit_id' => 'sometimes|exists:produits,id',
            'user_id' => 'sometimes|exists:users,id',
            'titre' => 'nullable|string|max:255',
            'commentaire' => 'sometimes|string',
            'est_approuve' => 'boolean',
            'note' => 'sometimes|integer|min:1|max:5',
        ]);
        $translator = new GoogleTranslate('en');
        $translator->setSource('fr');
        if (!empty($data['titre'])) {
            $data['titre_en'] = $translator->translate($data['titre']);
        }
        if (!empty($data['commentaire'])) {
            $data['commentaire_en'] = $translator->translate($data['commentaire']);
        }
        $avi->update($data);
        return response()->json([
            'success' => true,
            'message' => 'Avis mis à jour avec succès',
            'data' => (new AvisResource($avi))->toArray($request),
        ]);
    }

    public function destroy(Avis $avi): JsonResponse
    {
        $avi->delete();
        return response()->json([
            'success' => true,
            'message' => 'Avis supprimé avec succès',
        ]);
    }
}
