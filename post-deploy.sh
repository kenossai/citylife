#!/bin/bash

# Laravel post-deployment setup script for Sevalla
echo "🔧 Running Laravel post-deployment configuration..."

# Create .env file if it doesn't exist
if [ ! -f .env ]; then
    echo "📝 Creating .env file from .env.example..."
    cp .env.example .env || echo "⚠️  .env.example not found, creating basic .env"
fi

# Generate application key if not set
echo "🔑 Generating application key..."
php artisan key:generate --force

# Create storage directories with proper permissions
echo "📁 Creating storage directories..."
mkdir -p storage/app/public
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p bootstrap/cache

# Set proper permissions
echo "🔐 Setting permissions..."
chmod -R 775 storage
chmod -R 775 bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache || true

# Create storage link
echo "🔗 Creating storage link..."
php artisan storage:link --force

# Clear all caches
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Run migrations (only if database is configured)
if [ ! -z "$DATABASE_URL" ] || [ ! -z "$DB_HOST" ]; then
    echo "📊 Running database migrations..."
    php artisan migrate --force || echo "⚠️  Database migration failed - check database connection"
    
    echo "🌱 Seeding database..."
    php artisan db:seed --force || echo "⚠️  Database seeding failed"
fi

# Cache configurations for production
echo "⚡ Caching configurations..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize application
echo "🚀 Optimizing application..."
php artisan optimize

echo "✅ Post-deployment setup completed!"
echo "🌐 Your Laravel application should now be ready!"

# Show important information
echo ""
echo "📋 Important Notes:"
echo "- Ensure APP_KEY is set in environment variables"
echo "- Configure database connection in environment variables"
echo "- Check that APP_URL matches your domain"
echo "- Verify file permissions are set correctly"