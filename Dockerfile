# Use the official PHP image as the base
FROM php:8.2-fpm

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

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install heroku-php-apache2 binary
RUN mkdir -p /app/vendor/bin && \
    curl -L https://github.com/heroku/heroku-buildpack-php/releases/download/v246/heroku-buildpack-php-v246.tar.gz | tar xz -C /app && \
    mv /app/bin/heroku-php-apache2 /app/vendor/bin/heroku-php-apache2

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

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

# Expose the port
EXPOSE 80

# Start the app with heroku-php-apache2
CMD ["/app/vendor/bin/heroku-php-apache2", "public/"]