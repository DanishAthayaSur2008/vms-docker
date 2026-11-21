# =====================================================================
# Visitor Management System - Laravel 12 + Vite (Tailwind 4)
#   target "app" : PHP 8.4-FPM (backend + hasil build frontend)
#   target "web" : Nginx HTTP (file statis + FastCGI ke php-fpm)
#   SSL/TLS di-terminate oleh Nginx VM 1 (SERV-REV-PROX), bukan di sini
# =====================================================================

# ---------- base: PHP-FPM + ekstensi ----------
FROM php:8.4-fpm AS base

COPY --from=mlocati/php-extension-installer:latest /usr/bin/install-php-extensions /usr/local/bin/

RUN apt-get update \
 && apt-get install -y --no-install-recommends gosu unzip \
 && rm -rf /var/lib/apt/lists/* \
 && install-php-extensions pdo_mysql intl zip gd bcmath exif opcache

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
 && printf '%s\n' \
      'expose_php = Off' \
      'upload_max_filesize = 8M' \
      'post_max_size = 10M' \
      'memory_limit = 256M' \
      'max_execution_time = 60' \
      'date.timezone = Asia/Jakarta' \
      'opcache.enable = 1' \
      'opcache.memory_consumption = 128' \
      'opcache.max_accelerated_files = 20000' \
      'opcache.validate_timestamps = 0' \
    > "$PHP_INI_DIR/conf.d/zz-app.ini" \
 && printf '%s\n' \
      '[www]' \
      'pm = dynamic' \
      'pm.max_children = 15' \
      'pm.start_servers = 4' \
      'pm.min_spare_servers = 2' \
      'pm.max_spare_servers = 6' \
      'pm.max_requests = 500' \
    > /usr/local/etc/php-fpm.d/zz-pool.conf

# ---------- vendor: composer install (tanpa dev) ----------
FROM base AS vendor
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_NO_INTERACTION=1
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --no-progress
COPY app ./app
COPY database ./database
RUN composer dump-autoload --no-dev --optimize --no-scripts

# ---------- assets: build Vite / Tailwind ----------
FROM node:22-bookworm-slim AS assets
WORKDIR /build
COPY package.json package-lock.json ./
RUN npm ci --no-audit --no-fund || npm install --no-audit --no-fund
COPY vite.config.js ./
COPY resources ./resources
COPY --from=vendor /app/vendor/laravel/framework/src/Illuminate/Pagination/resources/views ./vendor/laravel/framework/src/Illuminate/Pagination/resources/views
RUN npm run build \
 || (rm -rf node_modules package-lock.json \
     && npm install --no-audit --no-fund \
     && npm run build)

# ---------- app: php-fpm + kode aplikasi ----------
FROM base AS app
WORKDIR /var/www/html
COPY . .
COPY --from=vendor /app/vendor ./vendor
COPY --from=assets /build/public/build ./public/build

RUN mkdir -p storage/app/public storage/app/private \
             storage/framework/cache/data storage/framework/sessions storage/framework/views \
             storage/logs bootstrap/cache \
 && rm -f bootstrap/cache/*.php \
 && chown -R www-data:www-data storage bootstrap/cache

COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN sed -i 's/\r$//' /usr/local/bin/entrypoint.sh \
 && chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 9000
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["php-fpm"]

# ---------- web: nginx (HTTP internal, di belakang reverse proxy) ----------
FROM nginx:stable-alpine AS web
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY --from=app /var/www/html/public /var/www/html/public
EXPOSE 80
