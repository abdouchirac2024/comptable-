<?php

namespace App\Http\Controllers;

use App\Services\HeroSectionService;
use App\Http\Requests\HeroSection\StoreHeroSectionRequest;
use App\Http\Requests\HeroSection\UpdateHeroSectionRequest;
use App\Http\Resources\HeroSectionResource;
use App\Models\HeroSection;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class HeroSectionController extends Controller
{
    protected $heroSectionService;

    public function __construct(HeroSectionService $heroSectionService)
    {
        $this->heroSectionService = $heroSectionService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $filters = [
                'search' => $request->query('search'),
                'is_active' => $request->query('is_active'),
            ];
            $perPage = $request->query('per_page', 15);
            $heroSections = $this->heroSectionService->list($filters, $perPage);
            
            return response()->json([
                'success' => true,
                'message' => 'Sections Hero récupérées avec succès',
                'data' => HeroSectionResource::collection($heroSections)
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des sections Hero', [
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des sections Hero',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $heroSection = $this->heroSectionService->find($id);
            
            if (!$heroSection) {
                return response()->json([
                    'success' => false,
                    'message' => 'Section Hero non trouvée'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Section Hero récupérée avec succès',
                'data' => new HeroSectionResource($heroSection)
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération de la section Hero', [
                'hero_section_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération de la section Hero',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function active(): JsonResponse
    {
        try {
            $heroSection = $this->heroSectionService->findActive();
            
            if (!$heroSection) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucune section Hero active trouvée'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Section Hero active récupérée avec succès',
                'data' => new HeroSectionResource($heroSection)
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération de la section Hero active', [
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération de la section Hero active',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(StoreHeroSectionRequest $request): JsonResponse
    {
        try {
            $heroSection = $this->heroSectionService->create($request->validated());
            
            return response()->json([
                'success' => true,
                'message' => 'Section Hero créée avec succès',
                'data' => new HeroSectionResource($heroSection)
            ], 201);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création de la section Hero', [
                'error' => $e->getMessage(),
                'data' => $request->validated()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de la section Hero',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(UpdateHeroSectionRequest $request, $id): JsonResponse
    {
        try {
            $heroSection = $this->heroSectionService->find($id);
            
            if (!$heroSection) {
                return response()->json([
                    'success' => false,
                    'message' => 'Section Hero non trouvée'
                ], 404);
            }
            
            $heroSection = $this->heroSectionService->update($heroSection, $request->validated());
            
            return response()->json([
                'success' => true,
                'message' => 'Section Hero mise à jour avec succès',
                'data' => new HeroSectionResource($heroSection)
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour de la section Hero', [
                'hero_section_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour de la section Hero',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $heroSection = $this->heroSectionService->find($id);
            
            if (!$heroSection) {
                return response()->json([
                    'success' => false,
                    'message' => 'Section Hero non trouvée'
                ], 404);
            }
            
            $this->heroSectionService->delete($heroSection);
            
            return response()->json([
                'success' => true,
                'message' => 'Section Hero supprimée avec succès'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression de la section Hero', [
                'hero_section_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression de la section Hero',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function activate($id): JsonResponse
    {
        try {
            $heroSection = $this->heroSectionService->find($id);
            
            if (!$heroSection) {
                return response()->json([
                    'success' => false,
                    'message' => 'Section Hero non trouvée'
                ], 404);
            }
            
            $heroSection = $this->heroSectionService->activate($heroSection);
            
            return response()->json([
                'success' => true,
                'message' => 'Section Hero activée avec succès',
                'data' => new HeroSectionResource($heroSection)
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'activation de la section Hero', [
                'hero_section_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'activation de la section Hero',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function deactivate($id): JsonResponse
    {
        try {
            $heroSection = $this->heroSectionService->find($id);
            
            if (!$heroSection) {
                return response()->json([
                    'success' => false,
                    'message' => 'Section Hero non trouvée'
                ], 404);
            }
            
            $heroSection = $this->heroSectionService->deactivate($heroSection);
            
            return response()->json([
                'success' => true,
                'message' => 'Section Hero désactivée avec succès',
                'data' => new HeroSectionResource($heroSection)
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la désactivation de la section Hero', [
                'hero_section_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la désactivation de la section Hero',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 