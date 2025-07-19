<?php

namespace Database\Seeders;

use App\Models\Categorie;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorieSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'nom' => 'Savons artisanaux',
                'description' => 'Savons faits main avec des ingrédients naturels',
                'slug' => 'savons-artisanaux'
            ],
            [
                'nom' => 'Bougies parfumées',
                'description' => 'Bougies artisanales aux parfums variés',
                'slug' => 'bougies-parfumees'
            ],
            [
                'nom' => 'Maquillage naturels',
                'description' => 'Produits de maquillage à base d’ingrédients naturels',
                'slug' => 'maquillage-naturels'
            ]
        ];

        foreach ($categories as $category) {
            Categorie::create($category);
        }
    }
}
