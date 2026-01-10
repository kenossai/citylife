# Admin Notification for Registration Interests

## Overview
When a user registers their interest to join CityLife Church, all active admin users are automatically notified via email and in-app notifications.

## Implementation Summary

### Files Created
1. **`app/Notifications/NewRegistrationInterest.php`**
   - Notification class that sends email and database notifications
   - Queued for background processing (implements `ShouldQueue`)
   - Uses custom email template for better presentation

2. **`resources/views/emails/admin/new-registration-interest.blade.php`**
   - Professional HTML email template for admin notifications
   - Includes user email, submission time, and direct link to admin panel
   - Styled to match CityLife Church branding

3. **`app/Console/Commands/TestRegistrationNotification.php`**
   - Test command to verify notification functionality
   - Usage: `php artisan test:registration-notification email@example.com`

### Files Modified
1. **`app/Livewire/RegistrationInterestModal.php`**
   - Added notification sending when new interest is submitted
   - Notifies all active admin users automatically
   - Uses Laravel's Notification facade for bulk sending

2. **`docs/REGISTRATION_FLOW.md`**
   - Updated flow diagram to show admin notification step
   - Added documentation for new notification components
   - Updated user journey to include admin notification

## How It Works

### User Submits Interest
1. User clicks "Join Us" button on website
2. Enters email in modal popup
3. Clicks "Submit Interest"

### Admin Gets Notified
1. System creates a new `RegistrationInterest` record with status "pending"
2. System finds all active admin users (`User::where('is_active', true)`)
3. Sends `NewRegistrationInterest` notification to all admins
4. Notification includes:
   - **Email**: Professional HTML email with user details and action button
   - **Database**: In-app notification visible in Filament admin panel

### Email Contents
The admin email includes:
- User's email address
- Submission date and time
- Current status (Pending)
- Direct "Review in Admin Panel" button
- Next steps guidance

### Admin Takes Action
1. Admin receives email notification
2. Clicks "Review in Admin Panel" button (or navigates manually)
3. Reviews the registration interest in Filament
4. Approves or rejects the request
5. If approved, user receives registration invitation email

## Notification Channels

### Email Notification
- Sent to all active admin users
- Queued for background processing
- Professional HTML template
- Direct link to admin panel

### Database Notification
- Stored in `notifications` table
- Visible in Filament admin panel (bell icon)
- Contains interest ID, email, and submission time
- Can be marked as read in admin panel

## Testing

### Manual Testing
1. Visit the website and click "Join Us"
2. Enter a test email (e.g., `test@example.com`)
3. Submit the interest
4. Check admin email inbox for notification
5. Login to admin panel and check notifications (bell icon)
6. Verify notification appears in database

### Using Test Command
```bash
php artisan test:registration-notification test@example.com
```

This command will:
- Create a test registration interest
- Show all active admin users
- Send notifications to all admins
- Optionally clean up test data

## Configuration

### Admin User Requirements
For a user to receive notifications, they must:
- Exist in the `users` table
- Have `is_active` set to `true`
- Have a valid email address

### Queue Configuration
The notification implements `ShouldQueue`, so ensure your queue worker is running:
```bash
php artisan queue:work
```

For development without queue worker, the notification will be sent synchronously.

## Benefits

1. **Immediate Awareness**: Admins are instantly notified of new interests
2. **No Manual Checking**: No need to regularly check the admin panel
3. **Multiple Admins**: All active admins receive notifications
4. **Direct Access**: Email contains direct link to review the interest
5. **In-App Alerts**: Database notifications provide in-app reminders
6. **Audit Trail**: Database notifications create a record of all alerts

## Future Enhancements

Potential improvements:
- Add SMS notifications for urgent interests
- Include interest statistics in daily digest emails
- Add notification preferences per admin user
- Implement notification grouping for multiple interests
- Add Slack/Teams integration for team notifications

## Troubleshooting

### Notifications Not Received
1. Check if admin users have `is_active = true`
2. Verify email configuration in `.env`
3. Check queue worker is running if using queues
4. Check `failed_jobs` table for any errors
5. Verify notifications table exists (run migrations)

### Test Notification
Run the test command to verify setup:
```bash
php artisan test:registration-notification admin@example.com
```
