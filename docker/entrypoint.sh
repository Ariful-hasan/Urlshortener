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


if [ "$APP_ENV" != "testing" ]; then
    # 2. Wait for Database
    echo "Waiting for database connection..."
    until php -r "try { new PDO(getenv('DB_CONNECTION') . ':host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT') . ';dbname=' . getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD')); exit(0); } catch (Exception \$e) { exit(1); }"; do
    echo "Database is unavailable - sleeping..."
    sleep 2
    done
    echo "Database is ready!"

    # TODO redis is ready check can be added here.
fi

echo "Running migrations..."
php artisan migrate --force

exec "$@"
