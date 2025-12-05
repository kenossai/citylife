# Laravel Cloud Deployment Guide - CityLife Church

## 🚀 Quick Start Deployment

### 1. Prerequisites
- Laravel Cloud account (sign up at https://cloud.laravel.com)
- GitHub repository connected
- Domain name (optional, Laravel Cloud provides subdomain)

### 2. Create New Project on Laravel Cloud

1. **Connect GitHub Repository**
   - Go to Laravel Cloud dashboard
   - Click "New Project"
   - Select your `citylife` repository
   - Choose branch: `master`

2. **Select Plan**
   - Recommended: **Pay As You Go** (cost-effective for church traffic patterns)
   - Alternative: Fixed plan if consistent high traffic

### 3. Configure Environment Variables

Copy all variables from `.env.cloud` to Laravel Cloud environment settings:

#### Critical Variables (Must Set):
```bash
APP_NAME="City Life Church"
APP_ENV=production
APP_KEY=base64:your-generated-key  # Generate with: php artisan key:generate --show
APP_DEBUG=false
APP_URL=https://your-app.laravel.cloud

# Mail (Update with your SMTP details)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-specific-password
MAIL_FROM_ADDRESS=noreply@citylifecc.com
MAIL_FROM_NAME="City Life Church"

# SMS - Vonage (Update with your credentials)
VONAGE_KEY=your-vonage-api-key
VONAGE_SECRET=your-vonage-api-secret
VONAGE_SMS_FROM="CityLife"

# PayPal (if using donations)
PAYPAL_MODE=live
PAYPAL_CLIENT_ID=your-production-client-id
PAYPAL_CLIENT_SECRET=your-production-client-secret
```

#### Auto-Configured by Laravel Cloud:
- Database credentials (DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD)
- Redis credentials (REDIS_HOST, REDIS_PORT, REDIS_PASSWORD)
- S3 storage credentials (AWS_ACCESS_KEY_ID, AWS_SECRET_ACCESS_KEY, AWS_BUCKET)

### 4. Database Setup

Laravel Cloud automatically creates your database. Run migrations on first deploy:

```bash
# Laravel Cloud will run these automatically on deploy
php artisan migrate --force
php artisan db:seed --class=ProductionSeeder  # If you have a production seeder
```

### 5. Storage Setup

Laravel Cloud provides S3-compatible storage. Link storage:

```bash
# Run this in Laravel Cloud console or add to deploy script
php artisan storage:link
```

### 6. Queue Workers Configuration

Laravel Cloud automatically starts queue workers from your `Procfile`:
- **web**: Octane server (main app)
- **queue**: Redis queue worker for background jobs
- **scheduler**: Laravel scheduler for cron jobs

Monitor queue workers in Laravel Cloud dashboard.

### 7. Build & Deploy

#### Automatic Deployment:
Every push to `master` triggers automatic deployment.

#### Manual Deployment:
```bash
git push origin master
```

Laravel Cloud will:
1. Pull latest code
2. Run `composer install --optimize-autoloader --no-dev`
3. Run `php artisan migrate --force`
4. Run `php artisan config:cache`
5. Run `php artisan route:cache`
6. Run `php artisan view:cache`
7. Restart services

### 8. Post-Deployment Tasks

#### A. Create Admin User (First Time Only)
```bash
# Access Laravel Cloud console and run:
php artisan tinker
```
Then create admin:
```php
$admin = App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@citylifecc.com',
    'password' => bcrypt('your-secure-password'),
]);
```

#### B. Verify Scheduled Tasks
Check that these are running:
- Birthday reminders
- Pastoral follow-ups
- Course notifications
- Newsletter sends

View in: Laravel Cloud → Your Project → Scheduler

#### C. Test Critical Features
- [ ] Member registration
- [ ] Course enrollment (including auto-enroll to Christian Development)
- [ ] Email notifications
- [ ] SMS notifications
- [ ] Event registration
- [ ] File uploads (certificates, images)
- [ ] Dashboard access

### 9. Domain Setup (Optional)

#### Custom Domain:
1. Go to Laravel Cloud → Your Project → Domains
2. Add your domain: `www.citylifecc.com`
3. Update DNS records:
   ```
   Type: CNAME
   Name: www
   Value: your-app.laravel.cloud
   ```
4. Update `APP_URL` environment variable

#### SSL Certificate:
Laravel Cloud automatically provisions and renews SSL certificates.

## 📊 Monitoring & Maintenance

### Performance Monitoring
- **Dashboard**: Laravel Cloud provides built-in metrics
- **Logs**: Real-time log streaming in console
- **Alerts**: Set up notifications for errors/downtime

### Database Backups
Laravel Cloud automatically backs up your database:
- Daily backups (retained for 7 days on Pay As You Go)
- Manual backups available anytime

### Cost Monitoring
Set budget alerts:
1. Laravel Cloud → Billing
2. Set alerts at: $25, $35, $50

### Optimization Tips

#### Cache Configuration:
```bash
# These run automatically on deploy, but you can run manually:
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

#### Queue Performance:
Monitor queue metrics:
- Jobs processed per minute
- Failed jobs
- Average job duration

Adjust workers if needed in Procfile.

## 🔧 Troubleshooting

### Common Issues

#### 1. Queue Jobs Not Processing
```bash
# Check queue worker status in Laravel Cloud console
php artisan queue:work --once  # Test single job
php artisan queue:restart      # Restart workers
```

#### 2. File Upload Errors
```bash
# Verify S3 configuration
php artisan storage:link
# Check AWS_BUCKET and credentials in environment
```

#### 3. Email Not Sending
```bash
# Test email configuration
php artisan tinker
Mail::raw('Test', function($msg) { 
    $msg->to('test@example.com')->subject('Test'); 
});
```

#### 4. Cache Issues
```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Get Help
- Laravel Cloud Support: support@laravel.com
- Documentation: https://docs.laravel.com/cloud
- Community: Laravel Discord/Forums

## 📈 Scaling

### When to Scale Up:
- Consistent $35-40/month on Pay As You Go
- Database storage > 80%
- Queue workers falling behind
- Response times > 500ms

### Scaling Options:
1. **Horizontal**: Add more web/queue workers
2. **Vertical**: Upgrade to fixed plan with more resources
3. **Database**: Upgrade database tier
4. **Cache**: Increase Redis memory

## 🎯 Launch Checklist

- [ ] All environment variables configured
- [ ] Database migrated successfully
- [ ] Admin user created
- [ ] Queue workers running
- [ ] Scheduler active
- [ ] Email sending working
- [ ] SMS notifications working
- [ ] File uploads to S3 working
- [ ] Custom domain configured (if applicable)
- [ ] SSL certificate active
- [ ] Backups enabled
- [ ] Monitoring alerts set
- [ ] Team access configured
- [ ] Documentation updated with production URLs

## 🔐 Security Notes

1. **Never commit** `.env` file to Git
2. **Rotate** `APP_KEY` only when necessary (invalidates sessions)
3. **Use** strong passwords for admin accounts
4. **Enable** two-factor authentication for Laravel Cloud
5. **Review** user permissions regularly
6. **Monitor** failed login attempts
7. **Keep** dependencies updated: `composer update`

## 📞 Support Contacts

- **Technical Issues**: Laravel Cloud Support
- **Billing Questions**: billing@laravel.com
- **Security Concerns**: security@laravel.com

---

**Deployment Date**: _Add date here_
**Deployed By**: _Add name here_
**Production URL**: https://your-app.laravel.cloud
