<?php

namespace App\Services;

use App\Repositories\Interfaces\ServiceRepositoryInterface;
use App\Models\Service;

class ServiceService
{
    protected $serviceRepository;

    public function __construct(ServiceRepositoryInterface $serviceRepository)
    {
        $this->serviceRepository = $serviceRepository;
    }

    public function list(array $filters = [], int $perPage = 15)
    {
        return $this->serviceRepository->all($filters, $perPage);
    }

    public function find(int $id): ?Service
    {
        return $this->serviceRepository->find($id);
    }

    public function create(array $data): Service
    {
        if (isset($data['image']) && is_file($data['image'])) {
            $data['image'] = $data['image']->store('services', 'public');
        }
        return $this->serviceRepository->create($data);
    }

    public function update(Service $service, array $data): Service
    {
        if (isset($data['image']) && is_file($data['image'])) {
            // Supprimer l'ancienne image si elle existe
            if ($service->image) {
                \Storage::disk('public')->delete($service->image);
            }
            $data['image'] = $data['image']->store('services', 'public');
        }
        return $this->serviceRepository->update($service, $data);
    }

    public function delete(Service $service): void
    {
        $this->serviceRepository->delete($service);
    }
} 