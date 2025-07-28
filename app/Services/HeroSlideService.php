<?php

namespace App\Services;

use App\Repositories\Interfaces\HeroSlideRepositoryInterface;
use App\Models\HeroSlide;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class HeroSlideService
{
    protected $heroSlideRepository;

    public function __construct(HeroSlideRepositoryInterface $heroSlideRepository)
    {
        $this->heroSlideRepository = $heroSlideRepository;
    }

    public function list(array $filters = [], int $perPage = 15)
    {
        return $this->heroSlideRepository->all($filters, $perPage);
    }

    public function findBySection(int $heroSectionId, array $filters = [])
    {
        return $this->heroSlideRepository->findBySection($heroSectionId, $filters);
    }

    public function find(int $id): ?HeroSlide
    {
        return $this->heroSlideRepository->find($id);
    }

    public function create(array $data): HeroSlide
    {
        try {
            // Gestion de l'image de fond
            if (isset($data['background_image']) && $data['background_image'] instanceof \Illuminate\Http\UploadedFile) {
                $data['background_image'] = $this->storeImage($data['background_image']);
            }

            $heroSlide = $this->heroSlideRepository->create($data);
            
            Log::info('HeroSlide créé avec succès', [
                'hero_slide_id' => $heroSlide->id,
                'title' => $heroSlide->title,
                'hero_section_id' => $heroSlide->hero_section_id
            ]);

            return $heroSlide;
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création du HeroSlide', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            throw $e;
        }
    }

    public function update(HeroSlide $heroSlide, array $data): HeroSlide
    {
        try {
            Log::info('Début de la mise à jour du HeroSlide', [
                'hero_slide_id' => $heroSlide->id,
                'data_received' => $data,
                'current_values' => [
                    'title' => $heroSlide->title,
                    'subtitle' => $heroSlide->subtitle,
                    'description' => $heroSlide->description,
                    'gradient' => $heroSlide->gradient,
                    'background_image' => $heroSlide->background_image,
                    'slide_duration' => $heroSlide->slide_duration,
                    'slide_order' => $heroSlide->slide_order,
                    'is_active' => $heroSlide->is_active,
                ]
            ]);
            
            // Gestion de l'image de fond
            if (isset($data['background_image']) && $data['background_image'] instanceof \Illuminate\Http\UploadedFile) {
                // Supprimer l'ancienne image
                $this->deleteOldImage($heroSlide->background_image);
                $data['background_image'] = $this->storeImage($data['background_image']);
            }

            // Filtrer les données vides
            $data = array_filter($data, function($value) {
                return $value !== null && $value !== '';
            });

            Log::info('Données finales pour mise à jour', [
                'hero_slide_id' => $heroSlide->id,
                'final_data' => $data
            ]);

            $heroSlide = $this->heroSlideRepository->update($heroSlide, $data);
            
            Log::info('HeroSlide mis à jour avec succès', [
                'hero_slide_id' => $heroSlide->id,
                'title' => $heroSlide->title,
                'updated_values' => [
                    'title' => $heroSlide->title,
                    'subtitle' => $heroSlide->subtitle,
                    'description' => $heroSlide->description,
                    'gradient' => $heroSlide->gradient,
                    'background_image' => $heroSlide->background_image,
                    'slide_duration' => $heroSlide->slide_duration,
                    'slide_order' => $heroSlide->slide_order,
                    'is_active' => $heroSlide->is_active,
                ]
            ]);

            return $heroSlide;
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour du HeroSlide', [
                'hero_slide_id' => $heroSlide->id,
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            throw $e;
        }
    }

    public function delete(HeroSlide $heroSlide): void
    {
        try {
            // Supprimer l'image associée
            $this->deleteOldImage($heroSlide->background_image);
            
            $this->heroSlideRepository->delete($heroSlide);
            
            Log::info('HeroSlide supprimé avec succès', [
                'hero_slide_id' => $heroSlide->id,
                'title' => $heroSlide->title
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression du HeroSlide', [
                'hero_slide_id' => $heroSlide->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    public function reorderSlides(int $heroSectionId, array $slideOrders): void
    {
        try {
            $this->heroSlideRepository->reorderSlides($heroSectionId, $slideOrders);
            
            Log::info('Slides réordonnés avec succès', [
                'hero_section_id' => $heroSectionId,
                'slide_orders' => $slideOrders
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors du réordonnancement des slides', [
                'hero_section_id' => $heroSectionId,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Stocker une image
     */
    private function storeImage(\Illuminate\Http\UploadedFile $file): string
    {
        $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        return $file->storeAs('hero-slides', $filename, 'public');
    }

    /**
     * Supprimer une ancienne image
     */
    private function deleteOldImage(?string $imagePath): void
    {
        if ($imagePath && Storage::disk('public')->exists($imagePath)) {
            Storage::disk('public')->delete($imagePath);
        }
    }

    /**
     * Activer un slide
     */
    public function activate(HeroSlide $heroSlide): HeroSlide
    {
        return $this->update($heroSlide, ['is_active' => true]);
    }

    /**
     * Désactiver un slide
     */
    public function deactivate(HeroSlide $heroSlide): HeroSlide
    {
        return $this->update($heroSlide, ['is_active' => false]);
    }
} 