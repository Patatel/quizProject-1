#!/bin/sh

echo "⏳ En attente de la base de données (127.0.0.1)..."
until php -r "new PDO('mysql:host=127.0.0.1;dbname=quizproject_test', 'root', 'root');"; do
  sleep 1
done

echo "✅ Base de données prête. Exécution de la migration..."
php scripts/migration_test.php
