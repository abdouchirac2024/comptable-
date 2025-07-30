# Documentation - Édition des Services avec Images

## Problème résolu

L'édition des services ne gérait pas correctement les images lors des requêtes PUT avec form-data, similaire au problème rencontré avec les articles de blog et les formations.

## Modifications apportées

### 1. Contrôleur ServiceController

Le contrôleur a été modifié pour gérer correctement les requêtes PUT avec form-data :

- **Gestion des données brutes** : Récupération de toutes les données de la requête
- **Validation manuelle** : Validation des données reçues avec gestion des champs optionnels
- **Gestion des images** : Support correct des fichiers image dans les requêtes PUT
- **Logging détaillé** : Ajout de logs pour le debugging
- **Gestion d'erreurs** : Amélioration de la gestion des erreurs avec try-catch

### 2. Routes API

Les routes ont été modifiées pour inclure une route alternative POST pour l'édition :

```php
Route::prefix('services')->group(function () {
    Route::get('/', [ServiceController::class, 'index']);
    Route::get('/{service}', [ServiceController::class, 'show']);
    Route::post('/', [ServiceController::class, 'store']);
    Route::put('/{service}', [ServiceController::class, 'update']);
    Route::post('/{service}/update', [ServiceController::class, 'update']); // Route alternative
    Route::delete('/{service}', [ServiceController::class, 'destroy']);
});
```

### 3. Middleware HandlePutFormData

Le middleware est déjà configuré dans le groupe 'api' et gère automatiquement les requêtes PUT avec form-data.

## Utilisation

### Via Postman

1. **Méthode PUT** : `PUT http://127.0.0.1:8000/api/services/{id}`
2. **Body** : form-data
3. **Champs** :
   - `nom` (text)
   - `description` (text)
   - `categorie` (text)
   - `tarif` (text, optionnel)
   - `duree` (text, optionnel)
   - `slug` (text)
   - `image` (file, optionnel)

### Via cURL

```bash
curl -X PUT http://127.0.0.1:8000/api/services/2 \
  -F "nom=Service mis à jour" \
  -F "description=Description mise à jour" \
  -F "categorie=Consultation" \
  -F "tarif=150€" \
  -F "duree=2 heures" \
  -F "slug=service-mis-a-jour" \
  -F "image=@/path/to/image.jpg"
```

### Route alternative POST

Si vous rencontrez des problèmes avec PUT, vous pouvez utiliser la route POST alternative :

```bash
curl -X POST http://127.0.0.1:8000/api/services/2/update \
  -F "nom=Service mis à jour" \
  -F "description=Description mise à jour" \
  -F "image=@/path/to/image.jpg"
```

## Validation

Les champs sont validés avec les règles suivantes :

- `nom` : string, max 255 caractères, min 2 caractères
- `description` : string, min 10 caractères
- `categorie` : string, max 255 caractères
- `tarif` : string, max 255 caractères, optionnel
- `duree` : string, max 255 caractères, optionnel
- `slug` : string, max 255 caractères, min 2 caractères, unique
- `image` : image, types autorisés: jpg,jpeg,png,gif,webp, max 5MB

## Gestion des images

- Les images sont stockées dans le dossier `storage/app/public/services/`
- L'ancienne image est automatiquement supprimée lors de la mise à jour
- Les images sont accessibles via l'URL `/storage/services/{filename}`

## Réponse

La réponse inclut :

```json
{
  "success": true,
  "message": "Service mis à jour avec succès",
  "data": {
    "id": 2,
    "nom": "Service mis à jour",
    "description": "Description mise à jour",
    "categorie": "Consultation",
    "tarif": "150€",
    "duree": "2 heures",
    "image": "services/filename.jpg",
    "slug": "service-mis-a-jour",
    // ... autres champs
  }
}
```

## Test

Un fichier de test `test_service_update.php` a été créé pour vérifier le fonctionnement de l'édition avec images.

## Logs

Les logs détaillés sont disponibles dans `storage/logs/laravel.log` pour le debugging des requêtes d'édition.

## Exemple d'utilisation avec Postman

1. **Méthode** : PUT
2. **URL** : `http://127.0.0.1:8000/api/services/2`
3. **Body** : form-data
4. **Champs** :
   - `nom` : "Consultation comptable"
   - `description` : "Service de consultation comptable professionnel"
   - `categorie` : "Comptabilité"
   - `tarif` : "200€"
   - `duree` : "3 heures"
   - `slug` : "consultation-comptable"
   - `image` : fichier (image.jpg)

## Structure de la base de données

La table `services` contient les champs suivants :
- `id` : clé primaire
- `nom` : nom du service
- `description` : description du service
- `categorie` : catégorie du service
- `slug` : slug unique pour l'URL
- `image` : chemin de l'image (optionnel)
- `tarif` : tarif du service (optionnel)
- `duree` : durée du service (optionnel)
- `created_at` et `updated_at` : timestamps 