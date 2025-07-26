<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\Mail;
use App\Mail\ContactAutoReply;
use App\Mail\ContactReply;

// Configuration temporaire pour le test
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Liste des emails à tester
$testEmails = [
    'yohivana794@gmail.com',
    'abdouchirac411@gmail.com',
    'test@example.com',
    'contact@site.com'
];

echo "=== Test d'envoi d'emails automatiques ===\n\n";

foreach ($testEmails as $email) {
    try {
        echo "Test d'envoi à: $email\n";
        
        // Test email de confirmation automatique
        Mail::to($email)->send(new ContactAutoReply('Test User', 'Ceci est un message de test'));
        
        echo "✅ Email de confirmation envoyé avec succès!\n";
        
        // Test email de réponse manuelle
        Mail::to($email)->send(new ContactReply('Test User', 'Ceci est une réponse de test'));
        
        echo "✅ Email de réponse envoyé avec succès!\n";
        
    } catch (Exception $e) {
        echo "❌ Erreur pour $email: " . $e->getMessage() . "\n";
    }
    
    echo "----------------------------------------\n";
}

echo "\n=== Test terminé ===\n";
echo "Vérifiez vos boîtes mail pour confirmer la réception des emails.\n"; 