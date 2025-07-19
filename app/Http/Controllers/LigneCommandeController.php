<?php

namespace App\Http\Controllers;

use App\Models\LigneCommande;
use App\Http\Resources\LigneCommandeResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LigneCommandeController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 15);
        $lignes = LigneCommande::with('produit')->paginate($perPage);
        $data = $lignes->getCollection()->map(function ($ligne) {
            return (new LigneCommandeResource($ligne))->toArray(request());
        });
        $paginated = $lignes->toArray();
        $paginated['data'] = $data;
        $paginated['total_lignes'] = $lignes->total();
        return response()->json([
            'success' => true,
            'message' => 'Liste des lignes de commande récupérée avec succès',
            'data' => $paginated,
        ]);
    }

    public function show(Request $request, LigneCommande $ligneCommande): JsonResponse
    {
        $ligneCommande->load('produit');
        return response()->json([
            'success' => true,
            'message' => 'Ligne de commande récupérée avec succès',
            'data' => (new LigneCommandeResource($ligneCommande))->toArray($request),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'commande_id' => 'required|exists:commandes,id',
            'produit_id' => 'required|exists:produits,id',
            'quantite' => 'required|integer|min:1',
            'prix_unitaire_snapshot' => 'required|numeric',
        ]);
        $ligne = LigneCommande::create($data);
        return response()->json([
            'success' => true,
            'message' => 'Ligne de commande créée avec succès',
            'data' => (new LigneCommandeResource($ligne->load('produit')))->toArray($request),
        ], 201);
    }

    public function update(Request $request, LigneCommande $ligneCommande): JsonResponse
    {
        $data = $request->validate([
            'commande_id' => 'sometimes|exists:commandes,id',
            'produit_id' => 'sometimes|exists:produits,id',
            'quantite' => 'sometimes|integer|min:1',
            'prix_unitaire_snapshot' => 'sometimes|numeric',
        ]);
        $ligneCommande->update($data);
        return response()->json([
            'success' => true,
            'message' => 'Ligne de commande mise à jour avec succès',
            'data' => (new LigneCommandeResource($ligneCommande->load('produit')))->toArray($request),
        ]);
    }

    public function destroy(LigneCommande $ligneCommande): JsonResponse
    {
        $ligneCommande->delete();
        return response()->json([
            'success' => true,
            'message' => 'Ligne de commande supprimée avec succès',
        ]);
    }
}
