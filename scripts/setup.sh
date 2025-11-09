#!/usr/bin/env bash
set -euo pipefail

PROJECT_DIR=${PROJECT_DIR:-/var/www/html}

if [ ! -d "$PROJECT_DIR" ]; then
  echo "Project directory $PROJECT_DIR does not exist" >&2
  exit 1
fi

cd "$PROJECT_DIR"

if [ ! -f composer.json ]; then
  echo "composer.json not found in $PROJECT_DIR; please ensure the Laravel project is present." >&2
  exit 1
fi

if [ ! -d vendor ]; then
  echo "Installing Composer dependencies..."
  composer install --no-interaction --prefer-dist
fi

if [ ! -f config/l5-swagger.php ]; then
  php artisan vendor:publish --provider="L5Swagger\\L5SwaggerServiceProvider" --tag=config --force
fi

if [ ! -d resources/views/vendor/l5-swagger ]; then
  php artisan vendor:publish --provider="L5Swagger\\L5SwaggerServiceProvider" --tag=views --force
fi

php artisan key:generate --ansi || true
php artisan config:clear || true

mkdir -p storage/logs
php artisan storage:link || true

php artisan migrate --force || true
php artisan l5-swagger:generate || true
