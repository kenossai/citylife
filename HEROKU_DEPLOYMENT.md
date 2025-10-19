# Heroku Deployment Guide for CityLife Laravel App

## Prerequisites
1. Heroku CLI installed
2. Git repository connected to Heroku
3. Database addon configured (ClearDB or JawsDB for MySQL)

## Required Files Created
- `Procfile` - Tells Heroku how to run your Laravel app
- `app.json` - Heroku app configuration

## Deployment Steps

### 1. Login to Heroku
```bash
heroku login
```

### 2. Create Heroku App (if not already created)
```bash
heroku create your-app-name
```

### 3. Set PHP Version and Buildpack
```bash
heroku config:set PHP_VERSION=8.2
heroku buildpacks:set heroku/php
```

### 4. Configure Environment Variables
```bash
# Laravel App Key (generate new one for production)
heroku config:set APP_KEY=$(php artisan key:generate --show)

# Laravel Environment
heroku config:set APP_ENV=production
heroku config:set APP_DEBUG=false
heroku config:set APP_URL=https://your-app-name.herokuapp.com

# Database (after adding ClearDB or JawsDB addon)
heroku addons:create cleardb:ignite
# Then copy the CLEARDB_DATABASE_URL and parse it for these vars:
heroku config:set DB_CONNECTION=mysql
heroku config:set DB_HOST=your-db-host
heroku config:set DB_DATABASE=your-db-name
heroku config:set DB_USERNAME=your-db-user
heroku config:set DB_PASSWORD=your-db-password

# Other Laravel configs
heroku config:set LOG_CHANNEL=errorlog
heroku config:set SESSION_DRIVER=database
heroku config:set CACHE_STORE=database
heroku config:set QUEUE_CONNECTION=database
```

### 5. Deploy
```bash
git add .
git commit -m "Add Heroku configuration"
git push heroku master
```

### 6. Run Migrations
```bash
heroku run php artisan migrate --force
heroku run php artisan db:seed --force
```

## Common Issues and Fixes

### "Forbidden" Error
- Usually caused by missing Procfile or wrong document root
- Make sure Procfile points to `public/` directory
- Ensure Laravel's `public/index.php` exists

### 500 Internal Server Error
- Check Heroku logs: `heroku logs --tail`
- Usually missing APP_KEY or database issues
- Run: `heroku config:set APP_KEY=$(php artisan key:generate --show)`

### Database Errors
- Make sure database addon is installed: `heroku addons`
- Parse CLEARDB_DATABASE_URL: `heroku config:get CLEARDB_DATABASE_URL`
- Set individual DB_* environment variables

### Asset Issues
- Laravel Mix/Vite assets need to be built: `npm run build`
- Commit built assets or use Heroku Node.js buildpack for building

## Useful Heroku Commands
```bash
# View logs
heroku logs --tail

# Access remote console
heroku run bash

# Run artisan commands
heroku run php artisan migrate
heroku run php artisan tinker

# View environment variables
heroku config

# Restart app
heroku restart
```

## Production Optimizations
```bash
heroku run php artisan config:cache
heroku run php artisan route:cache
heroku run php artisan view:cache
```