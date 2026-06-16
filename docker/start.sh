#!/bin/sh

php artisan config:clear || true
php artisan cache:clear || true

php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

php artisan migrate --force || true

exec /usr/bin/supervisord -c /etc/supervisord.conf