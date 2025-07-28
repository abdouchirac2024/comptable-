<?php

namespace App\Services;

use App\Repositories\Interfaces\HeroSectionRepositoryInterface;
use App\Models\HeroSection;
use Illuminate\Support\Facades\Log;

class HeroSectionService
{
    protected $heroSectionRepository;

    public function __construct(HeroSectionRepositoryInterface $heroSectionRepository)
    {
        $this->heroSectionRepository = $heroSectionRepository;
    }

    public function list(array $filters = [], int $perPage = 15)
    {
        return $this->heroSectionRepository->all($filters, $perPage);
    }

    public function find(int $id): ?HeroSection
    {
        return $this->heroSectionRepository->find($id);
    }

    public function findActive(): ?HeroSection
    {
        return $this->heroSectionRepository->findActive();
    }

    public function create(array $data): HeroSection
    {
        try {
            $heroSection = $this->heroSectionRepository->create($data);
            
            Log::info('HeroSection créée avec succès', [
                'hero_section_id' => $heroSection->id,
                'is_active' => $heroSection->is_active
            ]);

            return $heroSection;
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création de la HeroSection', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            throw $e;
        }
    }

    public function update(HeroSection $heroSection, array $data): HeroSection
    {
        try {
            Log::info('Début de la mise à jour de la HeroSection', [
                'hero_section_id' => $heroSection->id,
                'data_received' => $data,
                'current_values' => [
                    'is_active' => $heroSection->is_active,
                ]
            ]);

            $heroSection = $this->heroSectionRepository->update($heroSection, $data);
            
            Log::info('HeroSection mise à jour avec succès', [
                'hero_section_id' => $heroSection->id,
                'is_active' => $heroSection->is_active,
                'updated_values' => [
                    'is_active' => $heroSection->is_active,
                ]
            ]);

            return $heroSection;
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour de la HeroSection', [
                'hero_section_id' => $heroSection->id,
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            throw $e;
        }
    }

    public function delete(HeroSection $heroSection): void
    {
        try {
            $this->heroSectionRepository->delete($heroSection);
            
            Log::info('HeroSection supprimée avec succès', [
                'hero_section_id' => $heroSection->id
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression de la HeroSection', [
                'hero_section_id' => $heroSection->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Activer une section hero
     */
    public function activate(HeroSection $heroSection): HeroSection
    {
        // Désactiver toutes les autres sections
        HeroSection::where('id', '!=', $heroSection->id)->update(['is_active' => false]);
        
        // Activer la section courante
        return $this->update($heroSection, ['is_active' => true]);
    }

    /**
     * Désactiver une section hero
     */
    public function deactivate(HeroSection $heroSection): HeroSection
    {
        return $this->update($heroSection, ['is_active' => false]);
    }
} 