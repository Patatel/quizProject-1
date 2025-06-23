# QuizPlatform

**QuizPlatform** est une application web de quiz en ligne développée en PHP. Elle permet aux utilisateurs de créer, modifier, supprimer et participer à des quiz, tout en visualisant les résultats.

## 🛠️ Technologies utilisées

- PHP 8.0 avec Apache
- MySQL
- HTML / CSS / JavaScript
- Docker & Docker Compose
- PHPUnit (tests unitaires)

## 🚀 Installation et déploiement local avec Docker

### Prérequis

- [Docker](https://www.docker.com/) installé sur votre machine
- [Docker Compose](https://docs.docker.com/compose/)

### Étapes

1. Cloner le dépôt :
   ```bash
   git clone https://github.com/Juuunnne/quizProject
   cd quizplatform


2. Déployer l’application :

docker compose down -v --remove-orphans
docker compose build --no-cache
docker compose up

3. Accéder à l’application :

Frontend : http://localhost:8080



### Structure des conteneurs
web : conteneur PHP + Apache contenant l'application

db : conteneur MySQL avec la base de données

migrator : conteneur de migration exécutant le script d'initialisation de la base (wait-and-migrate.sh)


### Configuration
Les variables d’environnement sont définies dans le fichier docker-compose.yml. Par défaut :

DB_HOST=db
DB_NAME=quizproject
DB_USER=user
DB_PASS=password

### Tests
Les tests unitaires sont écrits avec PHPUnit et exécutés en dehors de Docker avec une base de données dédiée.
Base de données de test recommandée : quizproject_test.

Structure des tests :

QuizModelTest.php
ResultModelTest.php
UserModelTest.php

📂 Structure du projet
📁 api/
📁 public/
📁 tests/
📁 docs/
📄 docker-compose.yml
📄 Dockerfile
📄 composer.json
📄 .docker/wait-and-migrate.sh


### Sécurité
Hashage des mots de passe (password_hash)

Utilisation prévue de JWT pour l’authentification

Prévention des injections SQL via requêtes préparées (en cours de généralisation)

### Améliorations futures
Intégration complète de JWT pour sécuriser les sessions

Mise en production sur un serveur distant

Interface d’administration

Tests fonctionnels automatisés

### Licence
Projet personnel réalisé à des fins d'apprentissage. Libre d’utilisation et de modification.









