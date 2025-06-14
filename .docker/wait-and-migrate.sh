#!/bin/sh
echo "⏳ En attente de la base de données ($DB_HOST)..."
until php -r "new PDO('mysql:host=$DB_HOST;dbname=$DB_NAME', '$DB_USER', '$DB_PASS');"; do
  sleep 1
done

echo "✅ Base de données prête. Exécution de la migration..."
php /var/www/html/migration.php