# Use PHP 8.2 FPM Alpine for smaller size and security
FROM php:8.2-fpm-alpine

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    oniguruma-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Copy composer files first (for better caching)
COPY composer.json composer.lock ./

# Clear any existing autoloader files that might cause issues
RUN rm -rf vendor/ bootstrap/cache/*.php

# Install PHP dependencies without scripts first
RUN composer install --no-dev --no-interaction --prefer-dist --no-scripts --no-autoloader

# Copy package.json files
COPY package.json package-lock.json* ./

# Install Node.js dependencies
RUN npm ci --only=production

# Copy the rest of the application
COPY . .

# Now run composer with autoloader and scripts
RUN composer dump-autoload --optimize && \
    composer run-script post-autoload-dump

# Build frontend assets
RUN npm run build

# Create necessary directories and set permissions
RUN mkdir -p storage/logs storage/framework/{cache,sessions,views} bootstrap/cache && \
    chown -R www-data:www-data /var/www/html && \
    chmod -R 775 storage bootstrap/cache

# Remove development dependencies
RUN rm -rf node_modules package*.json

# Expose port 9000 for PHP-FPM
EXPOSE 9000

# Switch to www-data user
USER www-data

# Start PHP-FPM
CMD ["php-fpm"]
