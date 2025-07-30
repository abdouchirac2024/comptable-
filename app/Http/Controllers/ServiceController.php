<?php

namespace App\Http\Controllers;

use App\Services\ServiceService;
use App\Http\Requests\Service\StoreServiceRequest;
use App\Http\Requests\Service\UpdateServiceRequest;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ServiceController extends Controller
{
    protected $serviceService;

    public function __construct(ServiceService $serviceService)
    {
        $this->serviceService = $serviceService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $filters = [
                'search' => $request->query('search'),
            ];
            $perPage = $request->query('per_page', 15);
            $services = $this->serviceService->list($filters, $perPage);
            
            return response()->json([
                'success' => true,
                'message' => 'Services récupérés avec succès',
                'data' => ServiceResource::collection($services)
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des services', [
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des services',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $service = $this->serviceService->find($id);
            
            if (!$service) {
                return response()->json([
                    'success' => false,
                    'message' => 'Service non trouvé'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Service récupéré avec succès',
                'data' => new ServiceResource($service)
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération du service', [
                'service_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération du service',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(StoreServiceRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            
            // Gestion de l'image
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image');
            }
            
            $service = $this->serviceService->create($data);
            
            return response()->json([
                'success' => true,
                'message' => 'Service créé avec succès',
                'data' => new ServiceResource($service)
            ], 201);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création du service', [
                'error' => $e->getMessage(),
                'data' => $request->validated()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création du service',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        try {
            $service = $this->serviceService->find($id);
            
            if (!$service) {
                return response()->json([
                    'success' => false,
                    'message' => 'Service non trouvé'
                ], 404);
            }
            
            // Récupérer toutes les données de la requête
            $allData = $request->all();
            $files = $request->allFiles();
            
            Log::info('Données reçues dans la requête', [
                'service_id' => $id,
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
            $textFields = ['nom', 'description', 'categorie', 'tarif', 'duree', 'slug'];
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
                'service_id' => $id,
                'prepared_data' => $data
            ]);
            
            // Validation manuelle
            $validator = \Validator::make($data, [
                'nom' => 'sometimes|string|max:255|min:2',
                'description' => 'sometimes|string|min:10',
                'categorie' => 'sometimes|string|max:255',
                'tarif' => 'sometimes|nullable|string|max:255',
                'duree' => 'sometimes|nullable|string|max:255',
                'slug' => 'sometimes|string|max:255|min:2|unique:services,slug,' . $id,
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
                'service_id' => $id,
                'validated_data' => $validatedData
            ]);
            
            $service = $this->serviceService->update($service, $validatedData);
            
            return response()->json([
                'success' => true,
                'message' => 'Service mis à jour avec succès',
                'data' => new ServiceResource($service)
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour du service', [
                'service_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du service',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $service = $this->serviceService->find($id);
            
            if (!$service) {
                return response()->json([
                    'success' => false,
                    'message' => 'Service non trouvé'
                ], 404);
            }
            
            $this->serviceService->delete($service);
            
            return response()->json([
                'success' => true,
                'message' => 'Service supprimé avec succès'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression du service', [
                'service_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression du service',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 