<?php

echo "=== Test de réception des données Formation ===\n\n";

// URL de base
$baseUrl = 'http://127.0.0.1:8000';

// Données de test
$testData = [
    'nom' => 'Formation Test Data',
    'description' => 'Description test pour vérifier les données',
    'duree' => '4 mois',
    'tarif' => '180€',
    'slug' => 'formation-test-data'
];

echo "📝 Données à envoyer:\n";
foreach ($testData as $key => $value) {
    echo "  - $key: $value\n";
}

echo "\n🔄 Envoi de la requête PUT...\n";

// Test PUT avec données complètes
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/api/formations/1');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($testData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json',
    'Content-Type: application/x-www-form-urlencoded'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Status: $httpCode\n";
echo "Response: $response\n\n";

// Test avec données partielles
echo "🔄 Test avec données partielles...\n";
$partialData = [
    'nom' => 'Formation Partielle',
    'description' => 'Description partielle'
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/api/formations/1');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($partialData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json',
    'Content-Type: application/x-www-form-urlencoded'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Status: $httpCode\n";
echo "Response: $response\n\n";

// Vérifier les logs
echo "📋 Instructions pour vérifier les logs:\n";
echo "1. Vérifiez le fichier storage/logs/laravel.log\n";
echo "2. Cherchez les entrées avec 'Données reçues dans la requête'\n";
echo "3. Cherchez les entrées avec 'Données filtrées à envoyer au service'\n";
echo "4. Cherchez les entrées avec 'Formation mise à jour avec succès'\n\n";

echo "✅ Test terminé!\n";
echo "Si les données sont toujours vides, vérifiez:\n";
echo "1. Les logs Laravel pour voir les données reçues\n";
echo "2. La configuration de la base de données\n";
echo "3. Les permissions du dossier storage\n"; 