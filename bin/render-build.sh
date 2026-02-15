#!/usr/bin/env bash
# Exit on error
set -o errexit

npm install
npm run build

composer install --no-dev --optimize-autoloader

php artisan config:cache
php artisan route:cache
php artisan view:cache

php artisan migrate --force
