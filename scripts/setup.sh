#!/usr/bin/env bash
set -euo pipefail

PROJECT_DIR=${PROJECT_DIR:-/var/www/html}
SKELETON_VERSION=${LARAVEL_VERSION:-"^10.0"}

if [ ! -d "$PROJECT_DIR" ]; then
  echo "Project directory $PROJECT_DIR does not exist" >&2
  exit 1
fi

cd "$PROJECT_DIR"

if [ ! -f artisan ]; then
  echo "Creating Laravel skeleton..."
  composer create-project --no-interaction --prefer-dist laravel/laravel="${SKELETON_VERSION}" .
fi

echo "Installing API tooling dependencies..."
composer require --no-interaction darkaonline/l5-swagger:^8.5 guzzlehttp/guzzle:^7.8

php artisan vendor:publish --provider="L5Swagger\\L5SwaggerServiceProvider" --tag=config --force
php artisan vendor:publish --provider="L5Swagger\\L5SwaggerServiceProvider" --tag=views --force

OVERLAY_DIR=${PROJECT_OVERLAY:-/opt/project-overlay}

if [ -d "$OVERLAY_DIR" ]; then
  echo "Applying project overlay from $OVERLAY_DIR..."
  rsync -a "$OVERLAY_DIR"/ "$PROJECT_DIR"/
fi
php artisan key:generate --ansi
php artisan config:clear

mkdir -p storage/logs
php artisan storage:link || true

php artisan migrate --force || true
php artisan l5-swagger:generate || true
