# Exemples Postman - HeroSection avec Routes POST

## 🎯 **ROUTES POST POUR FORM-DATA**

### 📋 **1. METTRE À JOUR UN SLIDE HERO (POST)**

**Méthode :** `POST`  
**URL :** `http://127.0.0.1:8000/api/hero-slides/1/update`  
**Headers :**
```
Accept: application/json
Content-Type: multipart/form-data
```

**Body (form-data) :**
```
title: Titre mis à jour via POST
subtitle: Sous-titre mis à jour
description: Description mise à jour via méthode POST
gradient: bg-gradient-to-r from-red-500 to-yellow-600
slide_duration: 6000
is_active: true
```

**Réponse attendue :**
```json
{
    "success": true,
    "message": "Slide Hero mis à jour avec succès",
    "data": {
        "id": 1,
        "hero_section_id": 1,
        "slide_order": 1,
        "title": "Titre mis à jour via POST",
        "subtitle": "Sous-titre mis à jour",
        "description": "Description mise à jour via méthode POST",
        "gradient": "bg-gradient-to-r from-red-500 to-yellow-600",
        "background_image": null,
        "slide_duration": 6000,
        "is_active": true,
        "hero_section": null,
        "created_at": "2024-01-01T00:00:00.000000Z",
        "updated_at": "2024-01-01T00:00:00.000000Z"
    }
}
```

---

### 🎯 **2. METTRE À JOUR UN SLIDE HERO AVEC IMAGE (POST)**

**Méthode :** `POST`  
**URL :** `http://127.0.0.1:8000/api/hero-slides/1/update`  
**Headers :**
```
Accept: application/json
Content-Type: multipart/form-data
```

**Body (form-data) :**
```
title: Titre avec nouvelle image via POST
subtitle: Sous-titre avec nouvelle image
description: Description avec nouvelle image et contenu mis à jour via POST
gradient: bg-gradient-to-r from-purple-500 to-pink-600
slide_duration: 7000
is_active: true
background_image: [Sélectionnez un nouveau fichier image]
```

**Réponse attendue :**
```json
{
    "success": true,
    "message": "Slide Hero mis à jour avec succès",
    "data": {
        "id": 1,
        "hero_section_id": 1,
        "slide_order": 1,
        "title": "Titre avec nouvelle image via POST",
        "subtitle": "Sous-titre avec nouvelle image",
        "description": "Description avec nouvelle image et contenu mis à jour via POST",
        "gradient": "bg-gradient-to-r from-purple-500 to-pink-600",
        "background_image": "http://127.0.0.1:8000/storage/hero-slides/1703123457_def456.jpg",
        "slide_duration": 7000,
        "is_active": true,
        "hero_section": null,
        "created_at": "2024-01-01T00:00:00.000000Z",
        "updated_at": "2024-01-01T00:00:00.000000Z"
    }
}
```

---

### 🎯 **3. METTRE À JOUR UNE SECTION HERO (POST)**

**Méthode :** `POST`  
**URL :** `http://127.0.0.1:8000/api/hero-sections/1/update`  
**Headers :**
```
Accept: application/json
Content-Type: multipart/form-data
```

**Body (form-data) :**
```
is_active: false
```

**Réponse attendue :**
```json
{
    "success": true,
    "message": "Section Hero mise à jour avec succès",
    "data": {
        "id": 1,
        "is_active": false,
        "slides": [...],
        "active_slides": [...],
        "slides_count": null,
        "created_at": "2024-01-01T00:00:00.000000Z",
        "updated_at": "2024-01-01T00:00:00.000000Z"
    }
}
```

---

### 🎯 **4. CRÉER UNE SECTION HERO (POST)**

**Méthode :** `POST`  
**URL :** `http://127.0.0.1:8000/api/hero-sections`  
**Headers :**
```
Accept: application/json
Content-Type: multipart/form-data
```

**Body (form-data) :**
```
is_active: true
```

**Réponse attendue :**
```json
{
    "success": true,
    "message": "Section Hero créée avec succès",
    "data": {
        "id": 1,
        "is_active": true,
        "slides": [],
        "active_slides": [],
        "slides_count": null,
        "created_at": "2024-01-01T00:00:00.000000Z",
        "updated_at": "2024-01-01T00:00:00.000000Z"
    }
}
```

---

### 🎯 **5. CRÉER UN SLIDE HERO (POST)**

**Méthode :** `POST`  
**URL :** `http://127.0.0.1:8000/api/hero-slides`  
**Headers :**
```
Accept: application/json
Content-Type: multipart/form-data
```

**Body (form-data) :**
```
hero_section_id: 1
slide_order: 1
title: Bienvenue sur notre site
subtitle: Découvrez nos services
description: Une description détaillée de nos services et de notre expertise.
gradient: bg-gradient-to-r from-blue-500 to-purple-600
slide_duration: 5000
is_active: true
```

