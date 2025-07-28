<?php

namespace App\Repositories\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\HeroSection;

interface HeroSectionRepositoryInterface
{
    public function all(array $filters = [], int $perPage = 15): LengthAwarePaginator;
    public function find(int $id): ?HeroSection;
    public function findActive(): ?HeroSection;
    public function create(array $data): HeroSection;
    public function update(HeroSection $heroSection, array $data): HeroSection;
    public function delete(HeroSection $heroSection): void;
} 