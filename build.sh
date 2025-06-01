#!/bin/bash

echo " Building Laravel Gaming Store for Render..."

# Install PHP MongoDB extension
echo "Installing MongoDB extension..."
pecl install mongodb
echo "extension=mongodb.so" >> /opt/php/etc/php.ini

# Install Composer dependencies
echo "Installing Composer dependencies..."
composer install --optimize-autoloader --no-dev --no-interaction

# Install NPM dependencies and build assets
echo "Installing NPM dependencies..."
npm install

echo "Building frontend assets..."
npm run build

# Set up Laravel
echo "Setting up Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
echo "Setting permissions..."
chmod -R 755 storage bootstrap/cache

echo "Build completed successfully!"