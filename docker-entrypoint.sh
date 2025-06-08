#!/bin/bash

echo "Iniciando aplicación Laravel..."

echo "Esperando a que MySQL esté listo..."
while ! nc -z mysql 3306; do
    echo "MySQL no está disponible - esperando..."
    sleep 3
done

echo "MySQL está listo!"

if [ ! -d "vendor" ]; then
    echo "Instalando dependencias de Composer..."
    composer install --no-dev --optimize-autoloader
fi

if [ ! -f ".env" ]; then
    echo "Copiando archivo .env..."
    cp .env.example .env
    php artisan key:generate
fi

echo "Ejecutando migraciones..."
php artisan migrate --force

echo "Ejecutando UsuariosTableSeeder..."
php artisan db:seed --class=UsuariosTableSeeder --force

echo "Limpiando cache..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

echo "Optimizando aplicación..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Aplicación lista!"

exec "$@"