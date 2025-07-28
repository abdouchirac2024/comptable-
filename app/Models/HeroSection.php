<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeroSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relation avec les slides
     */
    public function slides()
    {
        return $this->hasMany(HeroSlide::class)->orderBy('slide_order');
    }

    /**
     * Relation avec les slides actifs seulement
     */
    public function activeSlides()
    {
        return $this->hasMany(HeroSlide::class)
            ->where('is_active', true)
            ->orderBy('slide_order');
    }

    /**
     * Scope pour les sections actives
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
} 