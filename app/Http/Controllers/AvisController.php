<?php

namespace App\Http\Controllers;

use App\Models\Avis;
use App\Http\Resources\AvisResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Illuminate\Support\Facades\Log;

class AvisController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        Log::info('Appel à AvisController@index', ['params' => $request->all()]);
        try {
            $perPage = $request->get('per_page', 15);
            $lang = $request->header('Accept-Language', 'fr');
            $avis = Avis::paginate($perPage);
            $data = $avis->getCollection()->map(function ($a) use ($lang) {
                return (new AvisResource($a))->toArray(request());
            });
            $paginated = $avis->toArray();
            $paginated['data'] = $data;
            $paginated['total_avis'] = $avis->total();
            Log::info('Succès AvisController@index');
            return response()->json([
                'success' => true,
                'message' => 'Liste des avis récupérée avec succès',
                'data' => $paginated,
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur AvisController@index', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des avis',
            ], 500);
        }
    }

    public function show(Request $request, Avis $avi): JsonResponse
    {
        Log::info('Appel à AvisController@show', ['avi_id' => $avi->id]);
        try {
            return response()->json([
                'success' => true,
                'message' => 'Avis récupéré avec succès',
                'data' => (new AvisResource($avi))->toArray($request),
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur AvisController@show', ['error' => $e->getMessage(), 'avi_id' => $avi->id]);
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération de l\'avis',
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        Log::info('Appel à AvisController@store', ['params' => $request->all()]);
        try {
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
            Log::info('Succès AvisController@store', ['avis_id' => $avis->id]);
            return response()->json([
                'success' => true,
                'message' => 'Avis créé avec succès',
                'data' => (new AvisResource($avis))->toArray($request),
            ], 201);
        } catch (\Exception $e) {
            Log::error('Erreur AvisController@store', ['error' => $e->getMessage(), 'params' => $request->all()]);
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de l\'avis',
            ], 500);
        }
    }

    public function update(Request $request, Avis $avi): JsonResponse
    {
        Log::info('Appel à AvisController@update', ['avi_id' => $avi->id, 'params' => $request->all()]);
        try {
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
            Log::info('Succès AvisController@update', ['avi_id' => $avi->id]);
            return response()->json([
                'success' => true,
                'message' => 'Avis mis à jour avec succès',
                'data' => (new AvisResource($avi))->toArray($request),
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur AvisController@update', ['error' => $e->getMessage(), 'avi_id' => $avi->id, 'params' => $request->all()]);
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour de l\'avis',
            ], 500);
        }
    }

    public function destroy(Avis $avi): JsonResponse
    {
        Log::info('Appel à AvisController@destroy', ['avi_id' => $avi->id]);
        try {
            $avi->delete();
            Log::info('Succès AvisController@destroy', ['avi_id' => $avi->id]);
            return response()->json([
                'success' => true,
                'message' => 'Avis supprimé avec succès',
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur AvisController@destroy', ['error' => $e->getMessage(), 'avi_id' => $avi->id]);
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression de l\'avis',
            ], 500);
        }
    }
}
