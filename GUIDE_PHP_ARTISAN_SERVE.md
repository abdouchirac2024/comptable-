# Guide : Utiliser php artisan serve avec l'API Formations

## 🚀 Démarrage rapide

### 1. Configuration initiale
```bash
# Exécuter le script de configuration
php setup_laravel.php
```

### 2. Démarrer le serveur
```bash
# Démarrer le serveur Laravel
php artisan serve --host=127.0.0.1 --port=8000
```

### 3. Vérifier que le serveur fonctionne
```bash
# Tester l'API
php test_api_formation.php
```

## 📋 Configuration requise

### Fichier .env
Le script `setup_laravel.php` crée automatiquement le fichier `.env` avec :
- Configuration de base Laravel
- Configuration email Gmail
- Configuration de base de données

### Clé d'application
```bash
# Générer la clé d'application
php artisan key:generate
```

## 🔧 Tests avec Postman

### Configuration Postman
1. **URL de base** : `http://127.0.0.1:8000`
2. **Headers** : `Accept: application/json`

### Test PUT Formation
```
Method: PUT
URL: http://127.0.0.1:8000/api/formations/1
Body: form-data

Fields:
- nom: "Formation Test"
- description: "Description test"
- duree: "6 mois"
- tarif: "200€"
- slug: "formation-test"
- image: [fichier image]
```

## 📊 Réponses API

### Succès (200)
```json
{
    "success": true,
    "message": "Formation mise à jour avec succès",
    "data": {
        "id": 1,
        "nom": "Formation Test",
        "slug": "formation-test",
        "description": "Description test",
        "duree": "6 mois",
        "tarif": "200€",
        "image": "http://127.0.0.1:8000/storage/formations/...",
        "created_at": "2025-07-28T08:31:18.000000Z",
        "updated_at": "2025-07-28T08:35:20.000000Z"
    }
}
```

### Erreur (422 - Validation)
```json
{
    "success": false,
    "message": "Erreur lors de la mise à jour de la formation",
    "error": "The nom field is required."
}
```

### Erreur (404 - Non trouvé)
```json
{
    "success": false,
    "message": "Formation non trouvée"
}
```

## 🛠️ Dépannage

### Problème : Serveur ne démarre pas
```bash
# Vérifier les erreurs
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### Problème : Erreur de base de données
```bash
# Vérifier la configuration
php artisan migrate:status
```

### Problème : Erreur de permissions
```bash
# Donner les permissions aux dossiers
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

## 📝 Logs et debugging

### Voir les logs
```bash
# Logs Laravel
tail -f storage/logs/laravel.log
```

### Mode debug
Dans `.env` :
```
APP_DEBUG=true
LOG_LEVEL=debug
```

## 🔍 Tests automatisés

### Test en ligne de commande
```bash
# Test de mise à jour
php test_formation_update.php

# Test API
php test_api_formation.php
```

### Test avec cURL
```bash
# Test GET
curl -X GET http://127.0.0.1:8000/api/formations

# Test PUT
curl -X PUT http://127.0.0.1:8000/api/formations/1 \
  -F "nom=Formation Test" \
  -F "description=Description test" \
  -F "duree=6 mois" \
  -F "tarif=200€" \
  -F "slug=formation-test"
```

## 🎯 Fonctionnalités implémentées

### ✅ Validation robuste
- Règles de validation détaillées
- Messages d'erreur personnalisés
- Validation unique des slugs

### ✅ Gestion des images
- Upload automatique
- Suppression des anciennes images
- Génération de noms uniques

### ✅ Gestion d'erreurs
- Try/catch complet
- Logs détaillés
- Réponses JSON standardisées

### ✅ API RESTful
- Méthodes HTTP appropriées
- Codes de statut corrects
- Ressources JSON structurées

## 🚀 Commandes utiles

```bash
# Démarrer le serveur
php artisan serve

# Démarrer avec host spécifique
php artisan serve --host=0.0.0.0 --port=8000

# Voir les routes
php artisan route:list

# Voir les routes API
php artisan route:list --path=api

# Nettoyer le cache
php artisan optimize:clear
```

## 📱 Test avec Postman

1. **Ouvrir Postman**
2. **Créer une nouvelle requête**
3. **Configurer :**
   - Method: `PUT`
   - URL: `http://127.0.0.1:8000/api/formations/1`
   - Body: `form-data`
4. **Ajouter les champs :**
   - `nom`: "Formation Test"
   - `description`: "Description test"
   - `duree`: "6 mois"
   - `tarif`: "200€"
   - `slug`: "formation-test"
   - `image`: [sélectionner fichier]
5. **Envoyer la requête**

## ✅ Vérification

Après avoir suivi ce guide, vous devriez pouvoir :
- ✅ Démarrer `php artisan serve`
- ✅ Accéder à l'API sur `http://127.0.0.1:8000`
- ✅ Mettre à jour les formations via Postman
- ✅ Recevoir des réponses JSON correctes
- ✅ Voir les logs détaillés dans `storage/logs/laravel.log` 