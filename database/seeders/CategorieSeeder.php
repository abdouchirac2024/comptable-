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
                'nom' => 'Électronique',
                'description' => 'Produits électroniques et gadgets',
                'slug' => 'electronique'
            ],
            [
                'nom' => 'Vêtements',
                'description' => 'Vêtements et accessoires de mode',
                'slug' => 'vetements'
            ],
            [
                'nom' => 'Livres',
                'description' => 'Livres et publications',
                'slug' => 'livres'
            ],
            [
                'nom' => 'Sport',
                'description' => 'Équipements et vêtements de sport',
                'slug' => 'sport'
            ],
            [
                'nom' => 'Maison',
                'description' => 'Articles pour la maison et le jardin',
                'slug' => 'maison'
            ],
            [
                'nom' => 'Beauté',
                'description' => 'Produits de beauté et cosmétiques',
                'slug' => 'beaute'
            ],
            [
                'nom' => 'Jouets',
                'description' => 'Jouets et jeux pour enfants',
                'slug' => 'jouets'
            ],
            [
                'nom' => 'Automobile',
                'description' => 'Pièces et accessoires automobiles',
                'slug' => 'automobile'
            ]
        ];

        foreach ($categories as $category) {
            Categorie::create($category);
        }
    }
} 