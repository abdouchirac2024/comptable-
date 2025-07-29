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

            // Gestion des images multiples
            $data['images'] = $this->processMultipleImages($data);

            $heroSlide = $this->heroSlideRepository->create($data);
            
            Log::info('HeroSlide créé avec succès', [
                'hero_slide_id' => $heroSlide->id,
                'title' => $heroSlide->title,
                'hero_section_id' => $heroSlide->hero_section_id,
                'images_count' => count($heroSlide->images ?? [])
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
                    'images' => $heroSlide->images,
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

            // Gestion des images multiples
            if (isset($data['images']) || isset($data['images_upload'])) {
                $data['images'] = $this->processMultipleImages($data, $heroSlide);
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
                'images_count' => count($heroSlide->images ?? []),
                'updated_values' => [
                    'title' => $heroSlide->title,
                    'subtitle' => $heroSlide->subtitle,
                    'description' => $heroSlide->description,
                    'gradient' => $heroSlide->gradient,
                    'background_image' => $heroSlide->background_image,
                    'images' => $heroSlide->images,
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
            // Supprimer l'image de fond
            $this->deleteOldImage($heroSlide->background_image);
            
            // Supprimer toutes les images multiples
            if ($heroSlide->images) {
                foreach ($heroSlide->images as $image) {
                    $imagePath = is_string($image) ? $image : $image['path'];
                    $this->deleteOldImage($imagePath);
                }
            }
            
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
     * Traiter les images multiples
     */
    private function processMultipleImages(array $data, ?HeroSlide $heroSlide = null): array
    {
        $images = $heroSlide ? ($heroSlide->images ?? []) : [];

        // Gestion des nouvelles images uploadées
        if (isset($data['images_upload']) && is_array($data['images_upload'])) {
            foreach ($data['images_upload'] as $uploadedFile) {
                if ($uploadedFile instanceof \Illuminate\Http\UploadedFile) {
                    $imagePath = $this->storeImage($uploadedFile);
                    $images[] = [
                        'path' => $imagePath,
                        'alt_text' => null,
                        'caption' => null,
                        'display_order' => count($images),
                    ];
                }
            }
        }

        // Gestion des images existantes (si mise à jour)
        if (isset($data['images']) && is_array($data['images'])) {
            $images = array_merge($images, $data['images']);
        }

        // Supprimer les images marquées pour suppression
        if (isset($data['images_to_delete']) && is_array($data['images_to_delete'])) {
            foreach ($data['images_to_delete'] as $imagePath) {
                $this->deleteOldImage($imagePath);
                $images = array_filter($images, function($image) use ($imagePath) {
                    $currentPath = is_string($image) ? $image : $image['path'];
                    return $currentPath !== $imagePath;
                });
            }
        }

        return array_values($images);
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

    /**
     * Ajouter une image à un slide
     */
    public function addImage(HeroSlide $heroSlide, \Illuminate\Http\UploadedFile $file, $altText = null, $caption = null): HeroSlide
    {
        $imagePath = $this->storeImage($file);
        $heroSlide->addImage($imagePath, $altText, $caption);
        
        Log::info('Image ajoutée au slide', [
            'hero_slide_id' => $heroSlide->id,
            'image_path' => $imagePath
        ]);

        return $heroSlide;
    }

    /**
     * Supprimer une image d'un slide
     */
    public function removeImage(HeroSlide $heroSlide, string $imagePath): HeroSlide
    {
        $this->deleteOldImage($imagePath);
        $heroSlide->removeImage($imagePath);
        
        Log::info('Image supprimée du slide', [
            'hero_slide_id' => $heroSlide->id,
            'image_path' => $imagePath
        ]);

        return $heroSlide;
    }

    /**
     * Réordonnancer les images d'un slide
     */
    public function reorderImages(HeroSlide $heroSlide, array $imageOrders): HeroSlide
    {
        $heroSlide->reorderImages($imageOrders);
        
        Log::info('Images réordonnées', [
            'hero_slide_id' => $heroSlide->id,
            'image_orders' => $imageOrders
        ]);

        return $heroSlide;
    }
} 