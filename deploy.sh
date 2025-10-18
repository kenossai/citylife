#!/bin/bash

# Railway Laravel deployment script
echo "🚀 Starting CityLife deployment..."

# Clear caches first
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Run database migrations
echo "📊 Running database migrations..."
php artisan migrate --force

# Seed database (only if tables are empty to avoid duplicates)
echo "🌱 Seeding database..."
php artisan db:seed --force

# Cache configuration for production
echo "⚡ Caching configuration..."
php artisan config:cache
php artisan route:cache  
php artisan view:cache

# Create storage link
echo "🔗 Creating storage link..."
php artisan storage:link

# Clear and warm up caches
php artisan optimize

echo "✅ CityLife deployment completed successfully!"
