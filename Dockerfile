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

# Install Node.js dependencies (including dev dependencies for build)
RUN npm ci

# Copy the rest of the application
COPY . .

# Now run composer with autoloader and scripts
RUN composer dump-autoload --optimize && \
    composer run-script post-autoload-dump

# Run Filament upgrade to ensure assets are published
RUN php artisan filament:upgrade --force || true

# Build frontend assets with error handling
COPY build-frontend.sh ./
RUN chmod +x build-frontend.sh && ./build-frontend.sh

# Create necessary directories and set permissions
RUN mkdir -p storage/logs storage/framework/{cache,sessions,views} bootstrap/cache storage/app/public && \
    mkdir -p storage/framework/cache/data && \
    chown -R www-data:www-data /var/www/html && \
    chmod -R 775 storage bootstrap/cache

# Create .env file from example if it doesn't exist
RUN if [ ! -f .env ] && [ -f .env.example ]; then cp .env.example .env; fi

# Generate application key (will be overridden by environment variables)
RUN php artisan key:generate --force || true

# Create storage link
RUN php artisan storage:link --force || true

# Clear any potential cache issues
RUN php artisan config:clear || true && \
    php artisan route:clear || true && \
    php artisan view:clear || true

# Copy post-deployment script
COPY post-deploy.sh ./
RUN chmod +x post-deploy.sh

# Remove development dependencies
RUN rm -rf node_modules package*.json

# Expose port 9000 for PHP-FPM
EXPOSE 9000

# Switch to www-data user
USER www-data

# Start PHP-FPM
CMD ["php-fpm"]
