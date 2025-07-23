<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Création d'un admin (éviter les doublons)
        User::updateOrCreate(
            ['email' => 'admin@site.com'],
            [
                'prenom' => 'Admin',
                'nom' => 'Principal',
                'password' => Hash::make('admin1234'),
                'adresse_livraison' => 'Adresse admin',
                'adresse_facturation' => 'Adresse admin',
                'role' => 'admin',
                'refresh_token' => Str::random(60),
            ]
        );

        // Création d'un client (éviter les doublons)
        User::updateOrCreate(
            ['email' => 'client@site.com'],
            [
                'prenom' => 'Client',
                'nom' => 'Test',
                'password' => Hash::make('client1234'),
                'adresse_livraison' => 'Adresse client',
                'adresse_facturation' => 'Adresse client',
                'role' => 'client',
                'refresh_token' => Str::random(60),
            ]
        );

        // Seed des catégories
        // $this->call([
        //     CategorieSeeder::class,
        //     ContactSeeder::class,
        // ]);
    }
}
