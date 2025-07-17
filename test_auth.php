<?php

// Test d'authentification pour l'API Laravel
$baseUrl = 'http://127.0.0.1:8000/api';

echo "=== Test d'authentification ===\n\n";

// Test 1: Inscription
echo "1. Test d'inscription...\n";
$registerData = [
    'prenom' => 'Test',
    'nom' => 'User',
    'email' => 'test@example.com',
    'password' => 'password123',
    'password_confirmation' => 'password123'
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/register');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($registerData));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Code HTTP: $httpCode\n";
echo "Réponse: $response\n\n";

if ($httpCode === 201) {
    $data = json_decode($response, true);
    $accessToken = $data['access_token'] ?? null;
    $refreshToken = $data['refresh_token'] ?? null;
    
    echo "✅ Inscription réussie!\n";
    echo "Access Token: " . substr($accessToken, 0, 20) . "...\n";
    echo "Refresh Token: " . substr($refreshToken, 0, 20) . "...\n\n";
    
    // Test 2: Connexion
    echo "2. Test de connexion...\n";
    $loginData = [
        'email' => 'test@example.com',
        'password' => 'password123'
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $baseUrl . '/login');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($loginData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "Code HTTP: $httpCode\n";
    echo "Réponse: $response\n\n";
    
    if ($httpCode === 200) {
        echo "✅ Connexion réussie!\n\n";
        
        // Test 3: Rafraîchissement du token
        echo "3. Test de rafraîchissement du token...\n";
        $refreshData = [
            'refresh_token' => $refreshToken
        ];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $baseUrl . '/refresh');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($refreshData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        echo "Code HTTP: $httpCode\n";
        echo "Réponse: $response\n\n";
        
        if ($httpCode === 200) {
            echo "✅ Rafraîchissement du token réussi!\n\n";
            
            // Test 4: Déconnexion
            echo "4. Test de déconnexion...\n";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $baseUrl . '/logout');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $accessToken
            ]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            echo "Code HTTP: $httpCode\n";
            echo "Réponse: $response\n\n";
            
            if ($httpCode === 200) {
                echo "✅ Déconnexion réussie!\n\n";
                echo "🎉 Tous les tests d'authentification sont passés avec succès!\n";
            } else {
                echo "❌ Échec de la déconnexion\n";
            }
        } else {
            echo "❌ Échec du rafraîchissement du token\n";
        }
    } else {
        echo "❌ Échec de la connexion\n";
    }
} else {
    echo "❌ Échec de l'inscription\n";
}

echo "\n=== Fin des tests ===\n"; 