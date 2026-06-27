#!/usr/bin/env sh
set -eu

echo "Installing Composer dependencies..."
composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

echo "Ensuring public storage link..."
php artisan storage:link || true

echo "Running database migrations..."
php artisan migrate --force

echo "Clearing stale caches..."
php artisan optimize:clear

echo "Rebuilding Laravel caches..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Deployment complete."
