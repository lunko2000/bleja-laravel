# Use the official PHP image with Apache
FROM php:8.2-apache

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_mysql zip

# Enable Apache mod_rewrite for Laravel routing
RUN a2enmod rewrite

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . /var/www/html/

# Install Composer dependencies
RUN composer install --no-dev --prefer-dist

# Install Node.js and npm for frontend assets
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && npm install \
    && npm run build

# Optimize Laravel
RUN php artisan optimize

# Optional: List PHP modules to confirm pdo_mysql is installed
RUN php -m

# Debug database connection variables (will be empty during build, but useful for runtime logs)
RUN echo "DB_HOST=$DB_HOST" && echo "DB_USERNAME=$DB_USERNAME" && echo "DB_PASSWORD=$DB_PASSWORD" # Updated on 2025-03-07

# Create a script to check the database connection at runtime
RUN echo "<?php\n\
try {\n\
    \$pdo = new PDO('mysql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT') . ';dbname=' . getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));\n\
    echo 'Database connection successful\\n';\n\
} catch (PDOException \$e) {\n\
    file_put_contents('/var/www/html/storage/logs/db-error.log', 'Connection failed: ' . \$e->getMessage() . \"\\n\", FILE_APPEND);\n\
    exit(1);\n\
}\n\
" > /var/www/html/check-db.php

# Set permissions for Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Configure Apache to serve the Laravel public directory
COPY ./public/.htaccess /var/www/html/public/.htaccess
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf \
    && echo "<Directory /var/www/html/public>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>" >> /etc/apache2/apache2.conf \
    && echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Enable PHP error logging to stderr for deploy logs
RUN echo "php_flag display_errors on" >> /etc/apache2/conf-enabled/error-logging.conf \
    && echo "php_flag log_errors on" >> /etc/apache2/conf-enabled/error-logging.conf \
    && echo "php_value error_log /proc/self/fd/2" >> /etc/apache2/conf-enabled/error-logging.conf

# Expose the port
EXPOSE 80

# Start Apache with database check
CMD ["sh", "-c", "php /var/www/html/check-db.php && apache2-foreground"]