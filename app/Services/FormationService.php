<?php

namespace App\Services;

use App\Repositories\Interfaces\FormationRepositoryInterface;
use App\Models\Formation;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FormationService
{
    protected $formationRepository;

    public function __construct(FormationRepositoryInterface $formationRepository)
    {
        $this->formationRepository = $formationRepository;
    }

    public function list(array $filters = [], int $perPage = 15)
    {
        return $this->formationRepository->all($filters, $perPage);
    }

    public function find(int $id): ?Formation
    {
        return $this->formationRepository->find($id);
    }

    public function create(array $data): Formation
    {
        try {
            // Gestion de l'image
            if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
                $data['image'] = $this->storeImage($data['image']);
            }

            // Générer un slug si non fourni
            if (empty($data['slug']) && !empty($data['nom'])) {
                $data['slug'] = $this->generateSlug($data['nom']);
            }

            $formation = $this->formationRepository->create($data);
            
            Log::info('Formation créée avec succès', [
                'formation_id' => $formation->id,
                'nom' => $formation->nom
            ]);

            return $formation;
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création de la formation', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            throw $e;
        }
    }

    public function update(Formation $formation, array $data): Formation
    {
        try {
            Log::info('Début de la mise à jour de la formation', [
                'formation_id' => $formation->id,
                'data_received' => $data,
                'current_values' => [
                    'nom' => $formation->nom,
                    'description' => $formation->description,
                    'duree' => $formation->duree,
                    'tarif' => $formation->tarif,
                    'slug' => $formation->slug,
                ]
            ]);
            
            // Gestion de l'image
            if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
                // Supprimer l'ancienne image
                $this->deleteOldImage($formation->image);
                $data['image'] = $this->storeImage($data['image']);
            }

            // Générer un slug si non fourni mais nom fourni
            if (empty($data['slug']) && !empty($data['nom'])) {
                $data['slug'] = $this->generateSlug($data['nom'], $formation->id);
            }

            // Filtrer les données vides une fois de plus pour sécurité
            $data = array_filter($data, function($value) {
                return $value !== null && $value !== '';
            });

            Log::info('Données finales pour mise à jour', [
                'formation_id' => $formation->id,
                'final_data' => $data
            ]);

            $formation = $this->formationRepository->update($formation, $data);
            
            Log::info('Formation mise à jour avec succès', [
                'formation_id' => $formation->id,
                'nom' => $formation->nom,
                'updated_values' => [
                    'nom' => $formation->nom,
                    'description' => $formation->description,
                    'duree' => $formation->duree,
                    'tarif' => $formation->tarif,
                    'slug' => $formation->slug,
                ]
            ]);

            return $formation;
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour de la formation', [
                'formation_id' => $formation->id,
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            throw $e;
        }
    }

    public function delete(Formation $formation): void
    {
        try {
            // Supprimer l'image associée
            $this->deleteOldImage($formation->image);
            
            $this->formationRepository->delete($formation);
            
            Log::info('Formation supprimée avec succès', [
                'formation_id' => $formation->id,
                'nom' => $formation->nom
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression de la formation', [
                'formation_id' => $formation->id,
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
        return $file->storeAs('formations', $filename, 'public');
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
     * Générer un slug unique
     */
    private function generateSlug(string $nom, ?int $excludeId = null): string
    {
        $slug = Str::slug($nom);
        $originalSlug = $slug;
        $counter = 1;

        while ($this->slugExists($slug, $excludeId)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Vérifier si un slug existe
     */
    private function slugExists(string $slug, ?int $excludeId = null): bool
    {
        $query = Formation::where('slug', $slug);
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }
} 