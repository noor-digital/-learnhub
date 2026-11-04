# LearnHub

LearnHub est une plateforme web de gestion et de suivi de formations en ligne.

## Technologies

- PHP 8.2+
- Laravel 12
- MySQL / MariaDB
- JavaScript, Alpine.js et Vite
- Tailwind CSS

## Fonctionnalités

- Gestion des utilisateurs et des rôles
- Gestion des catégories et des cours
- Inscriptions aux cours
- Leçons et suivi des apprentissages
- Quiz, questions, réponses et résultats
- Avis sur les cours

## Installation locale

1. Cloner le dépôt :
   ```bash
   git clone URL_DU_DEPOT
   cd lms-platform
   ```
2. Installer les dépendances PHP et JavaScript :
   ```bash
   composer install
   npm install
   ```
3. Créer le fichier d'environnement :
   ```bash
   copy .env.example .env
   ```
4. Générer la clé Laravel :
   ```bash
   php artisan key:generate
   ```
5. Configurer la connexion MySQL dans `.env`, puis exécuter :
   ```bash
   php artisan migrate
   ```
6. Compiler les ressources :
   ```bash
   npm run build
   ```
7. Démarrer le serveur local :
   ```bash
   php artisan serve
   ```

## Tests

```bash
php artisan test
```

## État du projet

Projet en développement. Les instructions de déploiement seront ajoutées après validation de l'hébergement et de la configuration de production.
