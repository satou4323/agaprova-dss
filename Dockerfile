FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libpng-dev libzip-dev zip unzip \
    && docker-php-ext-install pdo pdo_mysql mysqli zip gd

RUN a2enmod rewrite

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . /var/www/html/
WORKDIR /var/www/html

RUN composer install --no-dev --optimize-autoloader || true

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

EXPOSE 80