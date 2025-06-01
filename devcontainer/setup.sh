#!/bin/bash

echo " Setting up Laravel Gaming Store in Codespaces..."

# Update package lists
sudo apt-get update

# Install MongoDB
wget -qO - https://www.mongodb.org/static/pgp/server-7.0.asc | sudo apt-key add -
echo "deb [ arch=amd64,arm64 ] https://repo.mongodb.org/apt/ubuntu focal/mongodb-org/7.0 multiverse" | sudo tee /etc/apt/sources.list.d/mongodb-org-7.0.list
sudo apt-get update
sudo apt-get install -y mongodb-org

# Start MongoDB
sudo systemctl start mongod
sudo systemctl enable mongod

# Install PHP MongoDB extension
sudo pecl install mongodb
echo "extension=mongodb.so" | sudo tee -a /etc/php/8.2/cli/php.ini

# Install Composer dependencies
composer install

# Install NPM dependencies (if package.json exists)
if [ -f "package.json" ]; then
    npm install
fi

# Copy environment file if it doesn't exist
if [ ! -f ".env" ]; then
    cp .env.example .env
fi

# Generate application key
php artisan key:generate

# Set up environment for Codespaces
echo "" >> .env
echo "# Codespaces Configuration" >> .env
echo "APP_ENV=local" >> .env
echo "APP_DEBUG=true" >> .env
echo "DB_CONNECTION=mongodb" >> .env
echo "DB_HOST=127.0.0.1" >> .env
echo "DB_PORT=27017" >> .env
echo "DB_DATABASE=gaming_store" >> .env
echo "DB_USERNAME=" >> .env
echo "DB_PASSWORD=" >> .env

# Set permissions
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Create storage link
php artisan storage:link

# Build assets (if package.json exists)
if [ -f "package.json" ]; then
    npm run build
fi

echo " Setup complete! Your Laravel app is ready in Codespaces."
echo "Run 'php artisan serve --host=0.0.0.0 --port=8000' to start the server."