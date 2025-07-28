<?php

echo "=== Test API Formations avec php artisan serve ===\n\n";

// URL de base
$baseUrl = 'http://127.0.0.1:8000';

// 1. Test GET - Récupérer toutes les formations
echo "1️⃣ Test GET /api/formations\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/api/formations');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json',
    'Content-Type: application/json'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Status: $httpCode\n";
echo "Response: " . substr($response, 0, 500) . "...\n\n";

// 2. Test PUT - Mettre à jour une formation
echo "2️⃣ Test PUT /api/formations/1\n";

// Créer un fichier temporaire pour l'image
$tempImage = tempnam(sys_get_temp_dir(), 'test_image');
file_put_contents($tempImage, 'fake image content');

$postData = [
    'nom' => 'Formation Test API',
    'description' => 'Description test via API',
    'duree' => '3 mois',
    'tarif' => '150€',
    'slug' => 'formation-test-api'
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/api/formations/1');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json',
    'Content-Type: application/x-www-form-urlencoded'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Status: $httpCode\n";
echo "Response: " . substr($response, 0, 500) . "...\n\n";

// 3. Test avec image (simulation)
echo "3️⃣ Test PUT avec image (simulation)\n";
echo "Pour tester avec une vraie image, utilisez Postman avec:\n";
echo "- Method: PUT\n";
echo "- URL: $baseUrl/api/formations/1\n";
echo "- Body: form-data\n";
echo "- Fields: nom, description, duree, tarif, slug, image\n\n";

// 4. Instructions Postman
echo "📋 Instructions Postman:\n";
echo "1. Ouvrez Postman\n";
echo "2. Créez une nouvelle requête PUT\n";
echo "3. URL: $baseUrl/api/formations/1\n";
echo "4. Body → form-data:\n";
echo "   - nom: Formation Test Postman\n";
echo "   - description: Description test\n";
echo "   - duree: 6 mois\n";
echo "   - tarif: 200€\n";
echo "   - slug: formation-test-postman\n";
echo "   - image: [sélectionnez un fichier image]\n";
echo "5. Cliquez sur Send\n\n";

// 5. Vérifier si le serveur fonctionne
echo "🔍 Vérification du serveur...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200) {
    echo "✅ Serveur Laravel fonctionne sur $baseUrl\n";
} else {
    echo "❌ Serveur non accessible sur $baseUrl\n";
    echo "Assurez-vous que 'php artisan serve' est en cours d'exécution\n";
}

echo "\n✅ Test terminé!\n"; 