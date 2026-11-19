#!/bin/sh
# Entrypoint container "app": siapkan folder, tunggu DB, migrate, seed (sekali), cache, jalankan php-fpm
set -eu

cd /var/www/html

log() { echo "[entrypoint] $*"; }
artisan() { gosu www-data php artisan "$@"; }

# 1. APP_KEY wajib ada
if [ -z "${APP_KEY:-}" ]; then
    log "ERROR: APP_KEY kosong. Isi APP_KEY di file .env lalu jalankan ulang."
    exit 1
fi

# 2. Folder yang harus writable oleh php-fpm (www-data)
mkdir -p \
    storage/app/public/visitors \
    storage/app/private \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# 3. Tunggu database siap
log "Menunggu database ${DB_HOST:-db}:${DB_PORT:-3306} ..."
tries=0
while ! err=$(php -r '
try {
    new PDO(
        sprintf("mysql:host=%s;port=%s;dbname=%s", getenv("DB_HOST"), getenv("DB_PORT") ?: "3306", getenv("DB_DATABASE")),
        getenv("DB_USERNAME"),
        getenv("DB_PASSWORD"),
        [PDO::ATTR_TIMEOUT => 3]
    );
    exit(0);
} catch (Throwable $e) {
    fwrite(STDERR, $e->getMessage() . PHP_EOL);
    exit(1);
}
' 2>&1); do
    tries=$((tries + 1))
    if [ "$tries" -ge 40 ]; then
        log "ERROR: database tidak bisa dihubungi: $err"
        exit 1
    fi
    sleep 3
done
log "Database siap."

# 4. Discover package + migrate
artisan package:discover --ansi
artisan migrate --force --no-interaction

# 5. Seeder hanya jika tabel users masih kosong (supaya password admin tidak di-reset tiap restart)
USER_COUNT=$(php -r '
try {
    $pdo = new PDO(
        sprintf("mysql:host=%s;port=%s;dbname=%s", getenv("DB_HOST"), getenv("DB_PORT") ?: "3306", getenv("DB_DATABASE")),
        getenv("DB_USERNAME"),
        getenv("DB_PASSWORD")
    );
    echo (int) $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
} catch (Throwable $e) {
    echo -1;
}
' 2>/dev/null || echo -1)

if [ "$USER_COUNT" = "0" ]; then
    log "Tabel users kosong -> menjalankan seeder (admin awal)."
    artisan db:seed --force --no-interaction
else
    log "Seeder dilewati (jumlah user: $USER_COUNT)."
fi

# 6. Cache production (dilakukan di sini karena AppServiceProvider mengakses DB saat boot)
artisan config:cache || log "WARN: config:cache gagal"
artisan route:cache  || log "WARN: route:cache gagal"
artisan view:cache   || log "WARN: view:cache gagal"

log "Menjalankan: $*"
exec "$@"
