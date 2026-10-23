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

# Copy Source Code Aplikasi
COPY . .

# Copy Vendor PHP dari Composer Builder
COPY --from=composer-builder /var/www/visitor-management-system/vendor ./vendor

# Copy Hasil Compile Assets dari Frontend Builder
COPY --from=frontend-builder /var/www/visitor-management-system/public/build ./public/build

# Optimasi Composer Autoloader
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer dump-autoload --optimize --no-dev

# Setup Executable Script Entrypoint
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Atur Izin Akses Folder Storage & Cache
RUN chown -R www-data:www-data /var/www/visitor-management-system/storage /var/www/visitor-management-system/bootstrap/cache

EXPOSE 9000

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["php-fpm"]
