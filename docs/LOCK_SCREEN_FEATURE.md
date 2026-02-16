# Admin Panel Lock Screen Feature

## Overview

The admin panel now includes a lock screen feature that gracefully handles session timeouts instead of showing 404 or "page expired" errors. When the session expires due to inactivity, the admin panel will automatically lock and require the user to re-enter their password to continue.

## Features

### 1. **Lock Screen Page**
- Custom lock screen interface showing the user's name
- Password input to unlock the session
- Option to sign in as a different user
- Maintains the user's session while locked

### 2. **Session Timeout Detection**
- **Frontend Detection**: JavaScript monitors user activity and warns before timeout
- **Backend Validation**: Server-side middleware checks session validity
- **Warning System**: Users receive a 5-minute warning before the screen locks
- **Activity Tracking**: Mouse movements, clicks, keyboard input, and scrolling reset the timeout timer

### 3. **Graceful Error Handling**
- 419 (Page Expired) errors redirect to lock screen instead of error page
- Session timeout redirects to lock screen instead of login
- AJAX requests receive proper lock screen redirect responses

## Configuration

### Session Lifetime
The session timeout is controlled by the `SESSION_LIFETIME` environment variable (in minutes):

```env
SESSION_LIFETIME=120  # 120 minutes = 2 hours
```

### How It Works

1. **Activity Monitoring**
   - The system tracks user activity (mouse, keyboard, scroll, touch)
   - A timer resets on each activity
   - After `SESSION_LIFETIME` minutes of inactivity, warnings appear

2. **Warning Phase**
   - 5 minutes before timeout, a warning notification appears
   - Shows countdown timer
   - Allows user to extend session by clicking "Stay Active"

3. **Lock Screen Activation**
   - After full timeout, screen automatically locks
   - User sees lock screen with their profile
   - Must enter password to unlock
   - Session data is preserved during lock

4. **Unlocking**
   - User enters their password
   - On success, session is regenerated and user returns to where they were
   - On failure, shown error and can try again
   - Can also choose to sign out and sign in as different user

## Technical Implementation

### Files Created/Modified

1. **`app/Filament/Pages/LockScreen.php`**
   - Lock screen page controller
   - Password verification logic
   - Session management

2. **`resources/views/filament/pages/lock-screen.blade.php`**
   - Lock screen UI template
   - User profile display
   - Password input form

3. **`app/Http/Middleware/CheckSessionTimeout.php`**
   - Server-side session timeout detection
   - Redirects expired sessions to lock screen
   - Updates last activity time

4. **`app/Http/Controllers/SessionController.php`**
   - API endpoints for session management
   - `/admin/ping` - Update activity time
   - `/admin/lock` - Manually lock session
   - `/admin/session-check` - Check session status

5. **`public/js/session-timeout.js`**
   - Frontend session monitoring
   - Warning notifications
   - Automatic lock screen redirect
   - Activity tracking

6. **`app/Providers/Filament/AdminPanelProvider.php`**
   - Registered lock screen page
   - Added CheckSessionTimeout middleware
   - Injected session timeout JavaScript
   - Added necessary meta tags

7. **`routes/web.php`**
   - Added session management routes
   - Protected with auth middleware

8. **`bootstrap/app.php`**
   - Custom 419 error handling
   - Redirects to lock screen for admin routes

## Usage

### For Users

1. **Normal Use**: Work normally in the admin panel
2. **Warned**: See warning 5 minutes before timeout
3. **Stay Active**: Click "Stay Active" button to extend session
4. **Locked**: If inactive, screen locks automatically
5. **Unlock**: Enter password to continue working

### For Administrators

**Adjust Session Timeout**:
```env
# In .env file
SESSION_LIFETIME=180  # 3 hours
```

**Disable Lock Screen** (not recommended):
Comment out the middleware in `AdminPanelProvider.php`:
```php
// \App\Http\Middleware\CheckSessionTimeout::class,
```

## Benefits

✅ **User-Friendly**: No more confusing 404/expired errors  
✅ **Security**: Sessions timeout properly after inactivity  
✅ **Convenience**: No need to re-navigate after unlocking  
✅ **Warning System**: Users get advance notice before timeout  
✅ **Session Preservation**: Work in progress is not lost  
✅ **Professional**: Polished admin experience  

## Notes

- The lock screen only applies to the admin panel (`/admin/*` routes)
- The frontend website is not affected by this feature
- Session data is preserved during lock state
- Multiple failed unlock attempts don't lock out the user
- The warning countdown timer is accurate to the second
- Activity tracking is lightweight and doesn't impact performance

## Troubleshooting

**Lock screen not appearing?**
- Check that JavaScript is enabled
- Verify `session-timeout.js` is loading
- Check browser console for errors

**Session expiring too quickly?**
- Increase `SESSION_LIFETIME` in `.env`
- Check server session configuration
- Verify `SESSION_DRIVER` is properly configured

**Warning not showing?**
- Clear browser cache
- Check that meta tags are present in page source
- Verify JavaScript is not blocked

## Future Enhancements

Possible improvements for future versions:
- Configurable warning time
- Multiple warning levels
- Session extension without password
- Biometric unlock support
- Remember device option
- Activity logging
