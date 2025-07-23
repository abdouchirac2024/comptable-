<?php

namespace App\Services;

use App\Repositories\Interfaces\FormationRepositoryInterface;
use App\Models\Formation;

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
        if (isset($data['image']) && is_file($data['image'])) {
            $data['image'] = $data['image']->store('formations', 'public');
        }
        return $this->formationRepository->create($data);
    }

    public function update(Formation $formation, array $data): Formation
    {
        if (isset($data['image']) && is_file($data['image'])) {
            // Supprimer l'ancienne image si elle existe
            if ($formation->image) {
                \Storage::disk('public')->delete($formation->image);
            }
            $data['image'] = $data['image']->store('formations', 'public');
        }
        return $this->formationRepository->update($formation, $data);
    }

    public function delete(Formation $formation): void
    {
        $this->formationRepository->delete($formation);
    }
} 