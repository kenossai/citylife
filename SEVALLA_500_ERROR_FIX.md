# 🚨 500 Internal Server Error - DIAGNOSIS & FIX

## ✅ **Deployment Success, but 500 Error on Visit**

The good news: Your build succeeded! The 500 error is a common Laravel deployment issue that we can fix.

## 🔍 **Common Causes of 500 Error in Laravel:**

### **1. Missing APP_KEY (Most Common)**
```bash
# Error: No application encryption key has been specified
```

### **2. Database Connection Issues**
```bash
# Error: SQLSTATE[HY000] [2002] Connection refused
```

### **3. File Permissions**
```bash
# Error: Permission denied (storage/logs/laravel.log)
```

### **4. Missing Environment Variables**
```bash
# Error: Environment variable not found
```

## 🔧 **IMMEDIATE FIXES:**

### **Fix 1: Set Required Environment Variables in Sevalla**

Go to your Sevalla dashboard and add these environment variables:

#### **Essential Variables:**
```bash
APP_NAME="City Life Int'l Church"
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:PObtLE+RNakxkPRVRNiOQNdXNRPAAZxxE2GNvW5Y4GU=
APP_URL=https://your-sevalla-domain.com
```

#### **Database Variables (if using database):**
```bash
DB_CONNECTION=mysql
DB_HOST=your-database-host
DB_PORT=3306
DB_DATABASE=your-database-name
DB_USERNAME=your-database-user
DB_PASSWORD=your-database-password
```

#### **Session & Cache:**
```bash
SESSION_DRIVER=file
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
```

#### **Mail Configuration:**
```bash
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@citylifechurch.org
MAIL_FROM_NAME="City Life Int'l Church"
```

### **Fix 2: Run Post-Deployment Script**

I've created a `post-deploy.sh` script that should run automatically, but you can also trigger it manually in Sevalla's console:

```bash
chmod +x post-deploy.sh
./post-deploy.sh
```

### **Fix 3: Manual Laravel Setup Commands**

If you have access to Sevalla's terminal/console, run these commands:

```bash
# Generate application key
php artisan key:generate --force

# Create storage directories
mkdir -p storage/logs storage/framework/{cache,sessions,views}

# Set permissions
chmod -R 775 storage bootstrap/cache

# Create storage link
php artisan storage:link --force

# Clear caches
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Cache configurations
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations (if database is configured)
php artisan migrate --force
php artisan db:seed --force
```

## 🔧 **Updated Dockerfile (Already Applied):**

The updated Dockerfile now includes:
- ✅ Proper storage directory creation
- ✅ Correct file permissions
- ✅ Application key generation
- ✅ Storage link creation
- ✅ Cache clearing
- ✅ Post-deployment script

## 📊 **Database Setup (If Using Database):**

### **Option 1: Sevalla Database**
1. Create a MySQL database in Sevalla dashboard
2. Get connection details from Sevalla
3. Add database environment variables
4. Redeploy application

### **Option 2: External Database**
1. Use external MySQL/PostgreSQL service
2. Add connection details to environment variables
3. Ensure database accepts connections from Sevalla IPs

## 🎯 **Step-by-Step Fix Process:**

### **Step 1: Add Environment Variables**
1. Go to Sevalla dashboard
2. Navigate to your app → Environment Variables
3. Add all the essential variables listed above
4. Save changes

### **Step 2: Trigger Redeploy**
```bash
git commit --allow-empty -m "Trigger redeploy with env vars"
git push origin master
```

### **Step 3: Check Logs**
1. In Sevalla dashboard, check application logs
2. Look for specific error messages
3. Address any remaining issues

### **Step 4: Test Application**
1. Visit your Sevalla URL
2. Should now show Laravel welcome page or your app
3. Test key features (admin panel, cafe system, etc.)

## 🚨 **If Still Getting 500 Error:**

### **Check Sevalla Logs for Specific Errors:**

**Common Error Messages & Fixes:**

1. **"No application encryption key"**
   - Add `APP_KEY` environment variable
   - Run `php artisan key:generate --force`

2. **"SQLSTATE[HY000] [2002] Connection refused"**
   - Check database connection details
   - Ensure database server is running
   - Verify database credentials

3. **"Permission denied"**
   - Check file permissions: `chmod -R 775 storage`
   - Ensure www-data owns files: `chown -R www-data:www-data storage`

4. **"Class not found"**
   - Run `composer dump-autoload --optimize`
   - Clear config cache: `php artisan config:clear`

## ✅ **Expected Result After Fix:**

After applying these fixes, you should see:
- ✅ Laravel application loads successfully
- ✅ Welcome sound system works
- ✅ Admin panel accessible at `/admin`
- ✅ Cafe system functional
- ✅ Database with seeded content

## 🎵 **Your CityLife Features Will Be Live:**
- Welcome sound on homepage
- Complete cafe menu system
- Event management
- Contact forms
- Admin dashboard
- All seeded content

**The 500 error should be resolved after setting the environment variables and running the post-deployment setup!** 🚀