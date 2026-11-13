#!/bin/sh
set -e

echo "[C28 Entrypoint] Optimizing Laravel configuration, routes, and Blade views..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

if [ "${RUN_MIGRATIONS:-false}" = "true" ]; then
    echo "[C28 Entrypoint] Running production database migrations..."
    php artisan migrate --force
fi

echo "[C28 Entrypoint] Starting Supervisor (PHP-FPM 8.2 + Nginx on :8080)..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf

