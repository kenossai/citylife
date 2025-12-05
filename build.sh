#!/bin/bash

# Laravel Cloud Build Script
# This runs automatically after code deployment

echo "🚀 Starting Laravel Cloud build process..."

# Install dependencies
echo "📦 Installing Composer dependencies..."
composer install --optimize-autoloader --no-dev --prefer-dist

# Clear and cache config
echo "⚙️ Optimizing configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Run migrations
echo "🗄️ Running database migrations..."
php artisan migrate --force --no-interaction

# Create storage link if not exists
echo "🔗 Linking storage..."
php artisan storage:link

# Clear old caches
echo "🧹 Clearing old caches..."
php artisan cache:clear
php artisan queue:restart

echo "✅ Build completed successfully!"
