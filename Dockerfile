# ETAPA 1: Compilar los estilos y JS con Node
FROM node:20-alpine AS frontend
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# ETAPA 2: Configurar PHP y Apache
FROM php:8.3-apache

# Instalar extensiones de PHP (SQLite, Zip)
RUN apt-get update && apt-get install -y \
    libzip-dev \
    libsqlite3-dev \
    zip \
    unzip \
    && docker-php-ext-install zip pdo pdo_sqlite

# Habilitar mod_rewrite
RUN a2enmod rewrite

# Apuntar Apache a /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Copiar el proyecto
WORKDIR /var/www/html
COPY . .

# Copiar los assets YA COMPILADOS desde la ETAPA 1
COPY --from=frontend /app/public/build ./public/build

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Dar permisos
RUN chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

# Script de inicio
RUN echo '#!/bin/bash\n\
if [ ! -f /var/www/html/database/database.sqlite ]; then\n\
    touch /var/www/html/database/database.sqlite\n\
    chmod 777 /var/www/html/database/database.sqlite\n\
fi\n\
php artisan optimize:clear\n\
php artisan migrate --force\n\
php artisan db:seed --force\n\
apache2-foreground' > /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

EXPOSE 80
CMD ["/usr/local/bin/start.sh"]