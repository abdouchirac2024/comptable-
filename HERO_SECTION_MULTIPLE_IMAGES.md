# 🖼️ Images Multiples pour HeroSection

## 📋 **Vue d'ensemble**

Le système HeroSection a été étendu pour supporter **plusieurs images par slide**. Cette fonctionnalité permet de créer des carrousels riches avec de multiples images par slide.

## 🚀 **Fonctionnalités**

### **Images Multiples par Slide**
- ✅ **Ajout d'images multiples** : Chaque slide peut contenir plusieurs images
- ✅ **Métadonnées par image** : Alt text, caption, ordre d'affichage
- ✅ **Gestion des images** : Ajout, suppression, réordonnancement
- ✅ **Compatibilité** : Fonctionne avec l'image de fond existante

## 📊 **Structure des Données**

### **Champ `images` (JSON)**
```json
{
  "images": [
    {
      "path": "hero-slides/image1.jpg",
      "alt_text": "Description de l'image 1",
      "caption": "Légende de l'image 1",
      "display_order": 0
    },
    {
      "path": "hero-slides/image2.jpg",
      "alt_text": "Description de l'image 2",
      "caption": "Légende de l'image 2",
      "display_order": 1
    }
  ]
}
```

### **Champs Ajoutés**
- `images` : Array JSON des images multiples
- `image_alt_text` : Texte alternatif pour l'image principale

## 🔧 **Migration**

### **Migration pour Images Multiples**
```bash
php artisan migrate
```

**Fichier :** `database/migrations/2025_07_09_000007_modify_hero_slides_add_multiple_images.php`

```php
Schema::table('hero_slides', function (Blueprint $table) {
    $table->json('images')->nullable()->after('background_image');
    $table->string('image_alt_text')->nullable()->after('images');
});
```

## 📡 **API Endpoints**

### **1. Créer un Slide avec Images Multiples**
```http
POST /api/hero-slides
Content-Type: application/json

{
  "hero_section_id": 1,
  "title": "Slide avec Images Multiples",
  "subtitle": "Test des images multiples",
  "description": "Ce slide contient plusieurs images",
  "gradient": "bg-gradient-to-r from-blue-500 to-purple-600",
  "slide_duration": 5000,
  "is_active": true,
  "images": [
    {
      "path": "hero-slides/image1.jpg",
      "alt_text": "Image 1",
      "caption": "Première image",
      "display_order": 0
    },
    {
      "path": "hero-slides/image2.jpg",
      "alt_text": "Image 2",
      "caption": "Deuxième image",
      "display_order": 1
    }
  ]
}
```

### **2. Ajouter une Image à un Slide**
```http
POST /api/hero-slides/{id}/add-image
Content-Type: multipart/form-data

image: [fichier]
alt_text: "Description de l'image"
caption: "Légende de l'image"
```

### **3. Supprimer une Image d'un Slide**
```http
DELETE /api/hero-slides/{id}/remove-image
Content-Type: application/json

{
  "image_path": "hero-slides/image1.jpg"
}
```

### **4. Réordonnancer les Images**
```http
POST /api/hero-slides/{id}/reorder-images
Content-Type: application/json

{
  "image_orders": [2, 0, 1, 3]
}
```

### **5. Mettre à Jour avec Images Multiples**
```http
POST /api/hero-slides/{id}/update
Content-Type: application/json

{
  "title": "Slide Mis à Jour",
  "images": [
    {
      "path": "hero-slides/new_image1.jpg",
      "alt_text": "Nouvelle image 1",
      "caption": "Première nouvelle image",
      "display_order": 0
    }
  ]
}
```

## 🛠️ **Méthodes du Modèle**

### **HeroSlide Model**

```php
// Ajouter une image
$heroSlide->addImage($imagePath, $altText, $caption, $displayOrder);

// Supprimer une image
$heroSlide->removeImage($imagePath);

// Réordonnancer les images
$heroSlide->reorderImages([2, 0, 1, 3]);

// Obtenir les URLs des images
$imageUrls = $heroSlide->images_urls;
```

## 📝 **Exemples d'Utilisation**

