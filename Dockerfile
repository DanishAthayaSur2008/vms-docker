# =========================================================
# STAGE 1: Build Frontend Assets (Node.js 20 + Vite 7)
# =========================================================
FROM node:20-alpine AS frontend-builder
WORKDIR /var/www/visitor-management-system

COPY package*.json vite.config.js ./
COPY resources/ ./resources/
COPY public/ ./public/

RUN npm ci || npm install
RUN npm run build

# =========================================================
# STAGE 2: Composer Dependencies (PHP Packages)
# =========================================================
FROM composer:2 AS composer-builder
WORKDIR /var/www/visitor-management-system

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# =========================================================
# STAGE 3: Application Runtime (PHP-FPM 8.3)
# =========================================================
FROM php:8.3-fpm

WORKDIR /var/www/visitor-management-system

ENV COMPOSER_ALLOW_SUPERUSER=1

# Install System Dependencies & Extension PHP Wajib
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    libicu-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Ambil binary Composer dari image resmi (tanpa download dari getcomposer.org)
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

# Copy Source Code Aplikasi
COPY . .

# Copy Vendor PHP dari Composer Builder
COPY --from=composer-builder /var/www/visitor-management-system/vendor ./vendor

# Copy Hasil Compile Assets dari Frontend Builder
COPY --from=frontend-builder /var/www/visitor-management-system/public/build ./public/build

# Optimasi Composer Autoloader (tanpa scripts, karena butuh DB)
RUN composer dump-autoload --optimize --no-dev --no-scripts

# Simpan salinan public di luar volume (volume app_public tidak ikut update saat rebuild)
RUN cp -a /var/www/visitor-management-system/public /var/www/public-dist

# Setup Executable Script Entrypoint (sekaligus buang CRLF kalau diedit di Windows)
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN sed -i 's/\r$//' /usr/local/bin/entrypoint.sh \
    && chmod +x /usr/local/bin/entrypoint.sh

# Buat folder storage & cache lalu atur izin akses
RUN mkdir -p /var/www/visitor-management-system/storage/framework/views \
             /var/www/visitor-management-system/storage/framework/cache/data \
             /var/www/visitor-management-system/storage/framework/sessions \
             /var/www/visitor-management-system/storage/logs \
             /var/www/visitor-management-system/storage/app/public \
             /var/www/visitor-management-system/bootstrap/cache \
    && chown -R www-data:www-data /var/www/visitor-management-system/storage /var/www/visitor-management-system/bootstrap/cache \
    && chmod -R 775 /var/www/visitor-management-system/storage /var/www/visitor-management-system/bootstrap/cache

EXPOSE 9000

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["php-fpm"]
