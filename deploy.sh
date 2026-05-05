#!/bin/bash
echo "Starting deployment..."

# Fix storage permissions
echo "Fixing storage permissions..."
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || chown -R nginx:nginx storage bootstrap/cache 2>/dev/null || chown -R apache:apache storage bootstrap/cache 2>/dev/null || echo "Warning: Could not change ownership. You may need to run this script with sudo or fix permissions manually."

php artisan down

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
