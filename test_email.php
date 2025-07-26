<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\Mail;
use App\Mail\ContactAutoReply;

// Configuration temporaire pour le test
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    // Test d'envoi d'email
    Mail::to('yohivana794@gmail.com')->send(new ContactAutoReply('Test User', 'Test message'));
    echo "Email envoyé avec succès!\n";
} catch (Exception $e) {
    echo "Erreur lors de l'envoi d'email: " . $e->getMessage() . "\n";
    echo "Détails: " . $e->getTraceAsString() . "\n";
} 