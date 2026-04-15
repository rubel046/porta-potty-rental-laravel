#!/bin/bash
echo "Starting deployment..."
php artisan down

# Clear caches BEFORE modifying packages
php artisan optimize:clear

git pull origin main
composer install --optimize-autoloader --no-dev
rm -rf public/build
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
php artisan queue:restart

php artisan up
echo "Deployment complete!"