**Réponse attendue :**
```json
{
    "success": true,
    "message": "Slide Hero créé avec succès",
    "data": {
        "id": 1,
        "hero_section_id": 1,
        "slide_order": 1,
        "title": "Bienvenue sur notre site",
        "subtitle": "Découvrez nos services",
        "description": "Une description détaillée de nos services et de notre expertise.",
        "gradient": "bg-gradient-to-r from-blue-500 to-purple-600",
        "background_image": null,
        "slide_duration": 5000,
        "is_active": true,
        "hero_section": null,
        "created_at": "2024-01-01T00:00:00.000000Z",
        "updated_at": "2024-01-01T00:00:00.000000Z"
    }
}
```

---

### 🎯 **6. CRÉER UN SLIDE HERO AVEC IMAGE (POST)**

**Méthode :** `POST`  
**URL :** `http://127.0.0.1:8000/api/hero-slides`  
**Headers :**
```
Accept: application/json
Content-Type: multipart/form-data
```

**Body (form-data) :**
```
hero_section_id: 1
slide_order: 2
title: Nos formations
subtitle: Apprenez avec nous
description: Des formations de qualité pour développer vos compétences.
gradient: bg-gradient-to-r from-green-500 to-blue-600
slide_duration: 4000
is_active: true
background_image: [Sélectionnez un fichier image]
```

**Réponse attendue :**
```json
{
    "success": true,
    "message": "Slide Hero créé avec succès",
    "data": {
        "id": 2,
        "hero_section_id": 1,
        "slide_order": 2,
        "title": "Nos formations",
        "subtitle": "Apprenez avec nous",
        "description": "Des formations de qualité pour développer vos compétences.",
        "gradient": "bg-gradient-to-r from-green-500 to-blue-600",
        "background_image": "http://127.0.0.1:8000/storage/hero-slides/1703123456_abc123.jpg",
        "slide_duration": 4000,
        "is_active": true,
        "hero_section": null,
        "created_at": "2024-01-01T00:00:00.000000Z",
        "updated_at": "2024-01-01T00:00:00.000000Z"
    }
}
```

---

## 📋 **REQUÊTES GET (SANS FORM-DATA)**

### 🎯 **7. RÉCUPÉRER LA SECTION HERO ACTIVE**

**Méthode :** `GET`  
**URL :** `http://127.0.0.1:8000/api/hero-sections/active`  
**Headers :**
```
Accept: application/json
```

### 🎯 **8. RÉCUPÉRER LES SLIDES D'UNE SECTION**

**Méthode :** `GET`  
**URL :** `http://127.0.0.1:8000/api/hero-slides/section/1`  
**Headers :**
```
Accept: application/json
```

### 🎯 **9. RECHERCHER DES SLIDES**

**Méthode :** `GET`  
**URL :** `http://127.0.0.1:8000/api/hero-slides?search=formation&is_active=true`  
**Headers :**
```
Accept: application/json
```

---

## 🎯 **ROUTES POST POUR ACTIONS SPÉCIALES**

### 🎯 **10. RÉORDONNANCER LES SLIDES**

**Méthode :** `POST`  
**URL :** `http://127.0.0.1:8000/api/hero-slides/section/1/reorder`  
**Headers :**
```
Accept: application/json
Content-Type: multipart/form-data
```

**Body (form-data) :**
```
slide_orders[1]: 3
slide_orders[2]: 1
slide_orders[3]: 2
```

**Réponse attendue :**
```json
{
    "success": true,
    "message": "Slides réordonnés avec succès"
}
```

---

### 🎯 **11. ACTIVER UN SLIDE**

**Méthode :** `POST`  
**URL :** `http://127.0.0.1:8000/api/hero-slides/1/activate`  
**Headers :**
```
Accept: application/json
Content-Type: multipart/form-data
```

**Body (form-data) :**
```
is_active: true
```

**Réponse attendue :**
```json
{
    "success": true,
    "message": "Slide Hero activé avec succès",
    "data": {
        "id": 1,
        "hero_section_id": 1,
        "slide_order": 3,
        "title": "Titre avec nouvelle image via POST",
        "subtitle": "Sous-titre avec nouvelle image",
        "description": "Description avec nouvelle image et contenu mis à jour via POST",
        "gradient": "bg-gradient-to-r from-purple-500 to-pink-600",
        "background_image": "http://127.0.0.1:8000/storage/hero-slides/1703123457_def456.jpg",
        "slide_duration": 7000,
        "is_active": true,
        "hero_section": null,
        "created_at": "2024-01-01T00:00:00.000000Z",
        "updated_at": "2024-01-01T00:00:00.000000Z"
    }
}
```

