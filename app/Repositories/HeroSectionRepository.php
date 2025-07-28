<?php

namespace App\Repositories;

use App\Models\HeroSection;
use App\Repositories\Interfaces\HeroSectionRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class HeroSectionRepository implements HeroSectionRepositoryInterface
{
    public function all(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = HeroSection::with('slides');
        
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->whereHas('slides', function($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                  ->orWhere('subtitle', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%");
            });
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        return $query->orderByDesc('created_at')->paginate($perPage);
    }

    public function find(int $id): ?HeroSection
    {
        return HeroSection::with('slides')->find($id);
    }

    public function findActive(): ?HeroSection
    {
        return HeroSection::with('activeSlides')->active()->first();
    }

    public function create(array $data): HeroSection
    {
        return HeroSection::create($data);
    }

    public function update(HeroSection $heroSection, array $data): HeroSection
    {
        $heroSection->update($data);
        return $heroSection->fresh(['slides']);
    }

    public function delete(HeroSection $heroSection): void
    {
        $heroSection->delete();
    }
} 