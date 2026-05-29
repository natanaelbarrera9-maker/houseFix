#!/bin/bash
set -e

echo "Running Laravel deployment steps..."

# Clear cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force

# Create storage link
php artisan storage:link || true

echo "Deployment completed successfully!"
