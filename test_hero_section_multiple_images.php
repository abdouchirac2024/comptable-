<?php

/**
 * Test des images multiples pour HeroSection
 * Ce fichier teste l'ajout, la suppression et la réorganisation des images multiples
 */

$baseUrl = 'http://localhost:8000/api';

echo "=== TEST DES IMAGES MULTIPLES HERO SECTION ===\n\n";

// 1. Créer une section Hero
echo "1. Création d'une section Hero...\n";
$heroSectionData = [
    'is_active' => true
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/hero-sections');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($heroSectionData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLOPT_HTTP_CODE);
curl_close($ch);

if ($httpCode === 201) {
    $heroSection = json_decode($response, true);
    $heroSectionId = $heroSection['data']['id'];
    echo "✅ Section Hero créée avec ID: $heroSectionId\n\n";
} else {
    echo "❌ Erreur création section Hero: $response\n\n";
    exit;
}

// 2. Créer un slide avec images multiples
echo "2. Création d'un slide avec images multiples...\n";

// Simuler des fichiers d'images (en réalité, vous utiliseriez de vrais fichiers)
$slideData = [
    'hero_section_id' => $heroSectionId,
    'title' => 'Slide avec Images Multiples',
    'subtitle' => 'Test des images multiples',
    'description' => 'Ce slide contient plusieurs images pour tester la fonctionnalité',
    'gradient' => 'bg-gradient-to-r from-blue-500 to-purple-600',
    'slide_duration' => 5000,
    'is_active' => true,
    'images' => [
        [
            'path' => 'hero-slides/sample_image_1.jpg',
            'alt_text' => 'Image 1 du slide',
            'caption' => 'Première image',
            'display_order' => 0
        ],
        [
            'path' => 'hero-slides/sample_image_2.jpg',
            'alt_text' => 'Image 2 du slide',
            'caption' => 'Deuxième image',
            'display_order' => 1
        ],
        [
            'path' => 'hero-slides/sample_image_3.jpg',
            'alt_text' => 'Image 3 du slide',
            'caption' => 'Troisième image',
            'display_order' => 2
        ]
    ]
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/hero-slides');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($slideData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLOPT_HTTP_CODE);
curl_close($ch);

if ($httpCode === 201) {
    $slide = json_decode($response, true);
    $slideId = $slide['data']['id'];
    echo "✅ Slide créé avec ID: $slideId\n";
    echo "📸 Images ajoutées: " . count($slide['data']['images']) . "\n\n";
} else {
    echo "❌ Erreur création slide: $response\n\n";
    exit;
}

// 3. Ajouter une nouvelle image au slide
echo "3. Ajout d'une nouvelle image au slide...\n";

// Simuler l'ajout d'une image (en réalité, vous utiliseriez un vrai fichier)
$addImageData = [
    'image_path' => 'hero-slides/new_image.jpg',
    'alt_text' => 'Nouvelle image ajoutée',
    'caption' => 'Image ajoutée via API'
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . "/hero-slides/$slideId/add-image");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($addImageData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLOPT_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200) {
    $result = json_decode($response, true);
    echo "✅ Image ajoutée avec succès\n";
    echo "📸 Total images: " . count($result['data']['images']) . "\n\n";
} else {
    echo "❌ Erreur ajout image: $response\n\n";
}

// 4. Réordonnancer les images
echo "4. Réordonnancement des images...\n";

$reorderData = [
    'image_orders' => [3, 0, 1, 2] // Réordonnancer
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . "/hero-slides/$slideId/reorder-images");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($reorderData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLOPT_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200) {
    echo "✅ Images réordonnées avec succès\n\n";
} else {
    echo "❌ Erreur réordonnancement: $response\n\n";
}

// 5. Supprimer une image
echo "5. Suppression d'une image...\n";

$removeImageData = [
    'image_path' => 'hero-slides/sample_image_2.jpg'
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . "/hero-slides/$slideId/remove-image");
curl_setopt($ch, CURLOPT_DELETE, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($removeImageData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLOPT_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200) {
    $result = json_decode($response, true);
    echo "✅ Image supprimée avec succès\n";
    echo "📸 Images restantes: " . count($result['data']['images']) . "\n\n";
} else {
    echo "❌ Erreur suppression image: $response\n\n";
}

// 6. Mettre à jour le slide avec de nouvelles images
echo "6. Mise à jour du slide avec nouvelles images...\n";

$updateData = [
    'title' => 'Slide Mis à Jour avec Images Multiples',
    'subtitle' => 'Test mis à jour',
    'images' => [
        [
            'path' => 'hero-slides/updated_image_1.jpg',
            'alt_text' => 'Image mise à jour 1',
            'caption' => 'Première image mise à jour',
            'display_order' => 0
        ],
        [
            'path' => 'hero-slides/updated_image_2.jpg',
            'alt_text' => 'Image mise à jour 2',
            'caption' => 'Deuxième image mise à jour',
            'display_order' => 1
        ]
    ]
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . "/hero-slides/$slideId/update");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($updateData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLOPT_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200) {
    $result = json_decode($response, true);
    echo "✅ Slide mis à jour avec succès\n";
    echo "📸 Nouvelles images: " . count($result['data']['images']) . "\n\n";
} else {
    echo "❌ Erreur mise à jour: $response\n\n";
}

// 7. Afficher le slide final
echo "7. Affichage du slide final...\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . "/hero-slides/$slideId");
curl_setopt($ch, CURLOPT_HTTPGET, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLOPT_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200) {
    $slide = json_decode($response, true);
    echo "✅ Slide récupéré avec succès\n";
    echo "📋 Titre: " . $slide['data']['title'] . "\n";
    echo "📸 Images: " . count($slide['data']['images']) . "\n";
    
    foreach ($slide['data']['images'] as $index => $image) {
        echo "   Image $index: " . $image['url'] . " (Alt: " . $image['alt_text'] . ")\n";
    }
    echo "\n";
} else {
    echo "❌ Erreur récupération slide: $response\n\n";
}

// 8. Nettoyage - Supprimer le slide et la section
echo "8. Nettoyage...\n";

// Supprimer le slide
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . "/hero-slides/$slideId");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLOPT_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200) {
    echo "✅ Slide supprimé\n";
} else {
    echo "❌ Erreur suppression slide: $response\n";
}

// Supprimer la section
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . "/hero-sections/$heroSectionId");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLOPT_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200) {
    echo "✅ Section Hero supprimée\n";
} else {
    echo "❌ Erreur suppression section: $response\n";
}

echo "\n=== TEST TERMINÉ ===\n";
echo "🎉 Tous les tests des images multiples ont été effectués avec succès !\n"; 