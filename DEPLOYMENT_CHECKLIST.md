# Laravel Cloud Deployment Checklist

## ✅ Pre-Deployment Setup (Completed)

### Configuration Files
- [x] **Procfile** - Queue workers and scheduler configured
- [x] **cloud.toml** - Laravel Cloud configuration
- [x] **build.sh** - Automated build script
- [x] **.env.cloud** - Production environment template
- [x] **DEPLOYMENT.md** - Complete deployment guide

### Code Updates
- [x] **config/cache.php** - Redis configured for production
- [x] **config/queue.php** - Redis queue for production
- [x] **config/filesystems.php** - S3 storage for production
- [x] **routes/web.php** - Health check endpoint added
- [x] **Auto-enrollment** - Christian Development Course integration

## 📋 Deployment Steps

### 1. Laravel Cloud Account Setup
- [ ] Create Laravel Cloud account at https://cloud.laravel.com
- [ ] Connect GitHub repository
- [ ] Select Pay As You Go plan
- [ ] Note down project URL

### 2. Environment Variables Configuration
Copy from `.env.cloud` and configure:

#### Required (Before First Deploy):
- [ ] `APP_KEY` - Generate: `php artisan key:generate --show`
- [ ] `APP_URL` - Your Laravel Cloud URL
- [ ] `MAIL_USERNAME` - Your email SMTP username
- [ ] `MAIL_PASSWORD` - Your email app password
- [ ] `MAIL_FROM_ADDRESS` - noreply@citylifecc.com
- [ ] `VONAGE_KEY` - Your Vonage API key
- [ ] `VONAGE_SECRET` - Your Vonage API secret

#### Auto-Configured by Laravel Cloud:
- [ ] Database credentials (DB_*)
- [ ] Redis credentials (REDIS_*)
- [ ] S3 storage (AWS_*)

#### Optional:
- [ ] `PAYPAL_CLIENT_ID` - For donations
- [ ] `PAYPAL_CLIENT_SECRET` - For donations
- [ ] Custom domain settings

### 3. First Deployment
- [ ] Push code to master branch: `git push origin master`
- [ ] Monitor deployment in Laravel Cloud dashboard
- [ ] Check build logs for errors
- [ ] Verify health check: https://your-app.laravel.cloud/health

### 4. Post-Deployment Configuration

#### Database Setup:
- [ ] Verify migrations ran successfully
- [ ] Create admin user via Tinker console
- [ ] Test database connection

#### Queue Workers:
- [ ] Verify queue workers are running
- [ ] Test job processing
- [ ] Check scheduler is active

#### File Storage:
- [ ] Verify S3 bucket created
- [ ] Test file uploads
- [ ] Confirm storage link works

#### Email & SMS:
- [ ] Send test email
- [ ] Send test SMS
- [ ] Verify notification delivery

### 5. Application Testing

#### Core Features:
- [ ] Homepage loads correctly
- [ ] Member registration works
- [ ] Auto-enrollment to Christian Development Course
- [ ] Course dashboard displays enrollments
- [ ] Event registration functional
- [ ] Email notifications sending
- [ ] SMS notifications sending
- [ ] File uploads working (certificates, images)
- [ ] Filament admin panel accessible

#### Performance:
- [ ] Page load times < 2 seconds
- [ ] Database queries optimized
- [ ] Cache working correctly
- [ ] Queue jobs processing

### 6. Security & Monitoring

#### Security:
- [ ] APP_DEBUG=false in production
- [ ] SSL certificate active (auto by Laravel Cloud)
- [ ] Strong admin passwords set
- [ ] API keys secured in environment
- [ ] Session security enabled

#### Monitoring:
- [ ] Budget alerts configured ($25, $35, $50)
- [ ] Error tracking enabled
- [ ] Backup schedule verified
- [ ] Log monitoring active

### 7. Custom Domain (Optional)
- [ ] Add domain in Laravel Cloud dashboard
- [ ] Update DNS CNAME records
- [ ] Update APP_URL environment variable
- [ ] Verify SSL certificate issued
- [ ] Test domain access

### 8. Documentation
- [ ] Update team on production URL
- [ ] Document admin credentials (secure location)
- [ ] Share deployment guide with team
- [ ] Document any custom configurations

## 🔍 Verification Commands

Run these in Laravel Cloud console to verify:

```bash
# Check app status
php artisan about

# Verify database connection
php artisan db:show

# Check queue status
php artisan queue:monitor

# List scheduled tasks
php artisan schedule:list

# Check cache
php artisan cache:info

# View routes
php artisan route:list
```

## 🚨 Troubleshooting

If issues occur:

1. **Check Logs**: Laravel Cloud → Console → Logs
2. **Restart Services**: `php artisan queue:restart`
3. **Clear Cache**: `php artisan cache:clear`
4. **Re-deploy**: Push empty commit or trigger manual deploy
5. **Support**: contact@laravel.com

## 📊 Cost Monitoring

Expected monthly costs (Pay As You Go):
- **Low traffic weeks**: $10-15
- **Normal operation**: $25-35
- **High traffic (events/campaigns)**: $40-60
- **Average estimate**: $25-40/month

Set alerts at: $25, $35, $50

## 🎉 Go-Live Checklist

Final checks before announcing:
- [ ] All features tested in production
- [ ] Performance acceptable
- [ ] Email/SMS working
- [ ] Backups enabled
- [ ] Team trained on new system
- [ ] Old Heroku app (if any) deactivated
- [ ] DNS updated (if custom domain)
- [ ] Announcement prepared for congregation

---

**Deployment Date**: __________
**Deployed By**: __________
**Production URL**: __________
**Notes**: 
