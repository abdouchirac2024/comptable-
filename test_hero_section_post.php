<?php

echo "=== Test HeroSection avec routes POST (comme formations) ===\n\n";

// URL de base
$baseUrl = 'http://127.0.0.1:8000';

// Test 1: Créer une section Hero
echo "1️⃣ Test création d'une section Hero\n";
$heroSectionData = [
    'is_active' => true
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/api/hero-sections');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($heroSectionData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json',
    'Content-Type: application/x-www-form-urlencoded'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Status: $httpCode\n";
echo "Response: $response\n\n";

// Extraire l'ID de la section créée
$responseData = json_decode($response, true);
$heroSectionId = $responseData['data']['id'] ?? 1;

// Test 2: Créer un slide Hero
echo "2️⃣ Test création d'un slide Hero\n";
$heroSlideData = [
    'hero_section_id' => $heroSectionId,
    'slide_order' => 1,
    'title' => 'Bienvenue sur notre site',
    'subtitle' => 'Découvrez nos services',
    'description' => 'Une description détaillée de nos services et de notre expertise.',
    'gradient' => 'bg-gradient-to-r from-blue-500 to-purple-600',
    'slide_duration' => 5000,
    'is_active' => true
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/api/hero-slides');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($heroSlideData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json',
    'Content-Type: application/x-www-form-urlencoded'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Status: $httpCode\n";
echo "Response: $response\n\n";

// Test 3: Mettre à jour un slide avec POST (route alternative)
echo "3️⃣ Test POST avec form-data (route alternative)\n";
$updateData = [
    'title' => 'Titre mis à jour via POST',
    'subtitle' => 'Sous-titre mis à jour',
    'description' => 'Description mise à jour via méthode POST',
    'gradient' => 'bg-gradient-to-r from-red-500 to-yellow-600',
    'slide_duration' => 6000,
    'is_active' => true
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/api/hero-slides/1/update');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($updateData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json',
    'Content-Type: application/x-www-form-urlencoded'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Status: $httpCode\n";
echo "Response: $response\n\n";

// Test 4: Mettre à jour une section Hero avec POST
echo "4️⃣ Test mise à jour section Hero avec POST\n";
$sectionUpdateData = [
    'is_active' => false
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/api/hero-sections/1/update');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($sectionUpdateData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json',
    'Content-Type: application/x-www-form-urlencoded'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Status: $httpCode\n";
echo "Response: $response\n\n";

// Test 5: Vérifier la section mise à jour
echo "5️⃣ Vérification de la section mise à jour\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/api/hero-sections/1');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Status: $httpCode\n";
echo "Response: " . substr($response, 0, 1000) . "...\n\n";

// Test 6: Réordonnancer les slides avec POST
echo "6️⃣ Test réordonnancement avec POST\n";
$reorderData = [
    'slide_orders' => [
        1 => 2,
        2 => 1
    ]
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/api/hero-slides/section/' . $heroSectionId . '/reorder');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($reorderData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json',
    'Content-Type: application/x-www-form-urlencoded'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Status: $httpCode\n";
echo "Response: $response\n\n";

// Instructions Postman
echo "📋 Instructions Postman:\n";
echo "1. Méthode POST: http://127.0.0.1:8000/api/hero-slides/1/update\n";
echo "2. Méthode POST: http://127.0.0.1:8000/api/hero-sections/1/update\n";
echo "3. Body: form-data\n";
echo "4. Champs pour slides: title, subtitle, description, gradient, slide_duration, is_active, background_image\n";
echo "5. Champs pour sections: is_active\n\n";

echo "✅ Test terminé!\n";
echo "Vérifiez les logs dans storage/logs/laravel.log pour voir les détails\n"; 