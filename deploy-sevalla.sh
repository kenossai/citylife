#!/bin/bash

# Sevalla deployment script for Laravel
echo "🚀 Starting CityLife Sevalla deployment..."

# Clear any existing caches
echo "🧹 Clearing caches..."
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true
php artisan cache:clear || true

# Install composer dependencies (retry logic for network issues)
echo "📦 Installing Composer dependencies..."
for i in {1..3}; do
    if composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-scripts; then
        echo "✅ Composer install successful"
        break
    else
        echo "❌ Composer install failed (attempt $i/3)"
        if [ $i -eq 3 ]; then
            echo "💥 Composer install failed after 3 attempts"
            exit 1
        fi
        sleep 5
    fi
done

# Run composer scripts separately
echo "🔧 Running Composer scripts..."
composer run-script post-autoload-dump || true

# Install Node.js dependencies (including dev dependencies for build)
echo "📦 Installing Node.js dependencies..."
npm ci

# Run Filament upgrade to ensure assets are ready
echo "🔧 Running Filament upgrade..."
php artisan filament:upgrade --force || true

# Build frontend assets with robust error handling
echo "🏗️ Building frontend assets..."
if [ -f "build-frontend.sh" ]; then
    chmod +x build-frontend.sh
    ./build-frontend.sh
else
    npm run build
fi

# Create necessary directories
echo "📁 Creating storage directories..."
mkdir -p storage/logs
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p bootstrap/cache

# Set proper permissions
echo "🔐 Setting permissions..."
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Run Laravel optimizations
echo "⚡ Running Laravel optimizations..."
php artisan migrate --force || true
php artisan db:seed --force || true
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link || true

echo "✅ CityLife deployment completed successfully!"
