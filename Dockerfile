# Sorbetería Sarita · catálogo de helados en Laravel (stack clásico: PHP + Apache + MySQL)
# Kubo construye esta imagen en cada git push y la despliega sin root (Pod Security "restricted").

# 1) Dependencias de Composer, sin las de desarrollo
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --no-scripts --no-autoloader --ignore-platform-reqs
COPY . .
RUN composer dump-autoload --no-dev --optimize --classmap-authoritative

# 2) PHP 8.4 + Apache
FROM php:8.4-apache
RUN docker-php-ext-install pdo_mysql opcache \
 && a2enmod rewrite \
 && mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
 && sed -i 's/^expose_php = Off/expose_php = On/' "$PHP_INI_DIR/php.ini" \
 && printf 'upload_max_filesize = 3M\npost_max_size = 4M\n' > "$PHP_INI_DIR/conf.d/fotos.ini"
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf
COPY docker/mpm.conf /etc/apache2/mods-available/mpm_prefork.conf
COPY docker/iniciar.sh /usr/local/bin/iniciar
# Apache escucha en 8080: sin root no se puede abrir el puerto 80
RUN sed -i 's/^Listen 80$/Listen 8080/' /etc/apache2/ports.conf \
 && chown -R www-data:www-data /var/run/apache2 /var/lock/apache2 /var/log/apache2

WORKDIR /var/www/html
COPY --from=vendor --chown=www-data:www-data /app /var/www/html

# Configuración que no es secreta. Lo secreto (APP_KEY) lo da el cliente en el panel de Kubo
# y la conexión a MySQL (DB_*) la inyecta Kubo: nada de eso está en el repositorio.
ENV APP_ENV=production APP_DEBUG=false LOG_CHANNEL=stderr \
    SESSION_DRIVER=database SESSION_COOKIE=laravel_session CACHE_STORE=database \
    APP_LOCALE=es

USER 33
EXPOSE 8080
CMD ["iniciar"]
