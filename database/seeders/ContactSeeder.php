<?php

namespace Database\Seeders;

use App\Models\Contact;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    public function run(): void
    {
        Contact::create([
            'nom' => 'Jean Dupont',
            'email' => 'jean.dupont@example.com',
            'sujet' => 'Demande d\'informations',
            'message' => 'Bonjour, je souhaite avoir des informations sur vos produits.'
        ]);
        Contact::create([
            'nom' => 'Alice Martin',
            'email' => 'alice.martin@example.com',
            'sujet' => 'Remerciements',
            'message' => 'Merci pour votre service rapide !'
        ]);
        Contact::create([
            'nom' => 'John Smith',
            'email' => 'john.smith@example.com',
            'sujet' => 'Catalogue',
            'message' => 'Can you send me your latest catalog?'
        ]);
    }
} 