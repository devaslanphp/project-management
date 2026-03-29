#!/bin/bash
set -e

if [ ! -f .env ]; then
    cp .env.example .env
fi

if ! grep -q '^APP_KEY=base64:' .env; then
    php artisan key:generate --force --no-interaction
fi

php artisan storage:link || true
php artisan migrate --force --seed
php artisan optimize:clear
php artisan serve --host 0.0.0.0
