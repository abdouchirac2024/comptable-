<?php

echo "=== Test Final Formation API ===\n\n";

// URL de base
$baseUrl = 'http://127.0.0.1:8000';

// Test 1: PUT avec form-data
echo "1️⃣ Test PUT avec form-data\n";
$testData = [
    'nom' => 'Formation Test PUT',
    'description' => 'Description test PUT',
    'duree' => '5 mois',
    'tarif' => '250€',
    'slug' => 'formation-test-put'
];

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

// Test 2: POST avec form-data (route alternative)
echo "2️⃣ Test POST avec form-data (route alternative)\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/api/formations/1/update');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
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

// Test 3: Vérifier la formation mise à jour
echo "3️⃣ Vérification de la formation mise à jour\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/api/formations/1');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Status: $httpCode\n";
echo "Response: $response\n\n";

// Instructions Postman
echo "📋 Instructions Postman:\n";
echo "1. Méthode PUT: http://127.0.0.1:8000/api/formations/1\n";
echo "2. Méthode POST (alternative): http://127.0.0.1:8000/api/formations/1/update\n";
echo "3. Body: form-data\n";
echo "4. Champs: nom, description, duree, tarif, slug, image\n\n";

echo "✅ Test terminé!\n";
echo "Vérifiez les logs dans storage/logs/laravel.log pour voir les détails\n"; 