<?php

namespace App\Http\Controllers;

use App\Services\FormationService;
use App\Http\Requests\Formation\StoreFormationRequest;
use App\Http\Requests\Formation\UpdateFormationRequest;
use App\Http\Resources\FormationResource;
use App\Models\Formation;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class FormationController extends Controller
{
    protected $formationService;

    public function __construct(FormationService $formationService)
    {
        $this->formationService = $formationService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $filters = [
                'search' => $request->query('search'),
            ];
            $perPage = $request->query('per_page', 15);
            $formations = $this->formationService->list($filters, $perPage);
            
            return response()->json([
                'success' => true,
                'message' => 'Formations récupérées avec succès',
                'data' => FormationResource::collection($formations)
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des formations', [
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des formations',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $formation = $this->formationService->find($id);
            
            if (!$formation) {
                return response()->json([
                    'success' => false,
                    'message' => 'Formation non trouvée'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Formation récupérée avec succès',
                'data' => new FormationResource($formation)
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération de la formation', [
                'formation_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération de la formation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(StoreFormationRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            
            // Gestion de l'image
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image');
            }
            
            $formation = $this->formationService->create($data);
            
            return response()->json([
                'success' => true,
                'message' => 'Formation créée avec succès',
                'data' => new FormationResource($formation)
            ], 201);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création de la formation', [
                'error' => $e->getMessage(),
                'data' => $request->validated()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de la formation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        try {
            $formation = $this->formationService->find($id);
            
            if (!$formation) {
                return response()->json([
                    'success' => false,
                    'message' => 'Formation non trouvée'
                ], 404);
            }
            
            // Récupérer toutes les données de la requête
            $allData = $request->all();
            $files = $request->allFiles();
            
            Log::info('Données reçues dans la requête', [
                'formation_id' => $id,
                'all_data' => $allData,
                'files' => array_keys($files),
                'request_method' => $request->method(),
                'content_type' => $request->header('Content-Type'),
                'has_nom' => $request->has('nom'),
                'nom_value' => $request->input('nom'),
                'has_description' => $request->has('description'),
                'description_value' => $request->input('description'),
                'raw_input' => $request->getContent(),
            ]);
            
            // Préparer les données pour la validation
            $data = [];
            
            // Champs textuels
            $textFields = ['nom', 'description', 'duree', 'tarif', 'slug'];
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
                'formation_id' => $id,
                'prepared_data' => $data
            ]);
            
            // Validation manuelle
            $validator = \Validator::make($data, [
                'nom' => 'sometimes|string|max:255|min:2',
                'description' => 'sometimes|nullable|string|max:1000',
                'duree' => 'sometimes|nullable|string|max:100',
                'tarif' => 'sometimes|nullable|string|max:50',
                'slug' => 'sometimes|string|max:255|min:2',
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
                'formation_id' => $id,
                'validated_data' => $validatedData
            ]);
            
            $formation = $this->formationService->update($formation, $validatedData);
            
            return response()->json([
                'success' => true,
                'message' => 'Formation mise à jour avec succès',
                'data' => new FormationResource($formation)
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour de la formation', [
                'formation_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour de la formation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $formation = $this->formationService->find($id);
            
            if (!$formation) {
                return response()->json([
                    'success' => false,
                    'message' => 'Formation non trouvée'
                ], 404);
            }
            
            $this->formationService->delete($formation);
            
            return response()->json([
                'success' => true,
                'message' => 'Formation supprimée avec succès'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression de la formation', [
                'formation_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression de la formation',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 