FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libpng-dev libzip-dev zip unzip \
    && docker-php-ext-install pdo pdo_mysql mysqli zip gd

# Forzar SOLO prefork: limpiar todos los MPM y reactivar uno solo
RUN rm -f /etc/apache2/mods-enabled/mpm_*.load /etc/apache2/mods-enabled/mpm_*.conf \
    && ln -sf /etc/apache2/mods-available/mpm_prefork.load /etc/apache2/mods-enabled/mpm_prefork.load \
    && ln -sf /etc/apache2/mods-available/mpm_prefork.conf /etc/apache2/mods-enabled/mpm_prefork.conf \
    && a2enmod rewrite

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . /var/www/html/
WORKDIR /var/www/html

RUN composer install --no-dev --optimize-autoloader || true

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

EXPOSE 80
