<?php

namespace App\Repositories\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\HeroSlide;

interface HeroSlideRepositoryInterface
{
    public function all(array $filters = [], int $perPage = 15): LengthAwarePaginator;
    public function findBySection(int $heroSectionId, array $filters = []): LengthAwarePaginator;
    public function find(int $id): ?HeroSlide;
    public function create(array $data): HeroSlide;
    public function update(HeroSlide $heroSlide, array $data): HeroSlide;
    public function delete(HeroSlide $heroSlide): void;
    public function reorderSlides(int $heroSectionId, array $slideOrders): void;
} 