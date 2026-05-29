#!/bin/bash
set -e

# Ejecutar migraciones si la BD está disponible
echo "Attempting to run migrations..."
php artisan migrate --force || echo "Migration skipped (DB not available yet)"

# Limpiar y cachear configuración
php artisan config:cache
php artisan cache:clear

echo "Application initialized successfully"
