#!/bin/sh
set -e

# 1. Environment-specific optimizations
# If APP_ENV is production, cache everything. Otherwise, clear it.
if [ "$APP_ENV" = "production" ]; then
    echo "Running API optimizations for Production..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
else
    echo "Running in Development mode - clearing caches..."
    php artisan config:clear
    php artisan route:clear
fi

# TODO db is ready check can be added here.
# TODO redis is ready check can be added here.

echo "Running migrations..."
php artisan migrate --force

exec "$@"
