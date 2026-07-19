#!/usr/bin/env sh
set -e

if [ -f /var/www/html/composer.json ] && [ ! -d /var/www/html/vendor ]; then
  composer install --no-interaction --prefer-dist
fi

php artisan key:generate --force >/dev/null 2>&1 || true
php artisan storage:link >/dev/null 2>&1 || true

exec "$@"
