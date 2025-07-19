<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Http\Resources\ProduitResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Stichoza\GoogleTranslate\GoogleTranslate;

class ProduitController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 15);
        $lang = $request->header('Accept-Language', 'fr');
        $produits = Produit::with('categorie')->paginate($perPage);
        $data = $produits->getCollection()->map(function ($produit) use ($lang) {
            return (new ProduitResource($produit))->toArray(request());
        });
        $paginated = $produits->toArray();
        $paginated['data'] = $data;
        $paginated['total_produits'] = $produits->total();
        return response()->json([
            'success' => true,
            'message' => 'Liste des produits récupérée avec succès',
            'data' => $paginated,
        ]);
    }

    public function show(Request $request, Produit $produit): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Produit récupéré avec succès',
            'data' => (new ProduitResource($produit->load('categorie')))->toArray($request),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'categorie_id' => 'required|exists:categories,id',
            'nom' => 'required|string|max:255',
            'description_courte' => 'nullable|string',
            'description_longue' => 'nullable|string',
            'stock' => 'required|integer',
            'est_en_vedette' => 'boolean',
            'prix' => 'required|numeric',
            'reference_sku' => 'nullable|string|unique:produits,reference_sku',
            'slug' => 'required|string|unique:produits,slug',
        ]);
        $translator = new GoogleTranslate('en');
        $translator->setSource('fr');
        $data['nom_en'] = $translator->translate($data['nom']);
        if (!empty($data['description_courte'])) {
            $data['description_courte_en'] = $translator->translate($data['description_courte']);
        }
        if (!empty($data['description_longue'])) {
            $data['description_longue_en'] = $translator->translate($data['description_longue']);
        }
        $produit = Produit::create($data);
        return response()->json([
            'success' => true,
            'message' => 'Produit créé avec succès',
            'data' => (new ProduitResource($produit))->toArray($request),
        ], 201);
    }

    public function update(Request $request, Produit $produit): JsonResponse
    {
        $data = $request->validate([
            'categorie_id' => 'sometimes|exists:categories,id',
            'nom' => 'sometimes|string|max:255',
            'description_courte' => 'nullable|string',
            'description_longue' => 'nullable|string',
            'stock' => 'sometimes|integer',
            'est_en_vedette' => 'boolean',
            'prix' => 'sometimes|numeric',
            'reference_sku' => 'nullable|string|unique:produits,reference_sku,' . $produit->id,
            'slug' => 'sometimes|string|unique:produits,slug,' . $produit->id,
        ]);
        $translator = new GoogleTranslate('en');
        $translator->setSource('fr');
        if (!empty($data['nom'])) {
            $data['nom_en'] = $translator->translate($data['nom']);
        }
        if (!empty($data['description_courte'])) {
            $data['description_courte_en'] = $translator->translate($data['description_courte']);
        }
        if (!empty($data['description_longue'])) {
            $data['description_longue_en'] = $translator->translate($data['description_longue']);
        }
        $produit->update($data);
        return response()->json([
            'success' => true,
            'message' => 'Produit mis à jour avec succès',
            'data' => (new ProduitResource($produit))->toArray($request),
        ]);
    }

    public function destroy(Produit $produit): JsonResponse
    {
        $produit->delete();
        return response()->json([
            'success' => true,
            'message' => 'Produit supprimé avec succès',
        ]);
    }

    public function total(): JsonResponse
    {
        $total = Produit::count();
        return response()->json([
            'success' => true,
            'total_produits' => $total,
        ]);
    }
}
