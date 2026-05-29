release: php artisan migrate --force && php artisan cache:clear && php artisan config:cache
web: php -S 0.0.0.0:${PORT:-8080} -t public
