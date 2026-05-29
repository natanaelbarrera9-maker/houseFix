#!/bin/bash
set -e

echo "Running Laravel deployment steps..."

# Cache configuration
php artisan config:cache

# Run migrations
php artisan migrate --force

# Create storage link
php artisan storage:link || true

echo "Deployment completed successfully!"

