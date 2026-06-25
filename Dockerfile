FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libpng-dev libzip-dev zip unzip \
    && docker-php-ext-install pdo pdo_mysql mysqli zip gd

# Desactivar MPM event y activar prefork (evita el error "More than one MPM loaded")
RUN a2dismod mpm_event mpm_worker 2>/dev/null || true \
    && a2enmod mpm_prefork \
    && a2enmod rewrite

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . /var/www/html/
WORKDIR /var/www/html

RUN composer install --no-dev --optimize-autoloader || true

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

EXPOSE 80
