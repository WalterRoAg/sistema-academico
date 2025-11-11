#!/bin/sh

# 1. Corre la migración para crear las tablas
php artisan migrate:fresh --seed

# 2. Inicia el servidor
php artisan serve --host 0.0.0.0 --port 8000