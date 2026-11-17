FROM php:8.3-apache

# 1. Instalar extensiones necesarias de PHP (SQLite, Zip, etc.)
RUN apt-get update && apt-get install -y \
    libzip-dev \
    libsqlite3-dev \
    zip \
    unzip \
    && docker-php-ext-install zip pdo pdo_sqlite

# 2. Habilitar mod_rewrite de Apache
RUN a2enmod rewrite

# 3. Apuntar Apache a la carpeta /public de Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 4. Copiar tu proyecto al servidor
WORKDIR /var/www/html
COPY . .

# 5. Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-interaction --optimize-autoloader --no-dev

# 5.5 Instalar Node.js y compilar CSS/JS (Vite)
RUN apt-get update && apt-get install -y nodejs npm
RUN npm install && npm run build

# 6. Dar permisos absolutos a las carpetas que Laravel necesita escribir
RUN chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

# 7. Script de inicio para migrar y arrancar
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