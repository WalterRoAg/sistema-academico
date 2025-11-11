# 1. Usamos la imagen oficial de PHP 8.2
FROM php:8.2-fpm

# 2. Directorio de trabajo
WORKDIR /var/www/html

# 3. Instalamos dependencias del sistema (postgres, GD, y ZIP)
RUN apt-get update && apt-get install -y \
    zip \
    unzip \
    git \
    curl \
    libpq-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 4. Instalamos las extensiones de PHP (pdo_pgsql, GD, y ZIP)
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install -j$(nproc) gd pdo pdo_pgsql zip

# 5. Instalamos Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 6. Copiamos solo los archivos de dependencias primero
COPY composer.json composer.lock ./

# 7. Instalamos las dependencias de PHP
RUN composer install --no-dev --no-autoloader --no-scripts

# 8. Copiamos el resto de la aplicación
COPY . .

# 9. Copiamos el nuevo script de inicio
COPY start.sh .

# 10. Generamos el autoloader
RUN composer dump-autoload --optimize

# 11. Damos permisos
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod +x ./start.sh # <-- ¡Hacemos que el script sea ejecutable!

# 12. Exponemos el puerto
EXPOSE 8000

# 13. COMANDO DE INICIO (Simplemente ejecuta el script)
CMD ["./start.sh"]
