# Documentation - Édition des Articles de Blog avec Images

## Problème résolu

L'édition des articles de blog ne gérait pas correctement les images lors des requêtes PUT avec form-data, similaire au problème rencontré avec les formations.

## Modifications apportées

### 1. Contrôleur ArticleBlogController

Le contrôleur a été modifié pour gérer correctement les requêtes PUT avec form-data :

- **Gestion des données brutes** : Récupération de toutes les données de la requête
- **Validation manuelle** : Validation des données reçues avec gestion des champs optionnels
- **Gestion des images** : Support correct des fichiers image dans les requêtes PUT
- **Logging détaillé** : Ajout de logs pour le debugging
- **Gestion d'erreurs** : Amélioration de la gestion des erreurs avec try-catch

### 2. Routes API

Les routes ont été modifiées pour inclure une route alternative POST pour l'édition :

```php
Route::prefix('article-blogs')->group(function () {
    Route::get('/', [ArticleBlogController::class, 'index']);
    Route::get('/{article_blog}', [ArticleBlogController::class, 'show']);
    Route::post('/', [ArticleBlogController::class, 'store']);
    Route::put('/{article_blog}', [ArticleBlogController::class, 'update']);
    Route::post('/{article_blog}/update', [ArticleBlogController::class, 'update']); // Route alternative
    Route::delete('/{article_blog}', [ArticleBlogController::class, 'destroy']);
});
```

### 3. Middleware HandlePutFormData

Le middleware est déjà configuré dans le groupe 'api' et gère automatiquement les requêtes PUT avec form-data.

## Utilisation

### Via Postman

1. **Méthode PUT** : `PUT http://127.0.0.1:8000/api/article-blogs/{id}`
2. **Body** : form-data
3. **Champs** :
   - `titre` (text)
   - `contenu` (text)
   - `meta_titre` (text, optionnel)
   - `meta_description` (text, optionnel)
   - `date_publication` (text, format: YYYY-MM-DD)
   - `user_id` (text)
   - `slug` (text)
   - `image` (file, optionnel)

### Via cURL

```bash
curl -X PUT http://127.0.0.1:8000/api/article-blogs/2 \
  -F "titre=Article mis à jour" \
  -F "contenu=Contenu mis à jour" \
  -F "meta_titre=Meta titre" \
  -F "meta_description=Meta description" \
  -F "date_publication=2025-07-21" \
  -F "user_id=1" \
  -F "slug=article-mis-a-jour" \
  -F "image=@/path/to/image.jpg"
```

### Route alternative POST

Si vous rencontrez des problèmes avec PUT, vous pouvez utiliser la route POST alternative :

```bash
curl -X POST http://127.0.0.1:8000/api/article-blogs/2/update \
  -F "titre=Article mis à jour" \
  -F "contenu=Contenu mis à jour" \
  -F "image=@/path/to/image.jpg"
```

## Validation

Les champs sont validés avec les règles suivantes :

- `titre` : string, max 255 caractères, min 2 caractères
- `contenu` : string, min 10 caractères
- `meta_titre` : string, max 255 caractères, optionnel
- `meta_description` : string, optionnel
- `date_publication` : date, optionnel
- `user_id` : integer, doit exister dans la table users
- `slug` : string, max 255 caractères, min 2 caractères, unique
- `image` : image, types autorisés: jpg,jpeg,png,gif,webp, max 5MB

## Gestion des images

- Les images sont stockées dans le dossier `storage/app/public/article-blogs/`
- L'ancienne image est automatiquement supprimée lors de la mise à jour
- Les images sont accessibles via l'URL `/storage/article-blogs/{filename}`

## Réponse

La réponse inclut :

```json
{
  "success": true,
  "message": "Article mis à jour avec succès",
  "data": {
    "id": 2,
    "titre": "Article mis à jour",
    "contenu": "Contenu mis à jour",
    "image": "article-blogs/filename.jpg",
    "slug": "article-mis-a-jour",
    // ... autres champs
  }
}
```

## Test

Un fichier de test `test_article_blog_update.php` a été créé pour vérifier le fonctionnement de l'édition avec images.

## Logs

Les logs détaillés sont disponibles dans `storage/logs/laravel.log` pour le debugging des requêtes d'édition. 