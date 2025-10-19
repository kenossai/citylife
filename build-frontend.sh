#!/bin/bash

# Build script with error handling for Sevalla deployment
echo "🏗️ Starting frontend build process..."

# Ensure node_modules exists
if [ ! -d "node_modules" ]; then
    echo "📦 Installing Node.js dependencies..."
    npm ci
fi

# Try to build with error handling
echo "🔨 Building frontend assets..."
if npm run build; then
    echo "✅ Frontend build successful!"
else
    echo "❌ Frontend build failed, trying alternative approach..."

    # Alternative approach: build without optimizations
    echo "🔄 Attempting build without optimizations..."
    if npx vite build --mode development; then
        echo "✅ Alternative build successful!"
    else
        echo "💥 All build attempts failed!"
        exit 1
    fi
fi

echo "✅ Build process completed!"
