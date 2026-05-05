#!/bin/bash
echo "Starting deployment..."

# Clear cached files that may have stale references
echo "Clearing bootstrap cache..."
rm -f bootstrap/cache/*.php

# Note: If you see permission errors, run these commands on the server once:
# sudo chown -R www-data:www-data storage bootstrap/cache
# sudo chmod -R 755 storage bootstrap/cache

php artisan down 2>/dev/null || true

# Clear caches BEFORE modifying packages
php artisan optimize:clear

git pull origin dev
composer install --optimize-autoloader --no-dev
rm -rf public/build
npm ci
npm run build
php artisan migrate --force

# Recreate storage symlink (in case of new domain folders)
rm -f public/storage
php artisan storage:link

# Recache everything with the new code
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
php artisan queue:restart

php artisan up
echo "Deployment complete!"
