#!/bin/bash

# Laravel Cloud Build Script
# This runs automatically after code deployment

echo "🚀 Starting Laravel Cloud build process..."

# Install dependencies
echo "📦 Installing Composer dependencies..."
composer install --optimize-autoloader --no-dev --prefer-dist

# Create required directories with proper permissions
echo "📁 Creating storage directories..."
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p bootstrap/cache

# Set permissions
chmod -R 775 storage bootstrap/cache

# Clear old caches first
echo "🧹 Clearing old caches..."
php artisan cache:clear || true
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

# Run migrations
echo "🗄️ Running database migrations..."
php artisan migrate --force --no-interaction

# Create storage link if not exists
echo "🔗 Linking storage..."
php artisan storage:link || true

# Cache config for production
echo "⚙️ Optimizing configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Restart queue workers
echo "🔄 Restarting queue workers..."
php artisan queue:restart

echo "✅ Build completed successfully!"
