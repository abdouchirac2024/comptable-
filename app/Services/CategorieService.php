<?php

namespace App\Services;

use App\Models\Categorie;
use App\Repositories\Interfaces\CategorieRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CategorieService
{
    public function __construct(
        protected CategorieRepositoryInterface $categorieRepository
    ) {
    }

    public function getAllCategories(): Collection
    {
        return $this->categorieRepository->all();
    }

    public function getPaginatedCategories(int $perPage = 15): LengthAwarePaginator
    {
        return $this->categorieRepository->paginate($perPage);
    }

    public function getCategoryById(int $id): ?Categorie
    {
        return $this->categorieRepository->findById($id);
    }

    public function getCategoryBySlug(string $slug): ?Categorie
    {
        return $this->categorieRepository->findBySlug($slug);
    }

    public function createCategory(array $data): Categorie
    {
        try {
            DB::beginTransaction();
            
            $categorie = $this->categorieRepository->create($data);
            
            DB::commit();
            
            Log::info('Catégorie créée avec succès', ['id' => $categorie->id, 'nom' => $categorie->nom]);
            
            return $categorie;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la création de la catégorie', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            throw $e;
        }
    }

    public function updateCategory(Categorie $categorie, array $data): Categorie
    {
        try {
            DB::beginTransaction();
            
            $updatedCategorie = $this->categorieRepository->update($categorie, $data);
            
            DB::commit();
            
            Log::info('Catégorie mise à jour avec succès', [
                'id' => $updatedCategorie->id, 
                'nom' => $updatedCategorie->nom
            ]);
            
            return $updatedCategorie;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la mise à jour de la catégorie', [
                'error' => $e->getMessage(),
                'categorie_id' => $categorie->id,
                'data' => $data
            ]);
            throw $e;
        }
    }

    public function deleteCategory(Categorie $categorie): bool
    {
        try {
            DB::beginTransaction();
            
            // Vérifier si la catégorie a des produits associés
            if ($categorie->produits()->count() > 0) {
                throw new \Exception('Impossible de supprimer une catégorie qui contient des produits');
            }
            
            $deleted = $this->categorieRepository->delete($categorie);
            
            DB::commit();
            
            if ($deleted) {
                Log::info('Catégorie supprimée avec succès', [
                    'id' => $categorie->id, 
                    'nom' => $categorie->nom
                ]);
            }
            
            return $deleted;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la suppression de la catégorie', [
                'error' => $e->getMessage(),
                'categorie_id' => $categorie->id
            ]);
            throw $e;
        }
    }

    public function searchCategories(string $term): Collection
    {
        return $this->categorieRepository->search($term);
    }
} 