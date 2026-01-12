# ChurchSuite Integration - Quick Setup Guide

## What This Does

This integration automatically transfers CDC (Christian Development Course) graduates to your ChurchSuite account. When a member completes the CDC course, their data is automatically synced to ChurchSuite, making them a full member in your church management system.

## Installation Steps

### 1. Run Migration
```bash
php artisan migrate
```

### 2. Configure ChurchSuite Credentials

Add to your `.env` file:
```env
CHURCHSUITE_API_URL=https://api.churchsuite.com/v1
CHURCHSUITE_ACCOUNT_NAME=your-account-name
CHURCHSUITE_API_KEY=your-api-key
```

**Get Your Credentials:**
1. Log in to ChurchSuite
2. Go to Settings → API & Integrations
3. Generate API key with Read/Write access to Contacts
4. Copy your account name from URL
5. Copy the API key

### 3. Test Connection
```bash
php artisan churchsuite:sync --test
```

## How to Use

### Automatic Sync (Recommended)
✅ **Already configured!** When members complete the CDC course, they're automatically synced.

### Manual Sync via Admin Panel

**Single Member:**
1. Go to Admin → Members
2. Find the member
3. Click "Sync to ChurchSuite"

**Multiple Members:**
1. Go to Admin → Members
2. Select members (checkboxes)
3. Bulk Actions → "Sync to ChurchSuite"

### Command Line Sync

**Test connection:**
```bash
php artisan churchsuite:sync --test
```

**Sync specific member:**
```bash
php artisan churchsuite:sync --member=1
```

**Sync all CDC graduates:**
```bash
php artisan churchsuite:sync --cdc-graduates
```

**Sync all members:**
```bash
php artisan churchsuite:sync --all
```

## What Gets Synced

✅ Name, Email, Phone  
✅ Address, City, Postal Code  
✅ Date of Birth, Gender, Marital Status  
✅ Membership Information  
✅ Emergency Contacts  
✅ Communication Preferences  

## Files Created/Modified

### New Files:
- `app/Services/ChurchSuiteService.php` - Main integration service
- `app/Console/Commands/ChurchSuiteSyncCommand.php` - CLI command
- `database/migrations/2026_01_12_064603_add_churchsuite_fields_to_members_table.php` - Database migration
- `docs/CHURCHSUITE_INTEGRATION.md` - Full documentation
- `docs/CHURCHSUITE_SETUP.md` - This file

### Modified Files:
- `app/Models/Member.php` - Added ChurchSuite fields
- `app/Models/CourseEnrollment.php` - Added auto-sync on CDC completion
- `app/Filament/Resources/MemberResource.php` - Added sync actions
- `config/services.php` - Added ChurchSuite config
- `.env` - Added ChurchSuite credentials

## Troubleshooting

**"ChurchSuite Not Configured" error**
→ Add credentials to `.env` file

**"Connection failed" error**
→ Check API key and account name are correct

**Member synced but not in ChurchSuite**
→ Check Laravel logs: `storage/logs/laravel.log`

**Duplicate members**
→ Check if member already exists in ChurchSuite with same email

## Support

- Full documentation: `docs/CHURCHSUITE_INTEGRATION.md`
- ChurchSuite API Docs: https://github.com/ChurchSuite/churchsuite-api
- Check logs: `storage/logs/laravel.log`
