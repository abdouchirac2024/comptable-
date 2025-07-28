# Système HeroSection - Architecture SOLID

## Vue d'ensemble

Le système HeroSection permet de gérer des sections hero dynamiques avec des carrousels de slides. Chaque section peut contenir plusieurs slides avec des images de fond, des gradients CSS, et des durées d'affichage personnalisées.

## Architecture SOLID

### 1. **S** - Single Responsibility Principle
- **HeroSectionRepository** : Gestion de l'accès aux données des sections
- **HeroSlideRepository** : Gestion de l'accès aux données des slides
- **HeroSectionService** : Logique métier des sections
- **HeroSlideService** : Logique métier des slides
- **HeroSectionController** : Gestion des requêtes HTTP des sections
- **HeroSlideController** : Gestion des requêtes HTTP des slides

### 2. **O** - Open/Closed Principle
- Interfaces permettent l'extension sans modification
- Services peuvent être étendus avec de nouvelles fonctionnalités

### 3. **L** - Liskov Substitution Principle
- Toute implémentation des interfaces peut remplacer les repositories

### 4. **I** - Interface Segregation Principle
- Interfaces spécifiques pour chaque entité
- Pas de dépendances inutiles

### 5. **D** - Dependency Inversion Principle
- Contrôleurs et services dépendent d'abstractions
- Configuration dans AppServiceProvider

## Structure de la Base de Données

### Table `hero_sections`
```sql
- id (primary key)
- is_active (boolean)
- created_at, updated_at
```

### Table `hero_slides`
```sql
- id (primary key)
- hero_section_id (foreign key)
- slide_order (integer)
- title (string)
- subtitle (string, nullable)
- description (text, nullable)
- gradient (string, nullable) - Classes CSS
- background_image (string, nullable) - Chemin vers l'image
- slide_duration (integer) - Durée en millisecondes
- is_active (boolean)
- created_at, updated_at
```

## Modèles et Relations

### HeroSection Model
```php
- Relations: hasMany(HeroSlide)
- Scopes: active()
- Champs fillable: ['is_active']
```

### HeroSlide Model
```php
- Relations: belongsTo(HeroSection)
- Scopes: active(), ordered()
- Accessor: background_image_url
- Champs fillable: [tous les champs]
```

## Services

### HeroSectionService
- `list()` - Liste paginée avec filtres
- `find()` - Recherche par ID
- `findActive()` - Section active
- `create()` - Création
- `update()` - Mise à jour
- `delete()` - Suppression
- `activate()` - Activer (désactive les autres)
- `deactivate()` - Désactiver

### HeroSlideService
- `list()` - Liste paginée avec filtres
- `findBySection()` - Slides d'une section
- `find()` - Recherche par ID
- `create()` - Création avec gestion d'images
- `update()` - Mise à jour avec gestion d'images
- `delete()` - Suppression avec nettoyage d'images
- `reorderSlides()` - Réordonnancement
- `activate()` / `deactivate()` - Gestion du statut

## Contrôleurs

### HeroSectionController
- `index()` - Liste des sections
- `show()` - Détails d'une section
- `active()` - Section active
- `store()` - Création
- `update()` - Mise à jour
- `destroy()` - Suppression
- `activate()` / `deactivate()` - Gestion du statut

### HeroSlideController
- `index()` - Liste des slides
- `show()` - Détails d'un slide
- `bySection()` - Slides d'une section
- `store()` - Création
- `update()` - Mise à jour avec form-data
- `destroy()` - Suppression
- `reorder()` - Réordonnancement
- `activate()` / `deactivate()` - Gestion du statut

## Routes API

### Sections Hero
```
GET    /api/hero-sections              - Liste des sections
GET    /api/hero-sections/active       - Section active
GET    /api/hero-sections/{id}         - Détails d'une section
POST   /api/hero-sections              - Créer une section
PUT    /api/hero-sections/{id}         - Mettre à jour
POST   /api/hero-sections/{id}/update  - Mettre à jour (form-data)
DELETE /api/hero-sections/{id}         - Supprimer
POST   /api/hero-sections/{id}/activate   - Activer
POST   /api/hero-sections/{id}/deactivate - Désactiver
```

### Slides Hero
```
GET    /api/hero-slides                    - Liste des slides
GET    /api/hero-slides/{id}               - Détails d'un slide
GET    /api/hero-slides/section/{id}       - Slides d'une section
POST   /api/hero-slides                    - Créer un slide
PUT    /api/hero-slides/{id}               - Mettre à jour
POST   /api/hero-slides/{id}/update        - Mettre à jour (form-data)
DELETE /api/hero-slides/{id}               - Supprimer
POST   /api/hero-slides/section/{id}/reorder - Réordonnancer
POST   /api/hero-slides/{id}/activate      - Activer
POST   /api/hero-slides/{id}/deactivate    - Désactiver
```

