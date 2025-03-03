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
- MySQL 5.7 ou supérieur
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

[Base de données E-Discount](https://github.com/TMallor/Base-de-donn-e-E-Discount)
```

### 3.1 Effectuer les migrations
```bash
php bin/console doctrine:migrations:migrate

```

### 4. Démarrer le projet
```bash
# Démarrer le serveur Symfony
symfony server:start

# Compiler les assets (dans un autre terminal)
npm run watch
```

## Structure du projet
```
e-discount/
├── assets/ # Fichiers front-end (JS, CSS)
├── config/ # Configuration Symfony
├── public/ # Fichiers publics
│ ├── uploads/ # Images uploadées
│ └── styles/ # CSS compilé
├── src/
│ ├── Controller/ # Contrôleurs
│ ├── Entity/ # Entités
│ ├── Form/ # Formulaires
│ └── Repository/ # Repositories
└── templates/ # Templates Twig
```

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

## Contribution
1. Fork le projet
2. Créer une branche (`git checkout -b feature/nouvelle-fonctionnalite`)
3. Commit les changements (`git commit -am 'Ajout d'une nouvelle fonctionnalité'`)
4. Push la branche (`git push origin feature/nouvelle-fonctionnalite`)
5. Créer une Pull Request