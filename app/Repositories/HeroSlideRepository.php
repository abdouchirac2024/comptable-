<?php

namespace App\Repositories;

use App\Models\HeroSlide;
use App\Repositories\Interfaces\HeroSlideRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class HeroSlideRepository implements HeroSlideRepositoryInterface
{
    public function all(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = HeroSlide::with('heroSection');
        
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                  ->orWhere('subtitle', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%");
            });
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        if (isset($filters['hero_section_id'])) {
            $query->where('hero_section_id', $filters['hero_section_id']);
        }

        return $query->orderBy('slide_order')->paginate($perPage);
    }

    public function findBySection(int $heroSectionId, array $filters = []): LengthAwarePaginator
    {
        $query = HeroSlide::where('hero_section_id', $heroSectionId);
        
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                  ->orWhere('subtitle', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%");
            });
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        return $query->orderBy('slide_order')->paginate($filters['per_page'] ?? 15);
    }

    public function find(int $id): ?HeroSlide
    {
        return HeroSlide::with('heroSection')->find($id);
    }

    public function create(array $data): HeroSlide
    {
        // Si slide_order n'est pas fourni, prendre le prochain ordre disponible
        if (!isset($data['slide_order'])) {
            $maxOrder = HeroSlide::where('hero_section_id', $data['hero_section_id'])
                ->max('slide_order');
            $data['slide_order'] = ($maxOrder ?? 0) + 1;
        }

        return HeroSlide::create($data);
    }

    public function update(HeroSlide $heroSlide, array $data): HeroSlide
    {
        $heroSlide->update($data);
        return $heroSlide->fresh(['heroSection']);
    }

    public function delete(HeroSlide $heroSlide): void
    {
        $heroSlide->delete();
    }

    public function reorderSlides(int $heroSectionId, array $slideOrders): void
    {
        DB::transaction(function () use ($heroSectionId, $slideOrders) {
            foreach ($slideOrders as $slideId => $order) {
                HeroSlide::where('id', $slideId)
                    ->where('hero_section_id', $heroSectionId)
                    ->update(['slide_order' => $order]);
            }
        });
    }
} 