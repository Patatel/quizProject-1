# Utilisez une image officielle de PHP comme base
FROM php:8.0-apache

# Installez les extensions PHP nécessaires
RUN docker-php-ext-install pdo pdo_mysql

# Installez Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiez les fichiers composer.json et composer.lock
COPY composer.json composer.lock /var/www/html/

# Installez les dépendances du projet
RUN composer install --no-dev --optimize-autoloader

# Copiez le reste de votre application dans le conteneur
COPY . /var/www/html/

# Activez le module Apache rewrite
RUN a2enmod rewrite

# Configurez les permissions
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html

COPY .docker/wait-and-migrate.sh /usr/local/bin/wait-and-migrate.sh
RUN chmod +x /usr/local/bin/wait-and-migrate.sh

RUN echo "display_errors=On" >> /usr/local/etc/php/conf.d/docker-php.ini

# Configurez Apache pour servir votre application
COPY .docker/apache.conf /etc/apache2/sites-available/000-default.conf

# Définissez le répertoire de travail
WORKDIR /var/www/html