### **Créer un Slide avec Images Multiples**
```php
$slideData = [
    'hero_section_id' => 1,
    'title' => 'Slide avec Images Multiples',
    'images' => [
        [
            'path' => 'hero-slides/image1.jpg',
            'alt_text' => 'Image 1',
            'caption' => 'Première image',
            'display_order' => 0
        ],
        [
            'path' => 'hero-slides/image2.jpg',
            'alt_text' => 'Image 2',
            'caption' => 'Deuxième image',
            'display_order' => 1
        ]
    ]
];

$slide = $heroSlideService->create($slideData);
```

### **Ajouter une Image via API**
```php
$addImageData = [
    'image' => $uploadedFile,
    'alt_text' => 'Nouvelle image',
    'caption' => 'Image ajoutée via API'
];

$response = $this->addImage($request, $heroSlide);
```

## 🔄 **Réponse API**

### **HeroSlideResource avec Images Multiples**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "title": "Slide avec Images Multiples",
    "background_image": "http://localhost:8000/storage/hero-slides/bg.jpg",
    "images": [
      {
        "url": "http://localhost:8000/storage/hero-slides/image1.jpg",
        "alt_text": "Image 1",
        "caption": "Première image",
        "display_order": 0
      },
      {
        "url": "http://localhost:8000/storage/hero-slides/image2.jpg",
        "alt_text": "Image 2",
        "caption": "Deuxième image",
        "display_order": 1
      }
    ],
    "image_alt_text": "Texte alternatif principal",
    "slide_duration": 5000,
    "is_active": true
  }
}
```

## 🧪 **Tests**

### **Fichier de Test**
- `test_hero_section_multiple_images.php` : Tests complets des images multiples

### **Exécuter les Tests**
```bash
php test_hero_section_multiple_images.php
```

## 📋 **Validation**

### **StoreHeroSlideRequest**
```php
'images_upload.*' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
'image_alt_text' => 'nullable|string|max:255',
```

### **UpdateHeroSlideRequest**
```php
'images_upload.*' => 'sometimes|nullable|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
'image_alt_text' => 'sometimes|nullable|string|max:255',
```

## 🔧 **Service Layer**

### **HeroSlideService - Nouvelles Méthodes**
```php
// Ajouter une image
public function addImage(HeroSlide $heroSlide, UploadedFile $file, $altText = null, $caption = null): HeroSlide

// Supprimer une image
public function removeImage(HeroSlide $heroSlide, string $imagePath): HeroSlide

// Réordonnancer les images
public function reorderImages(HeroSlide $heroSlide, array $imageOrders): HeroSlide
```

## 🎯 **Avantages**

1. **Flexibilité** : Plusieurs images par slide
2. **Métadonnées** : Alt text, caption, ordre d'affichage
3. **Gestion complète** : Ajout, suppression, réordonnancement
4. **Compatibilité** : Fonctionne avec l'image de fond existante
5. **Validation** : Règles de validation strictes
6. **Logging** : Traçabilité complète des opérations

## 🚀 **Utilisation Frontend**

### **Affichage des Images Multiples**
```javascript
// Exemple d'affichage des images multiples
slide.images.forEach(image => {
    console.log(`Image: ${image.url}`);
    console.log(`Alt: ${image.alt_text}`);
    console.log(`Caption: ${image.caption}`);
    console.log(`Order: ${image.display_order}`);
});
```

### **Carrousel d'Images**
```javascript
// Créer un carrousel avec les images multiples
const imageCarousel = slide.images
    .sort((a, b) => a.display_order - b.display_order)
    .map(image => `<img src="${image.url}" alt="${image.alt_text}">`);
```

## 📊 **Statistiques**

- **Images par slide** : Illimité
- **Taille max par image** : 5MB
- **Formats supportés** : JPG, JPEG, PNG, GIF, WebP
- **Stockage** : JSON dans la base de données
- **URLs** : Générées automatiquement

## 🔄 **Migration depuis l'Ancien Système**

Le système est **rétrocompatible** :
- Les slides existants continuent de fonctionner
- L'image de fond (`background_image`) reste inchangée
- Le champ `images` est optionnel

## ✅ **Tests Complets**

Le fichier `test_hero_section_multiple_images.php` teste :
1. ✅ Création de slide avec images multiples
2. ✅ Ajout d'images individuelles
3. ✅ Suppression d'images
4. ✅ Réordonnancement des images
5. ✅ Mise à jour avec nouvelles images
6. ✅ Affichage final du slide

---

**🎉 Le système HeroSection supporte maintenant les images multiples avec une gestion complète et flexible !** 