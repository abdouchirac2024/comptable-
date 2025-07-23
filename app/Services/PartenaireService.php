<?php

namespace App\Services;

use App\Repositories\Interfaces\PartenaireRepositoryInterface;
use App\Models\Partenaire;

class PartenaireService
{
    protected $partenaireRepository;

    public function __construct(PartenaireRepositoryInterface $partenaireRepository)
    {
        $this->partenaireRepository = $partenaireRepository;
    }

    public function list(array $filters = [], int $perPage = 15)
    {
        return $this->partenaireRepository->all($filters, $perPage);
    }

    public function find(int $id): ?Partenaire
    {
        return $this->partenaireRepository->find($id);
    }

    public function create(array $data): Partenaire
    {
        if (isset($data['image']) && is_file($data['image'])) {
            $data['image'] = $data['image']->store('partenaires', 'public');
        }
        return $this->partenaireRepository->create($data);
    }

    public function update(Partenaire $partenaire, array $data): Partenaire
    {
        if (isset($data['image']) && is_file($data['image'])) {
            if ($partenaire->image) {
                \Storage::disk('public')->delete($partenaire->image);
            }
            $data['image'] = $data['image']->store('partenaires', 'public');
        }
        return $this->partenaireRepository->update($partenaire, $data);
    }

    public function delete(Partenaire $partenaire): void
    {
        if ($partenaire->image) {
            \Storage::disk('public')->delete($partenaire->image);
        }
        $this->partenaireRepository->delete($partenaire);
    }
} 