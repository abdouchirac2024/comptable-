# CRUD Contacts - Architecture SOLID

## Vue d'ensemble

Le CRUD des contacts est structuré selon les principes SOLID pour garantir une architecture claire, maintenable et testable.

## Principes SOLID appliqués

### 1. Single Responsibility Principle
- **ContactRepository** : accès aux données (Eloquent)
- **ContactService** : logique métier
- **ContactController** : gestion des requêtes HTTP
- **FormRequest** : validation centralisée
- **Resource** : formatage des réponses API

### 2. Open/Closed Principle
- Les interfaces permettent d'étendre sans modifier le code existant.

### 3. Liskov Substitution Principle
- Les dépendances sont injectées via des interfaces.

### 4. Interface Segregation Principle
- Les interfaces sont spécifiques à chaque entité.

### 5. Dependency Inversion Principle
- Les contrôleurs et services dépendent d'abstractions (interfaces), pas d'implémentations concrètes.

## Structure des fichiers
- `app/Repositories/Interfaces/ContactRepositoryInterface.php`
- `app/Repositories/ContactRepository.php`
- `app/Services/ContactService.php`
- `app/Http/Controllers/ContactController.php`
- `app/Http/Requests/Contact/CreateContactRequest.php`
- `app/Http/Requests/Contact/UpdateContactRequest.php`
- `app/Http/Resources/ContactResource.php`
- `app/Http/Resources/ContactCollection.php`
- `resources/lang/fr/contacts.php` et `resources/lang/en/contacts.php`
- `database/seeders/ContactSeeder.php`

## Utilisation
- Toutes les routes CRUD sont disponibles sous `/api/contacts`.
- Les messages sont traduits (français/anglais).
- Les tests peuvent être faits via Postman ou PHPUnit. 