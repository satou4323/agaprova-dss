FROM php:8.2-cli

# Instalar extensiones necesarias para MySQL e imagenes
RUN apt-get update && apt-get install -y \
    libpng-dev libzip-dev zip unzip \
    && docker-php-ext-install pdo pdo_mysql mysqli zip gd

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar el proyecto
COPY . /var/www/html/
WORKDIR /var/www/html

# Instalar dependencias PHP
RUN composer install --no-dev --optimize-autoloader || true

# Permisos
RUN chmod -R 755 /var/www/html

# Servidor embebido de PHP con router (sin Apache = sin error de MPM)
# Railway inyecta el puerto en la variable PORT
EXPOSE 8080
CMD php -S 0.0.0.0:${PORT:-8080} -t /var/www/html /var/www/html/router.php
