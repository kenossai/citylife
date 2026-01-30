# ChurchSuite OAuth2 Setup Guide

## What Changed

Your ChurchSuite integration has been updated to use **OAuth2 authentication** (the modern, secure method) instead of the deprecated API key method.

## Setup Steps

### 1. Create API User in ChurchSuite

1. **Log into ChurchSuite** as an Administrator
2. Click your **profile** (top right) → **Settings**
3. Click **Users** → **Create a new user**
4. Set up the user:
   - Name: `CityLife Sync Bot` (or similar)
   - Email: Use a valid email
   - **Important**: Give this user ONLY the permissions needed:
     - Address Book → Add/Edit Contacts
     - (Keep other permissions minimal)
5. **Save** the new user

### 2. Enable API Access

1. While viewing the new user, click **More** menu
2. Click **"Enable API access"** (only Admins can do this)

### 3. Get Your Credentials

1. **Log out** of ChurchSuite
2. **Log back in** as the new API user you just created
3. Click your **profile** (top right) → **My profile**
4. Click the **"Secrets"** tab (now visible)
5. At the top, you'll see a clickable field with your **identifier** (username)
   - Copy this identifier (looks like: `user_abc123xyz`)
6. Click **"Create a new Secret"**
7. **Copy the secret immediately** (starts with `cs_oauth2_...`)
   - ⚠️ You can only see this ONCE! Store it securely.

### 4. Update Your .env File

Add these to your `.env` file:

```env
# ChurchSuite OAuth2 Configuration
CHURCHSUITE_API_URL=https://api.churchsuite.com/v2
CHURCHSUITE_TOKEN_URL=https://login.churchsuite.com/oauth2/token
CHURCHSUITE_CLIENT_ID=user_abc123xyz
CHURCHSUITE_CLIENT_SECRET=cs_oauth2_your_secret_here
```

Replace:
- `user_abc123xyz` with your actual identifier
- `cs_oauth2_your_secret_here` with your actual secret

### 5. Test the Connection

```bash
php artisan churchsuite:sync --test
```

You should see: ✅ "Successfully connected to ChurchSuite API"

## How It Works

### OAuth2 Client Credentials Flow

1. Your app exchanges the `client_id` + `client_secret` for a temporary **access token**
2. The token is **cached for ~1 hour** (automatic)
3. When the token expires, a new one is automatically obtained
4. All API requests use this token (Bearer authentication)

### What Happens When You Sync

```
Member completes CDC course
    ↓
App requests access token from ChurchSuite
    ↓
ChurchSuite returns token (valid ~1 hour)
    ↓
App caches token
    ↓
App sends member data with token to ChurchSuite API v2
    ↓
ChurchSuite creates contact & returns ID
    ↓
App stores ChurchSuite ID in your database
```

## Security Notes

- ✅ Tokens expire automatically (~1 hour)
- ✅ Credentials are stored in `.env` (never commit to Git)
- ✅ API user has minimal permissions
- ✅ All requests use HTTPS encryption
- ✅ Token is cached (reduces API calls)

## Troubleshooting

### "Failed to obtain access token"

**Check:**
- Is your `CHURCHSUITE_CLIENT_ID` correct?
- Is your `CHURCHSUITE_CLIENT_SECRET` correct?
- Did you copy the secret correctly? (they're long!)
- Did the secret expire or get deleted in ChurchSuite?

**Fix:**
1. Log into ChurchSuite as the API user
2. Go to My Profile → Secrets
3. Create a new secret
4. Update your `.env` file

### "Failed to transfer to ChurchSuite"

**Check:**
- Does the API user have permission to create contacts?
- Run test: `php artisan churchsuite:sync --test`
- Check logs: `storage/logs/laravel.log`

### "Connection timeout"

**Check:**
- Is `https://login.churchsuite.com` reachable?
- Is `https://api.churchsuite.com` reachable?
- Are you behind a firewall?

## Usage

### Sync All CDC Graduates

```bash
php artisan churchsuite:sync --cdc-graduates
```

### Sync Specific Member

```bash
php artisan churchsuite:sync --member=123
```

### Force Re-sync

```bash
php artisan churchsuite:sync --cdc-graduates --force
```

### Via Admin Panel

1. Go to Members in Filament
2. Select members
3. Click "Sync to ChurchSuite" bulk action

## API Endpoints Used

- **Token**: `POST https://login.churchsuite.com/oauth2/token`
- **Create Contact**: `POST https://api.churchsuite.com/v2/addressbook/contacts`
- **Update Contact**: `PUT https://api.churchsuite.com/v2/addressbook/contacts/{id}`
- **List Contacts**: `GET https://api.churchsuite.com/v2/addressbook/contacts`

## Need Help?

Check the ChurchSuite API documentation:
- https://developer.churchsuite.com/auth
- https://developer.churchsuite.com/module/addressbook

Or contact ChurchSuite support if you can't create API users.
