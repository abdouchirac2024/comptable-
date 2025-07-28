<?php

require_once 'vendor/autoload.php';

use App\Models\Formation;
use App\Services\FormationService;
use App\Repositories\FormationRepository;

// Configuration temporaire pour le test
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Test de mise à jour des formations ===\n\n";

try {
    // Créer une instance du service
    $formationRepository = new FormationRepository();
    $formationService = new FormationService($formationRepository);
    
    // Trouver une formation existante
    $formation = Formation::first();
    
    if (!$formation) {
        echo "❌ Aucune formation trouvée dans la base de données\n";
        exit(1);
    }
    
    echo "✅ Formation trouvée: {$formation->nom} (ID: {$formation->id})\n";
    
    // Données de test pour la mise à jour
    $updateData = [
        'nom' => 'Formation Test Mise à Jour',
        'description' => 'Description mise à jour pour test',
        'duree' => '6 mois',
        'tarif' => '200€',
        'slug' => 'formation-test-mise-a-jour'
    ];
    
    echo "\n📝 Données de mise à jour:\n";
    foreach ($updateData as $key => $value) {
        echo "  - {$key}: {$value}\n";
    }
    
    // Effectuer la mise à jour
    echo "\n🔄 Mise à jour en cours...\n";
    $updatedFormation = $formationService->update($formation, $updateData);
    
    echo "✅ Formation mise à jour avec succès!\n";
    echo "📊 Résultats:\n";
    echo "  - ID: {$updatedFormation->id}\n";
    echo "  - Nom: {$updatedFormation->nom}\n";
    echo "  - Slug: {$updatedFormation->slug}\n";
    echo "  - Description: {$updatedFormation->description}\n";
    echo "  - Durée: {$updatedFormation->duree}\n";
    echo "  - Tarif: {$updatedFormation->tarif}\n";
    echo "  - Image: " . ($updatedFormation->image ?: 'Aucune') . "\n";
    echo "  - Mis à jour le: {$updatedFormation->updated_at}\n";
    
} catch (Exception $e) {
    echo "❌ Erreur lors du test: " . $e->getMessage() . "\n";
    echo "📋 Détails: " . $e->getTraceAsString() . "\n";
}

echo "\n=== Test terminé ===\n"; 