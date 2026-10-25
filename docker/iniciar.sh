#!/bin/sh
# Arranque del contenedor: espera a MySQL, aplica migraciones y levanta Apache
set -e
cd /var/www/html

echo "Esperando a MySQL en ${DB_HOST:-?}:${DB_PORT:-3306}…"
i=0
until php -r 'try { new PDO("mysql:host=".getenv("DB_HOST").";port=".(getenv("DB_PORT") ?: 3306), getenv("DB_USERNAME"), getenv("DB_PASSWORD")); } catch (Throwable $e) { exit(1); }'; do
  i=$((i + 1)); [ "$i" -lt 60 ] || { echo "MySQL no respondió en 2 minutos"; exit 1; }
  sleep 2
done

php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force
php artisan db:seed --force

exec apache2-foreground
