# Système d'Email Automatique - Documentation

## Vue d'ensemble

Le système d'email automatique a été configuré pour répondre automatiquement à tous les contacts qui utilisent le formulaire de contact, peu importe leur adresse email.

## Fonctionnalités

### 1. Email de Confirmation Automatique
- **Déclencheur** : Création d'un nouveau contact via l'API
- **Template** : `resources/views/emails/contact_auto_reply.blade.php`
- **Contenu** : Confirmation de réception du message
- **Envoi** : Automatique lors de la création du contact

### 2. Email de Réponse Manuelle
- **Déclencheur** : Mise à jour d'un contact avec une réponse
- **Template** : `resources/views/emails/contact_reply.blade.php`
- **Contenu** : Réponse personnalisée de l'équipe
- **Envoi** : Lors de la mise à jour avec le champ `reponse`

## Configuration Email

### Configuration SMTP Gmail
```php
// config/mail.php
'smtp' => [
    'transport' => 'smtp',
    'host' => env('MAIL_HOST', 'smtp.gmail.com'),
    'port' => env('MAIL_PORT', 587),
    'encryption' => env('MAIL_ENCRYPTION', 'tls'),
    'username' => env('MAIL_USERNAME', 'yohmarley237@gmail.com'),
    'password' => env('MAIL_PASSWORD', 'aqivoxnzowsgfvks'),
]
```

### Variables d'environnement requises
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=yohmarley237@gmail.com
MAIL_PASSWORD=aqivoxnzowsgfvks
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=yohmarley237@gmail.com
MAIL_FROM_NAME="Votre Site"
```

## Utilisation

### 1. Création d'un contact (Email automatique envoyé)
```bash
POST /api/contacts
{
    "nom": "John Doe",
    "email": "john@example.com",
    "sujet": "Question générale",
    "message": "Bonjour, j'ai une question..."
}
```

### 2. Réponse manuelle à un contact
```bash
PUT /api/contacts/{id}
{
    "reponse": "Merci pour votre message. Voici notre réponse..."
}
```

### 3. Test d'envoi d'email
```bash
POST /api/contacts/test-email
{
    "email": "test@example.com",
    "nom": "Test User",
    "type": "auto" // ou "reply"
}
```

## Scripts de Test

### Test en ligne de commande
```bash
php test_all_emails.php
```

### Test individuel
```bash
php test_email.php
```

## Gestion des Erreurs

### Logs
Les erreurs d'envoi d'email sont loggées dans :
- `storage/logs/laravel.log`

### Types d'erreurs courantes
1. **Configuration SMTP incorrecte**
   - Vérifier les identifiants Gmail
   - Activer l'authentification à 2 facteurs
   - Générer un mot de passe d'application

2. **Problèmes de réseau**
   - Vérifier la connexion internet
   - Vérifier les ports SMTP (587, 465)

3. **Limites Gmail**
   - Limite quotidienne d'envoi
   - Restrictions de sécurité

## Templates d'Email

### Email de Confirmation Automatique
- Design professionnel avec CSS inline
- En-tête avec titre
- Contenu personnalisé avec le nom
- Affichage du message original
- Pied de page informatif

### Email de Réponse Manuelle
- Design similaire mais avec couleur verte
- Affichage de la réponse personnalisée
- Invitation à recontacter si nécessaire

## Sécurité

### Bonnes pratiques
- Validation des adresses email
- Protection contre les injections
- Logs des erreurs pour diagnostic
- Gestion des exceptions

### Configuration recommandée
- Utiliser des mots de passe d'application Gmail
- Activer l'authentification à 2 facteurs
- Surveiller les logs d'erreur
- Tester régulièrement l'envoi

## Dépannage

### Vérifier la configuration
1. Créer le fichier `.env` avec les bonnes variables
2. Vérifier les identifiants Gmail
3. Tester avec le script de test

### Problèmes courants
1. **Email non reçu** : Vérifier les spams
2. **Erreur SMTP** : Vérifier la configuration
3. **Limite atteinte** : Attendre ou changer de compte

## Support

Pour toute question ou problème :
1. Vérifier les logs dans `storage/logs/laravel.log`
2. Utiliser les scripts de test
3. Vérifier la configuration SMTP
4. Tester avec différentes adresses email 