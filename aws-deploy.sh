#!/bin/bash

echo "Deploying Laravel Gaming Store to AWS EC2..."

# Update system
sudo apt update && sudo apt upgrade -y

# Install PHP 8.2 and extensions
sudo apt install -y software-properties-common
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install -y php8.2 php8.2-fpm php8.2-cli php8.2-mysql php8.2-xml php8.2-curl php8.2-zip php8.2-mbstring php8.2-bcmath php8.2-gd

# Install MongoDB PHP extension
sudo apt install -y php8.2-dev php8.2-pear
sudo pecl install mongodb
echo "extension=mongodb.so" | sudo tee -a /etc/php/8.2/cli/php.ini
echo "extension=mongodb.so" | sudo tee -a /etc/php/8.2/fpm/php.ini

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js 18
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install -y nodejs

# Install Nginx
sudo apt install -y nginx

# Install Git
sudo apt install -y git

# Clone repository
cd /var/www
sudo git clone https://github.com/cloudO9/laravel-ecommerce.git
sudo chown -R www-data:www-data laravel-ecommerce
cd laravel-ecommerce

# Install dependencies
sudo -u www-data composer install --optimize-autoloader --no-dev
sudo -u www-data npm install
sudo -u www-data npm run build

# Set up environment
sudo -u www-data cp .env.example .env
sudo -u www-data php artisan key:generate

# Set permissions
sudo chmod -R 755 storage bootstrap/cache
sudo -u www-data php artisan storage:link

# Configure Nginx
sudo tee /etc/nginx/sites-available/laravel > /dev/null <<EOF
server {
    listen 80;
    server_name _;
    root /var/www/laravel-ecommerce/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
EOF

# Enable site
sudo ln -sf /etc/nginx/sites-available/laravel /etc/nginx/sites-enabled/
sudo rm -f /etc/nginx/sites-enabled/default

# Start services
sudo systemctl restart nginx
sudo systemctl restart php8.2-fpm
sudo systemctl enable nginx
sudo systemctl enable php8.2-fpm

echo "Laravel Gaming Store deployed successfully!"
echo "Your app is now live!"
echo "Don't forget to configure your .env file with MongoDB Atlas connection"