#!/bin/bash
echo "Starting deployment..."
php artisan down

# Clear caches BEFORE modifying packages
php artisan optimize:clear

git pull origin main
composer install --optimize-autoloader --no-dev
npm run build
php artisan migrate --force

# Recache everything with the new code
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan queue:restart

php artisan up
echo "Deployment complete!"
