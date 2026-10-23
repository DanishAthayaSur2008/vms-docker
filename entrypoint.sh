#!/bin/sh
set -e

echo "=== [1/6] Menunggu Koneksi Database MySQL (db:3306) ==="
until php -r "
  try {
    \$pdo = new PDO('mysql:host='.getenv('DB_HOST').';port='.getenv('DB_PORT').';dbname='.getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));
    exit(0);
  } catch (Throwable \$e) {
    exit(1);
  }
"; do
    echo "Koneksi database belum siap, menunggu 2 detik..."
    sleep 2
done

echo "Database MySQL Berhasil Terhubung!"

# Salin .env jika belum ada
if [ ! -f .env ]; then
    echo "=== [2/6] Membuat file .env dari .env.example ==="
    cp .env.example .env
fi

# Memastikan seluruh direktori internal framework dibuat sebelum artisan command dipanggil
echo "=== [3/6] Memastikan Folder Storage & Framework Terbentuk ==="
# Sinkronkan isi public dari image (volume app_public tidak otomatis update saat rebuild)
cp -a /var/www/public-dist/. public/

mkdir -p storage/framework/views \
         storage/framework/cache/data \
         storage/framework/sessions \
         storage/logs \
         storage/app/public \
         bootstrap/cache

# Set izin awal folder storage & cache
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Buang cache package/service lama (bawaan dev, mereferensikan Pail dkk) lalu buat ulang tanpa dev
echo "=== [4/6] Reset Cache Package & Package Discovery ==="
rm -f bootstrap/cache/packages.php \
      bootstrap/cache/services.php \
      bootstrap/cache/config.php \
      bootstrap/cache/routes-*.php
php artisan package:discover --ansi

# Generate APP_KEY jika belum ada (di environment maupun di file .env)
if [ -z "$APP_KEY" ] && ! grep -q "^APP_KEY=base64:" .env; then
    echo "=== APP_KEY kosong, generating Application Key ==="
    php artisan key:generate --force
fi

# Storage link, Database Migration & Seeding (sekali saja)
echo "=== [5/6] Storage Link, Migrations & Seeding ==="
php artisan storage:link --force
php artisan migrate --force

if [ ! -f storage/.seeded ]; then
    echo "Menjalankan DatabaseSeeder (pertama kali)..."
    php artisan db:seed --class=DatabaseSeeder --force
    touch storage/.seeded
else
    echo "Seeding dilewati (sudah pernah dijalankan)."
fi

# Cache Konfigurasi, Route, dan View untuk Production
echo "=== [6/6] Membuat Optimasi Cache Laravel ==="
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Re-apply Izin Akses Direktori ke user www-data untuk file hasil caching/log baru
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

echo "=== Container App Siap Digunakan! Memulai PHP-FPM... ==="
exec "$@"
