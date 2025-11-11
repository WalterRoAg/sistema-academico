# Usamos una imagen de PHP 8.2 (puedes cambiar la versión si usas otra)
FROM php:8.2-fpm

# Directorio de trabajo
WORKDIR /var/www/html

# Instalamos dependencias del sistema (para postgres)
RUN apt-get update && apt-get install -y \
    zip \
    unzip \
    git \
    curl \
    libpq-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalamos la extensión de PHP para PostgreSQL
RUN docker-php-ext-install pdo pdo_pgsql

# Instalamos Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiamos solo los archivos de dependencias primero
COPY composer.json composer.lock ./

# Instalamos las dependencias de PHP
RUN composer install --no-dev --optimize-autoloader

# Copiamos el resto de la aplicación
COPY . .

# Generamos el autoloader de clases
RUN composer dump-autoload --optimize

# Damos permisos a las carpetas de Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Exponemos el puerto que usa `artisan serve`

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host", "0.0.0.0", "--port", "8000"]

