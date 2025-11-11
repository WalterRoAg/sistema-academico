# Usamos la imagen oficial de PHP 8.2
FROM php:8.2-fpm

# Directorio de trabajo
WORKDIR /var/www/html

# Instalamos dependencias del sistema (postgres, GD, y AHORA ZIP)
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

# Instalamos las extensiones de PHP (pdo_pgsql, GD, y AHORA ZIP)
# 1. Configuramos GD con soporte para fuentes y imágenes
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
# 2. Instalamos GD, pdo_pgsql, y zip
RUN docker-php-ext-install -j$(nproc) gd pdo pdo_pgsql zip

# Instalamos Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiamos solo los archivos de dependencias primero
COPY composer.json composer.lock ./

# Instalamos las dependencias de PHP

RUN composer install --no-dev --no-autoloader --no-scripts
# Copiamos el resto de la aplicación
COPY . .

# Generamos el autoloader de clases
RUN composer dump-autoload --optimize

# Damos permisos a las carpetas de Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Exponemos el puerto y definimos el comando de inicio
EXPOSE 8000
CMD ["php", "artisan", "serve", "--host", "0.0.0.0", "--port", "8000"]
