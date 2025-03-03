# E-Discount - Site E-commerce de Sport

## Description
E-Discount est une plateforme e-commerce spécialisée dans la vente d'articles de sport. Le site permet aux utilisateurs de vendre et d'acheter des articles, avec une interface moderne.

## Fonctionnalités principales
- 👤 Système d'authentification (inscription/connexion)
- 🛍️ Gestion des articles (ajout, modification, suppression)
- 🛒 Panier d'achat
- 👨‍💼 Interface d'administration
- 🖼️ Upload d'images
- 📦 Gestion des stocks
- 🏷️ Système de catégories

## Prérequis
- PHP 8.1 ou supérieur
- Composer
- Symfony CLI
- SQLite
- Node.js et npm
- Git

## Installation

### 1. Cloner le projet
```bash
git clone https://github.com/TMallor/E-Discount.git
cd E-Discount
```

### 2. Installer les dépendances
```bash
# Installation des dépendances PHP
composer install

# Installation des dépendances JavaScript
npm install
npm run build
```

### 3. Configuration de la base de données
```bash
Pour accéder à la base de données utilisée dans ce projet, veuillez consulter le dépôt suivant :

https://github.com/TMallor/Base-de-donn-e-E-Discount

Installer la base de données et importer la dans le dossier var.

⚠️ Penser à installer "composer" avant.
```

### 4. Démarrer le projet
```bash
# Démarrer le serveur Symfony
symfony server:start

# Compiler les assets (dans un autre terminal)
npm run watch
```

## Compte et mot de passe 

- Admin : admin@admin.com - root0000


## Rôles utilisateurs
- `ROLE_USER` : Utilisateur connecté
  - Peut acheter des articles
  - Peut vendre des articles
  - Peut gérer son profil
- `ROLE_ADMIN` : Administrateur
  - Accès au tableau de bord admin
  - Gestion des utilisateurs
  - Gestion de tous les articles

## Routes principales
- `/` : Page d'accueil
- `/login` : Connexion
- `/register` : Inscription
- `/articles` : Liste des articles
- `/article/{id}` : Détail d'un article
- `/cart` : Panier
- `/admin` : Interface d'administration
- `/profile` : Profil utilisateur

## Commandes utiles
```bash
# Vider le cache
php bin/console cache:clear

# Créer une entité
php bin/console make:entity

# Créer une migration
php bin/console make:migration

# Créer un contrôleur
php bin/console make:controller
```

## Auteur

- [@TMallor](https://github.com/TMallor)
- [@eliongu](https://github.com/eliongu)
- [@vincent4player](https://github.com/vincent4player)
