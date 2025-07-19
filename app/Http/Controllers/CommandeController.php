<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Http\Resources\CommandeResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CommandeController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 15);
        $commandes = Commande::with(['user', 'lignes.produit'])->paginate($perPage);
        $data = $commandes->getCollection()->map(function ($commande) {
            return (new CommandeResource($commande))->toArray(request());
        });
        $paginated = $commandes->toArray();
        $paginated['data'] = $data;
        $paginated['total_commandes'] = $commandes->total();
        return response()->json([
            'success' => true,
            'message' => 'Liste des commandes récupérée avec succès',
            'data' => $paginated,
        ]);
    }

    public function show(Request $request, Commande $commande): JsonResponse
    {
        $commande->load(['user', 'lignes.produit']);
        return response()->json([
            'success' => true,
            'message' => 'Commande récupérée avec succès',
            'data' => (new CommandeResource($commande))->toArray($request),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'numero_commande' => 'required|string|unique:commandes,numero_commande',
            'statut' => 'in:en_attente,payee,expediee,annulee',
            'total_commande' => 'required|numeric',
            'adresse_livraison_snapshot' => 'required|string',
            'adresse_facturation_snapshot' => 'required|string',
        ]);
        $translator = new \Stichoza\GoogleTranslate\GoogleTranslate('en');
        $translator->setSource('fr');
        if (!empty($data['adresse_livraison_snapshot'])) {
            $data['adresse_livraison_snapshot_en'] = $translator->translate($data['adresse_livraison_snapshot']);
        }
        if (!empty($data['adresse_facturation_snapshot'])) {
            $data['adresse_facturation_snapshot_en'] = $translator->translate($data['adresse_facturation_snapshot']);
        }
        $commande = Commande::create($data);
        return response()->json([
            'success' => true,
            'message' => 'Commande créée avec succès',
            'data' => (new CommandeResource($commande->load(['user', 'lignes.produit'])))->toArray($request),
        ], 201);
    }

    public function update(Request $request, Commande $commande): JsonResponse
    {
        $data = $request->validate([
            'user_id' => 'sometimes|exists:users,id',
            'numero_commande' => 'sometimes|string|unique:commandes,numero_commande,' . $commande->id,
            'statut' => 'in:en_attente,payee,expediee,annulee',
            'total_commande' => 'sometimes|numeric',
            'adresse_livraison_snapshot' => 'sometimes|string',
            'adresse_facturation_snapshot' => 'sometimes|string',
        ]);
        $translator = new \Stichoza\GoogleTranslate\GoogleTranslate('en');
        $translator->setSource('fr');
        if (!empty($data['adresse_livraison_snapshot'])) {
            $data['adresse_livraison_snapshot_en'] = $translator->translate($data['adresse_livraison_snapshot']);
        }
        if (!empty($data['adresse_facturation_snapshot'])) {
            $data['adresse_facturation_snapshot_en'] = $translator->translate($data['adresse_facturation_snapshot']);
        }
        $commande->update($data);
        return response()->json([
            'success' => true,
            'message' => 'Commande mise à jour avec succès',
            'data' => (new CommandeResource($commande->load(['user', 'lignes.produit'])))->toArray($request),
        ]);
    }

    public function destroy(Commande $commande): JsonResponse
    {
        $commande->delete();
        return response()->json([
            'success' => true,
            'message' => 'Commande supprimée avec succès',
        ]);
    }
}
