<?php

namespace App\Http\Controllers;

use App\Services\HeroSlideService;
use App\Http\Requests\HeroSlide\StoreHeroSlideRequest;
use App\Http\Requests\HeroSlide\UpdateHeroSlideRequest;
use App\Http\Resources\HeroSlideResource;
use App\Models\HeroSlide;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class HeroSlideController extends Controller
{
    protected $heroSlideService;

    public function __construct(HeroSlideService $heroSlideService)
    {
        $this->heroSlideService = $heroSlideService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $filters = [
                'search' => $request->query('search'),
                'is_active' => $request->query('is_active'),
                'hero_section_id' => $request->query('hero_section_id'),
            ];
            $perPage = $request->query('per_page', 15);
            $heroSlides = $this->heroSlideService->list($filters, $perPage);
            
            return response()->json([
                'success' => true,
                'message' => 'Slides Hero récupérés avec succès',
                'data' => HeroSlideResource::collection($heroSlides)
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des slides Hero', [
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des slides Hero',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $heroSlide = $this->heroSlideService->find($id);
            
            if (!$heroSlide) {
                return response()->json([
                    'success' => false,
                    'message' => 'Slide Hero non trouvé'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Slide Hero récupéré avec succès',
                'data' => new HeroSlideResource($heroSlide)
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération du slide Hero', [
                'hero_slide_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération du slide Hero',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function bySection($heroSectionId, Request $request): JsonResponse
    {
        try {
            $filters = [
                'search' => $request->query('search'),
                'is_active' => $request->query('is_active'),
                'per_page' => $request->query('per_page', 15),
            ];
            
            $heroSlides = $this->heroSlideService->findBySection($heroSectionId, $filters);
            
            return response()->json([
                'success' => true,
                'message' => 'Slides Hero de la section récupérés avec succès',
                'data' => HeroSlideResource::collection($heroSlides)
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des slides Hero de la section', [
                'hero_section_id' => $heroSectionId,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des slides Hero de la section',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(StoreHeroSlideRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            
            // Gestion de l'image de fond
            if ($request->hasFile('background_image')) {
                $data['background_image'] = $request->file('background_image');
            }
            
            $heroSlide = $this->heroSlideService->create($data);
            
            return response()->json([
                'success' => true,
                'message' => 'Slide Hero créé avec succès',
                'data' => new HeroSlideResource($heroSlide)
            ], 201);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création du slide Hero', [
                'error' => $e->getMessage(),
                'data' => $request->validated()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création du slide Hero',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        try {
            $heroSlide = $this->heroSlideService->find($id);
            
            if (!$heroSlide) {
                return response()->json([
                    'success' => false,
                    'message' => 'Slide Hero non trouvé'
                ], 404);
            }
            
            // Récupérer toutes les données de la requête
            $allData = $request->all();
            $files = $request->allFiles();
            
            Log::info('Données reçues dans la requête', [
                'hero_slide_id' => $id,
                'all_data' => $allData,
                'files' => array_keys($files),
                'request_method' => $request->method(),
                'content_type' => $request->header('Content-Type'),
            ]);
            
            // Préparer les données pour la validation
            $data = [];
            
            // Champs textuels
            $textFields = ['title', 'subtitle', 'description', 'gradient', 'slide_duration', 'slide_order', 'is_active'];
            foreach ($textFields as $field) {
                if ($request->has($field) && $request->input($field) !== null) {
                    $data[$field] = trim($request->input($field));
                }
            }
            
            // Gestion de l'image de fond
            if ($request->hasFile('background_image')) {
                $data['background_image'] = $request->file('background_image');
            }
            
            Log::info('Données préparées pour validation', [
                'hero_slide_id' => $id,
                'prepared_data' => $data
            ]);
            
            // Validation manuelle
            $validator = \Validator::make($data, [
                'title' => 'sometimes|string|max:255|min:2',
                'subtitle' => 'sometimes|nullable|string|max:255',
                'description' => 'sometimes|nullable|string|max:1000',
                'gradient' => 'sometimes|nullable|string|max:255',
                'slide_duration' => 'sometimes|integer|min:1000|max:30000',
                'slide_order' => 'sometimes|integer|min:0',
                'is_active' => 'sometimes|boolean',
                'background_image' => 'sometimes|nullable|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
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
                'hero_slide_id' => $id,
                'validated_data' => $validatedData
            ]);
            
            $heroSlide = $this->heroSlideService->update($heroSlide, $validatedData);
            
            return response()->json([
                'success' => true,
                'message' => 'Slide Hero mis à jour avec succès',
                'data' => new HeroSlideResource($heroSlide)
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour du slide Hero', [
                'hero_slide_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du slide Hero',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $heroSlide = $this->heroSlideService->find($id);
            
            if (!$heroSlide) {
                return response()->json([
                    'success' => false,
                    'message' => 'Slide Hero non trouvé'
                ], 404);
            }
            
            $this->heroSlideService->delete($heroSlide);
            
            return response()->json([
                'success' => true,
                'message' => 'Slide Hero supprimé avec succès'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression du slide Hero', [
                'hero_slide_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression du slide Hero',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function reorder(Request $request, $heroSectionId): JsonResponse
    {
        try {
            $request->validate([
                'slide_orders' => 'required|array',
                'slide_orders.*' => 'integer|min:0'
            ]);

            $slideOrders = $request->input('slide_orders');
            
            $this->heroSlideService->reorderSlides($heroSectionId, $slideOrders);
            
            return response()->json([
                'success' => true,
                'message' => 'Slides réordonnés avec succès'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors du réordonnancement des slides', [
                'hero_section_id' => $heroSectionId,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du réordonnancement des slides',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function activate($id): JsonResponse
    {
        try {
            $heroSlide = $this->heroSlideService->find($id);
            
            if (!$heroSlide) {
                return response()->json([
                    'success' => false,
                    'message' => 'Slide Hero non trouvé'
                ], 404);
            }
            
            $heroSlide = $this->heroSlideService->activate($heroSlide);
            
            return response()->json([
                'success' => true,
                'message' => 'Slide Hero activé avec succès',
                'data' => new HeroSlideResource($heroSlide)
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'activation du slide Hero', [
                'hero_slide_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'activation du slide Hero',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function deactivate($id): JsonResponse
    {
        try {
            $heroSlide = $this->heroSlideService->find($id);
            
            if (!$heroSlide) {
                return response()->json([
                    'success' => false,
                    'message' => 'Slide Hero non trouvé'
                ], 404);
            }
            
            $heroSlide = $this->heroSlideService->deactivate($heroSlide);
            
            return response()->json([
                'success' => true,
                'message' => 'Slide Hero désactivé avec succès',
                'data' => new HeroSlideResource($heroSlide)
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la désactivation du slide Hero', [
                'hero_slide_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la désactivation du slide Hero',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Ajouter une image à un slide
     */
    public function addImage(Request $request, HeroSlide $heroSlide)
    {
        try {
            $request->validate([
                'image' => 'required|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
                'alt_text' => 'nullable|string|max:255',
                'caption' => 'nullable|string|max:255',
            ]);

            $heroSlide = $this->heroSlideService->addImage(
                $heroSlide,
                $request->file('image'),
                $request->input('alt_text'),
                $request->input('caption')
            );

            return response()->json([
                'success' => true,
                'message' => 'Image ajoutée avec succès',
                'data' => new HeroSlideResource($heroSlide)
            ], 200);

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'ajout d\'image au slide', [
                'hero_slide_id' => $heroSlide->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'ajout de l\'image',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Supprimer une image d'un slide
     */
    public function removeImage(Request $request, HeroSlide $heroSlide)
    {
        try {
            $request->validate([
                'image_path' => 'required|string',
            ]);

            $heroSlide = $this->heroSlideService->removeImage(
                $heroSlide,
                $request->input('image_path')
            );

            return response()->json([
                'success' => true,
                'message' => 'Image supprimée avec succès',
                'data' => new HeroSlideResource($heroSlide)
            ], 200);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression d\'image du slide', [
                'hero_slide_id' => $heroSlide->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression de l\'image',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Réordonnancer les images d'un slide
     */
    public function reorderImages(Request $request, HeroSlide $heroSlide)
    {
        try {
            $request->validate([
                'image_orders' => 'required|array',
                'image_orders.*' => 'integer|min:0',
            ]);

            $heroSlide = $this->heroSlideService->reorderImages(
                $heroSlide,
                $request->input('image_orders')
            );

            return response()->json([
                'success' => true,
                'message' => 'Images réordonnées avec succès',
                'data' => new HeroSlideResource($heroSlide)
            ], 200);

        } catch (\Exception $e) {
            Log::error('Erreur lors du réordonnancement des images', [
                'hero_slide_id' => $heroSlide->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du réordonnancement des images',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 