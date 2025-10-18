# CityLife Temporary Hosting Deployment Guide

## Quick Setup Options

### Option 1: Railway (Recommended - Free $5 credit monthly)

#### Steps:
1. **Create Railway Account**: Go to [railway.app](https://railway.app) and sign up with GitHub
2. **Connect Repository**: 
   - Click "Deploy from GitHub repo"
   - Select your `citylife` repository
3. **Add Database**:
   - Click "Add Service" → "Database" → "PostgreSQL"
   - Railway will automatically create database and set environment variables
4. **Configure Environment Variables**:
   - Go to your service → "Variables" tab
   - Add these variables:
   ```
   APP_NAME=City Life Int'l Church
   APP_ENV=production
   APP_DEBUG=false
   APP_KEY=base64:PObtLE+RNakxkPRVRNiOQNdXNRPAAZxxE2GNvW5Y4GU=
   ```
5. **Deploy**: Railway will automatically deploy from your GitHub repo

#### Railway Auto-Configuration:
- Database variables are set automatically (PGHOST, PGPORT, etc.)
- Domain provided: `https://your-app-name.up.railway.app`
- SSL certificate included
- Automatic deployments on git push

---

### Option 2: Render (Free for static, $7/month for web services)

#### Steps:
1. **Create Render Account**: Go to [render.com](https://render.com)
2. **Create Web Service**:
   - Connect GitHub repository
   - Choose "Web Service"
   - Runtime: PHP
   - Build Command: `composer install --no-dev --optimize-autoloader`
   - Start Command: `vendor/bin/heroku-php-apache2 public/`
3. **Add PostgreSQL Database**:
   - Create new PostgreSQL database (free for 90 days)
   - Copy database URL to web service environment variables
4. **Set Environment Variables**: Add all variables from `.env.production`

---

### Option 3: Heroku (Paid - $5/month minimum)

#### Steps:
1. **Install Heroku CLI**: `brew install heroku/brew/heroku`
2. **Login**: `heroku login`
3. **Create App**: `heroku create your-app-name`
4. **Add PostgreSQL**: `heroku addons:create heroku-postgresql:mini`
5. **Set Environment Variables**:
   ```bash
   heroku config:set APP_NAME="City Life Int'l Church"
   heroku config:set APP_ENV=production
   heroku config:set APP_DEBUG=false
   heroku config:set APP_KEY=base64:PObtLE+RNakxkPRVRNiOQNdXNRPAAZxxE2GNvW5Y4GU=
   ```
6. **Deploy**: `git push heroku master`

---

## Files Created for Deployment:

1. **Procfile** - Tells hosting platform how to run your app
2. **railway.json** - Railway-specific configuration
3. **deploy.sh** - Deployment script with Laravel commands
4. **.env.production** - Production environment template

## Important Notes:

### Before Deploying:
1. **Commit all changes** to GitHub:
   ```bash
   git add .
   git commit -m "Add deployment configuration"
   git push origin master
   ```

2. **Database Migration**: The deployment will automatically run migrations and seeders

3. **File Storage**: Configure file storage for uploaded images:
   - For production, consider using AWS S3 or similar
   - Current setup uses local storage (files will be lost on restart)

### Post-Deployment:
1. **Test the application** at your provided URL
2. **Check logs** if there are any issues
3. **Configure custom domain** if needed

## Cost Breakdown:
- **Railway**: $5 credit/month (free tier)
- **Render**: Free for static, $7/month for web service + database
- **Heroku**: $5/month for dyno + $5/month for database = $10/month

## Recommended Choice:
**Railway** is recommended for temporary hosting due to:
- Free $5 monthly credit
- Easy setup with GitHub integration
- Automatic database configuration
- Good performance and reliability
- No credit card required initially

Would you like me to help you deploy to any of these platforms?
