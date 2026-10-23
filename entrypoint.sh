#!/bin/sh
set -e

echo "=== [1/5] Menunggu Koneksi Database MySQL (db:3306) ==="
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
    echo "=== [2/5] Membuat file .env dari .env.example ==="
    cp .env.example .env
fi

# Generate APP_KEY jika belum ada
if ! grep -q "^APP_KEY=base64:" .env; then
    echo "=== [3/5] Generating Application Key ==="
    php artisan key:generate --force
fi

# Jalankan Database Migration
echo "=== [4/5] Menjalankan Database Migrations ==="
php artisan migrate --force

# Cache Konfigurasi & Route untuk Production
echo "=== [5/5] Membuat Optimasi Cache Laravel ==="
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Pastikan Izin Akses Direktori
chown -R www-data:www-data /var/www/visitor-management-system/storage /var/www/visitor-management-system/bootstrap/cache
chmod -R 775 /var/www/visitor-management-system/storage /var/www/visitor-management-system/bootstrap/cache

echo "=== Container App Siap Digunakan! Memulai PHP-FPM... ==="
exec "$@"
