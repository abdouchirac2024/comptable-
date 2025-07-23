<?php

namespace App\Services;

use App\Repositories\Interfaces\MissionRepositoryInterface;
use App\Models\Mission;

class MissionService
{
    protected $missionRepository;

    public function __construct(MissionRepositoryInterface $missionRepository)
    {
        $this->missionRepository = $missionRepository;
    }

    public function list(array $filters = [], int $perPage = 15)
    {
        return $this->missionRepository->all($filters, $perPage);
    }

    public function find(int $id): ?Mission
    {
        return $this->missionRepository->find($id);
    }

    public function create(array $data): Mission
    {
        return $this->missionRepository->create($data);
    }

    public function update(Mission $mission, array $data): Mission
    {
        return $this->missionRepository->update($mission, $data);
    }

    public function delete(Mission $mission): void
    {
        $this->missionRepository->delete($mission);
    }
} 