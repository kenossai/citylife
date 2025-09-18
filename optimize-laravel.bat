@echo off
REM Laravel Performance Optimization Script for Development
REM Run this script whenever you want to optimize your Laravel app

echo Optimizing Laravel Application...

echo.
echo [1/6] Clearing all caches...
php artisan optimize:clear

echo.
echo [2/6] Optimizing Composer autoloader...
composer dump-autoload --optimize --no-dev

echo.
echo [3/6] Caching configurations...
php artisan config:cache

echo.
echo [4/6] Caching routes...
php artisan route:cache

echo.
echo [5/6] Caching views...
php artisan view:cache

echo.
echo [6/6] Running Filament optimization...
php artisan filament:optimize

echo.
echo ✅ Laravel application optimized successfully!
echo.
echo Additional Performance Tips:
echo - Use Redis for caching and sessions in production
echo - Enable PHP OpCache in your php.ini
echo - Consider using Laravel Octane for production
echo - Monitor database queries for N+1 problems
echo.
pause
