<?php

echo "=== Test Système HeroSection ===\n\n";

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
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($heroSectionData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json',
    'Content-Type: application/json'
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
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($heroSlideData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json',
    'Content-Type: application/json'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Status: $httpCode\n";
echo "Response: $response\n\n";

// Test 3: Créer un deuxième slide
echo "3️⃣ Test création d'un deuxième slide\n";
$heroSlideData2 = [
    'hero_section_id' => $heroSectionId,
    'slide_order' => 2,
    'title' => 'Nos formations',
    'subtitle' => 'Apprenez avec nous',
    'description' => 'Des formations de qualité pour développer vos compétences.',
    'gradient' => 'bg-gradient-to-r from-green-500 to-blue-600',
    'slide_duration' => 4000,
    'is_active' => true
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/api/hero-slides');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($heroSlideData2));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json',
    'Content-Type: application/json'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Status: $httpCode\n";
echo "Response: $response\n\n";

// Test 4: Récupérer la section Hero avec ses slides
echo "4️⃣ Test récupération de la section Hero avec slides\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/api/hero-sections/' . $heroSectionId);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Status: $httpCode\n";
echo "Response: " . substr($response, 0, 1000) . "...\n\n";

// Test 5: Récupérer la section Hero active
echo "5️⃣ Test récupération de la section Hero active\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/api/hero-sections/active');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Status: $httpCode\n";
echo "Response: " . substr($response, 0, 1000) . "...\n\n";

// Test 6: Récupérer tous les slides d'une section
echo "6️⃣ Test récupération des slides d'une section\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/api/hero-slides/section/' . $heroSectionId);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Status: $httpCode\n";
echo "Response: " . substr($response, 0, 1000) . "...\n\n";

// Test 7: Mettre à jour un slide
echo "7️⃣ Test mise à jour d'un slide\n";
$updateData = [
    'title' => 'Titre mis à jour',
    'subtitle' => 'Sous-titre mis à jour',
    'slide_duration' => 6000
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/api/hero-slides/1');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($updateData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json',
    'Content-Type: application/json'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Status: $httpCode\n";
echo "Response: $response\n\n";

// Test 8: Réordonnancer les slides
echo "8️⃣ Test réordonnancement des slides\n";
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
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($reorderData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json',
    'Content-Type: application/json'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Status: $httpCode\n";
echo "Response: $response\n\n";

// Instructions Postman
echo "📋 Instructions Postman:\n";
echo "1. Créer une section Hero: POST $baseUrl/api/hero-sections\n";
echo "2. Créer un slide: POST $baseUrl/api/hero-slides\n";
echo "3. Récupérer la section active: GET $baseUrl/api/hero-sections/active\n";
echo "4. Récupérer les slides d'une section: GET $baseUrl/api/hero-slides/section/{id}\n";
echo "5. Mettre à jour un slide: PUT $baseUrl/api/hero-slides/{id}\n";
echo "6. Réordonnancer: POST $baseUrl/api/hero-slides/section/{id}/reorder\n";
echo "7. Activer/Désactiver: POST $baseUrl/api/hero-slides/{id}/activate\n\n";

echo "✅ Test terminé!\n";
echo "Vérifiez les logs dans storage/logs/laravel.log pour voir les détails\n"; 