## Validation des Données

### HeroSection
- `is_active` : boolean

### HeroSlide
- `hero_section_id` : required, exists
- `slide_order` : integer, min:0
- `title` : required, string, max:255, min:2
- `subtitle` : nullable, string, max:255
- `description` : nullable, string, max:1000
- `gradient` : nullable, string, max:255
- `background_image` : nullable, image, max:5MB
- `slide_duration` : integer, min:1000, max:30000
- `is_active` : boolean

## Gestion des Images

### Stockage
- Dossier : `storage/app/public/hero-slides/`
- Nommage : `timestamp_randomstring.extension`
- Formats supportés : jpg, jpeg, png, gif, webp
- Taille maximale : 5MB

### URLs
- Génération automatique via `HeroSlideResource`
- Support des URLs externes
- Fallback si image non trouvée

## Fonctionnalités Avancées

### 1. Réordonnancement des Slides
```php
POST /api/hero-slides/section/{id}/reorder
{
    "slide_orders": {
        "1": 2,
        "2": 1
    }
}
```

### 2. Activation/Désactivation
- Une seule section peut être active à la fois
- Les slides peuvent être activés/désactivés individuellement

### 3. Recherche et Filtres
- Recherche par titre, sous-titre, description
- Filtrage par statut actif
- Pagination configurable

### 4. Logging
- Toutes les opérations sont loggées
- Traçabilité complète des modifications

## Exemples d'Utilisation

### Créer une Section Hero
```json
POST /api/hero-sections
{
    "is_active": true
}
```

### Créer un Slide
```json
POST /api/hero-slides
{
    "hero_section_id": 1,
    "slide_order": 1,
    "title": "Bienvenue",
    "subtitle": "Découvrez nos services",
    "description": "Description détaillée",
    "gradient": "bg-gradient-to-r from-blue-500 to-purple-600",
    "slide_duration": 5000,
    "is_active": true
}
```

### Mettre à jour un Slide (form-data)
```
POST /api/hero-slides/1/update
Content-Type: multipart/form-data

title: Titre mis à jour
subtitle: Sous-titre mis à jour
description: Description mise à jour
gradient: bg-gradient-to-r from-red-500 to-yellow-600
slide_duration: 6000
is_active: true
background_image: [fichier image]
```

### Réponse API
```json
{
    "success": true,
    "message": "Slide Hero créé avec succès",
    "data": {
        "id": 1,
        "hero_section_id": 1,
        "slide_order": 1,
        "title": "Bienvenue",
        "subtitle": "Découvrez nos services",
        "description": "Description détaillée",
        "gradient": "bg-gradient-to-r from-blue-500 to-purple-600",
        "background_image": "http://localhost:8000/storage/hero-slides/image.jpg",
        "slide_duration": 5000,
        "is_active": true,
        "created_at": "2024-01-01T00:00:00.000000Z",
        "updated_at": "2024-01-01T00:00:00.000000Z"
    }
}
```

## Tests

### Fichiers de Test
- `test_hero_section.php` - Tests complets avec cURL
- `test_hero_section_post.php` - Tests avec routes POST (form-data)
- Tests de création, mise à jour, réordonnancement
- Instructions Postman incluses

### Exécution des Tests
```bash
php test_hero_section.php
php test_hero_section_post.php
```

## Points Forts

✅ **Architecture SOLID** - Séparation claire des responsabilités  
✅ **Gestion d'images** - Upload, stockage et nettoyage automatique  
✅ **Validation robuste** - Règles de validation complètes  
✅ **Logging détaillé** - Traçabilité des opérations  
✅ **Gestion d'erreurs** - Réponses JSON standardisées  
✅ **Support form-data** - Compatible avec les formulaires HTML  
✅ **Routes POST alternatives** - Pour éviter les problèmes PUT  
✅ **Réordonnancement** - Gestion de l'ordre des slides  
✅ **Activation/Désactivation** - Gestion des statuts  
✅ **Recherche et filtres** - Fonctionnalités de recherche avancées  
✅ **Relations optimisées** - Chargement eager des relations  
✅ **Tests complets** - Fichiers de test pour validation  

Le système HeroSection est **complet et production-ready** avec une architecture moderne Laravel suivant les meilleures pratiques ! 🚀 