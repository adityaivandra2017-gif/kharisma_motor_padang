#!/usr/bin/env bash
# Install dependensi PHP
composer install --no-dev --optimize-autoloader

# Jalankan optimasi Laravel 13
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Jalankan database migration (jika sudah ada database)
php artisan migrate --force