---

### 🎯 **12. DÉSACTIVER UN SLIDE**

**Méthode :** `POST`  
**URL :** `http://127.0.0.1:8000/api/hero-slides/1/deactivate`  
**Headers :**
```
Accept: application/json
Content-Type: multipart/form-data
```

**Body (form-data) :**
```
is_active: false
```

**Réponse attendue :**
```json
{
    "success": true,
    "message": "Slide Hero désactivé avec succès",
    "data": {
        "id": 1,
        "hero_section_id": 1,
        "slide_order": 3,
        "title": "Titre avec nouvelle image via POST",
        "subtitle": "Sous-titre avec nouvelle image",
        "description": "Description avec nouvelle image et contenu mis à jour via POST",
        "gradient": "bg-gradient-to-r from-purple-500 to-pink-600",
        "background_image": "http://127.0.0.1:8000/storage/hero-slides/1703123457_def456.jpg",
        "slide_duration": 7000,
        "is_active": false,
        "hero_section": null,
        "created_at": "2024-01-01T00:00:00.000000Z",
        "updated_at": "2024-01-01T00:00:00.000000Z"
    }
}
```

---

### 🎯 **13. ACTIVER UNE SECTION HERO**

**Méthode :** `POST`  
**URL :** `http://127.0.0.1:8000/api/hero-sections/1/activate`  
**Headers :**
```
Accept: application/json
Content-Type: multipart/form-data
```

**Body (form-data) :**
```
is_active: true
```

**Réponse attendue :**
```json
{
    "success": true,
    "message": "Section Hero activée avec succès",
    "data": {
        "id": 1,
        "is_active": true,
        "slides": [...],
        "active_slides": [...],
        "slides_count": null,
        "created_at": "2024-01-01T00:00:00.000000Z",
        "updated_at": "2024-01-01T00:00:00.000000Z"
    }
}
```

---

### 🎯 **14. DÉSACTIVER UNE SECTION HERO**

**Méthode :** `POST`  
**URL :** `http://127.0.0.1:8000/api/hero-sections/1/deactivate`  
**Headers :**
```
Accept: application/json
Content-Type: multipart/form-data
```

**Body (form-data) :**
```
is_active: false
```

**Réponse attendue :**
```json
{
    "success": true,
    "message": "Section Hero désactivée avec succès",
    "data": {
        "id": 1,
        "is_active": false,
        "slides": [...],
        "active_slides": [...],
        "slides_count": null,
        "created_at": "2024-01-01T00:00:00.000000Z",
        "updated_at": "2024-01-01T00:00:00.000000Z"
    }
}
```

---

## 🎨 **EXEMPLES DE GRADIENTS POUR FORM-DATA**

Utilisez ces gradients dans le champ `gradient` :

```
bg-gradient-to-r from-blue-500 to-purple-600
bg-gradient-to-r from-green-500 to-blue-600
bg-gradient-to-r from-red-500 to-yellow-600
bg-gradient-to-r from-purple-500 to-pink-600
bg-gradient-to-r from-indigo-500 to-purple-600
bg-gradient-to-r from-pink-500 to-red-600
bg-gradient-to-r from-yellow-500 to-orange-600
bg-gradient-to-r from-teal-500 to-blue-600
bg-gradient-to-r from-gray-500 to-gray-700
bg-gradient-to-r from-blue-600 to-indigo-700
```

---

## 🎯 **SÉQUENCE DE TEST AVEC POST**

1. **Créer une section Hero** (POST /api/hero-sections)
2. **Créer un premier slide sans image** (POST /api/hero-slides)
3. **Créer un deuxième slide avec image** (POST /api/hero-slides)
4. **Mettre à jour un slide sans image** (POST /api/hero-slides/1/update)
5. **Mettre à jour un slide avec nouvelle image** (POST /api/hero-slides/1/update)
6. **Créer un troisième slide avec image** (POST /api/hero-slides)
7. **Réordonnancer les slides** (POST /api/hero-slides/section/1/reorder)
8. **Activer/Désactiver des slides** (POST /api/hero-slides/1/activate)
9. **Activer/Désactiver la section** (POST /api/hero-sections/1/activate)
10. **Récupérer la section active** (GET /api/hero-sections/active)

---

## 🔧 **CONFIGURATION POSTMAN**

### Variables de collection :
```
base_url: http://127.0.0.1:8000
hero_section_id: 1
hero_slide_id: 1
```

### Headers par défaut :
```
Accept: application/json
Content-Type: multipart/form-data
```

Ces exemples avec **routes POST** vous permettront de tester toutes les fonctionnalités du système HeroSection avec form-data, exactement comme pour les formations ! 🎯 