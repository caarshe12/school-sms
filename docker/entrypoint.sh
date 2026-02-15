#!/bin/sh

# Exit on error
set -e

# Run migrations (force for production)
echo "Running migrations..."
php artisan migrate --force

# Create storage link for logo uploads
echo "Linking storage..."
php artisan storage:link || true

# Cache configuration
echo "Caching configuration..."
php artisan config:cache
php artisan route:cache || true
php artisan view:cache || true

# Start the application
echo "Starting application..."
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
