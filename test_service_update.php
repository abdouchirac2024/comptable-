<?php

// Test pour l'édition d'un service avec image
$url = 'http://127.0.0.1:8000/api/services/2';

// Données de test
$data = [
    'nom' => 'Service mis à jour avec image',
    'description' => 'Description mise à jour du service avec une nouvelle image.',
    'categorie' => 'Consultation',
    'tarif' => '150€',
    'duree' => '2 heures',
    'slug' => 'service-mis-a-jour-avec-image'
];

// Préparer les données pour form-data
$postData = [];
foreach ($data as $key => $value) {
    $postData[] = '--boundary';
    $postData[] = 'Content-Disposition: form-data; name="' . $key . '"';
    $postData[] = '';
    $postData[] = $value;
}

// Ajouter l'image si elle existe
$imagePath = 'test_image.jpg'; // Remplacez par le chemin de votre image de test
if (file_exists($imagePath)) {
    $imageContent = file_get_contents($imagePath);
    $postData[] = '--boundary';
    $postData[] = 'Content-Disposition: form-data; name="image"; filename="' . basename($imagePath) . '"';
    $postData[] = 'Content-Type: image/jpeg';
    $postData[] = '';
    $postData[] = $imageContent;
}

$postData[] = '--boundary--';

$postString = implode("\r\n", $postData);

// Configuration de la requête cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: multipart/form-data; boundary=boundary',
    'Accept: application/json',
    'Content-Length: ' . strlen($postString)
]);

// Exécuter la requête
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

// Afficher les résultats
echo "=== TEST ÉDITION SERVICE AVEC IMAGE ===\n";
echo "URL: $url\n";
echo "Code HTTP: $httpCode\n";

if ($error) {
    echo "Erreur cURL: $error\n";
} else {
    echo "Réponse:\n";
    $responseData = json_decode($response, true);
    echo json_encode($responseData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    echo "\n";
}

echo "\n=== FIN DU TEST ===\n";
?> 