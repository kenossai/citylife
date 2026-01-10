# Registration Flow - CityLife Church

## Overview
The registration system has been updated to use a two-step approval process where users express interest, admins approve, and then users complete their registration.

## Flow Diagram

```
User clicks "Join Us" button
    ↓
Modal popup appears - Enter email
    ↓
Submit → Record saved as "pending" in registration_interests table
    ↓
Admin views in Filament dashboard
    ↓
Admin approves interest
    ↓
System generates unique token & sends email with registration link
    ↓
User clicks link in email
    ↓
Registration form opens (email pre-filled from token)
    ↓
User completes registration
    ↓
Account created + CDC course auto-assigned + Redirected to course dashboard
```

## Components Created

### 1. Database
- **Table**: `registration_interests`
- **Fields**:
  - `email` - User's email address
  - `status` - pending/approved/rejected
  - `token` - Unique registration link token
  - `approved_by` - Admin who approved
  - `approved_at` - Approval timestamp
  - `registered_at` - Registration completion timestamp
  - `user_id` - Link to created member account
  - `notes` - Admin notes

### 2. Model
- **File**: `app/Models/RegistrationInterest.php`
- **Key Methods**:
  - `generateToken()` - Creates unique 64-char token
  - `isPending()` - Check if awaiting approval
  - `isApproved()` - Check if approved
  - `isRegistered()` - Check if registration completed

### 3. Filament Resource
- **File**: `app/Filament/Resources/RegistrationInterestResource.php`
- **Features**:
  - View all registration interests
  - Approve/Reject individual or bulk interests
  - Resend invitation emails
  - Filter by status and registration completion
  - Navigation: User Management > Registration Interests

### 4. Livewire Modal Component
- **Files**:
  - `app/Livewire/RegistrationInterestModal.php`
  - `resources/views/livewire/registration-interest-modal.blade.php`
- **Features**:
  - Popup modal for email capture
  - Email validation
  - Duplicate prevention
  - Success message display
  - Auto-closes after 3 seconds

### 5. Email Notification
- **File**: `app/Notifications/RegistrationInvitation.php`
- **Content**:
  - Welcome message
  - Personalized registration link with token
  - 7-day expiration notice
  - Information about CDC course enrollment

### 6. Registration Controller Updates
- **File**: `app/Http/Controllers/Auth/MemberAuthController.php`
- **New Methods**:
  - `showRegisterWithToken($token)` - Display registration form with pre-filled email
  - `registerWithToken($token)` - Process registration, create account, auto-enroll CDC

### 7. Routes
- **File**: `routes/web.php`
- **New Routes**:
  - `GET /register/{token}` - Registration form with token
  - `POST /register/{token}` - Submit registration with token

### 8. Views
- **File**: `resources/views/auth/member/register-with-token.blade.php`
- **Features**:
  - Pre-filled email field (readonly)
  - All standard registration fields
  - Token validation
  - Direct redirect to course dashboard after completion

### 9. Layout Updates
- **File**: `resources/views/layouts/base.blade.php`
- **Changes**:
  - Added `@livewireStyles` in head
  - Added `@livewire('registration-interest-modal')` component
  - Added `@livewireScripts` before closing body

### 10. Button Updates
- **File**: `resources/views/pages/about/our-ministry.blade.php`
- **Change**: "Join Us" button now triggers modal via `onclick="Livewire.dispatch('openModal')"`

## User Journey

### Step 1: Express Interest
1. User visits website and clicks "Join Us" button
2. Modal popup appears
3. User enters email and clicks "Submit Interest"
4. Success message: "Thank you for your interest! We'll review your request and send you a registration link soon."

### Step 2: Admin Approval
1. Admin logs into Filament (/admin)
2. Navigates to User Management > Registration Interests
3. Views pending interests
4. Clicks "Approve" button (or bulk approve multiple)
5. System automatically:
   - Updates status to "approved"
   - Generates unique token
   - Sends invitation email

### Step 3: Complete Registration
1. User receives email with subject: "Welcome to CityLife Church - Complete Your Registration"
2. Clicks "Complete Registration" button in email
3. Redirected to registration form with email pre-filled
4. Completes remaining fields (name, phone, password)
5. Accepts GDPR consent
6. Submits form

### Step 4: Auto-Enrollment & Dashboard
1. Account created automatically
2. CDC (Church Development Course) auto-assigned
3. User logged in automatically
4. Redirected to `/my-courses` dashboard
5. Success message: "Welcome to CityLife Church! You have been enrolled in the Church Development Course."

## Admin Features

### Filament Dashboard Actions
- **Approve** - Approve single interest and send invitation
- **Reject** - Reject interest (no email sent)
- **Resend** - Resend invitation email if user didn't receive/lost it
- **Bulk Approve** - Approve multiple interests at once
- **View** - See full details of interest
- **Edit** - Modify email or add notes

### Filters Available
- Status (Pending/Approved/Rejected)
- Completed Registration
- Not Registered Yet

### Table Columns
- Email (searchable)
- Status (badge with colors)
- Registered (icon)
- Approved By
- Approved At
- Created At

## Security Features

1. **Unique Tokens**: 64-character random tokens prevent guessing
2. **Email Matching**: Registration email must match invited email
3. **Token Expiration**: Links expire after 7 days
4. **One-Time Use**: Token invalidated after successful registration
5. **Status Validation**: Only approved, non-registered interests can be used

## Email Configuration

Ensure your `.env` file has proper mail configuration:

```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=info@citylifechurch.org
MAIL_FROM_NAME="CityLife Church"
```

## CDC Course Setup

The system automatically enrolls new members into the CDC course. Ensure a course exists with:
- **Slug**: `christian-development` OR
- **Title**: Contains "Christian Development"

If no CDC course exists, enrollment will be skipped (logged but no error shown to user).

## Testing the Flow

### 1. Test Interest Submission
```
1. Visit homepage or /our-ministry
2. Click "Join Us" button
3. Enter test email: test@example.com
4. Submit
5. Verify success message appears
```

### 2. Test Admin Approval
```
1. Login to /admin
2. Go to User Management > Registration Interests
3. Find test@example.com
4. Click Approve
5. Verify notification shows "Registration invitation has been sent"
```

### 3. Test Registration (Manual)
```
Since you can't easily check email in dev:
1. Check database: SELECT token FROM registration_interests WHERE email = 'test@example.com'
2. Visit: /register/{token}
3. Complete form
4. Verify redirect to /my-courses
5. Verify CDC course appears in dashboard
```

### 4. Test Queue (If Using)
```bash
# If using queue for emails
php artisan queue:work

# Check failed jobs
php artisan queue:failed
```

## Troubleshooting

### Modal doesn't appear
- Check browser console for JavaScript errors
- Verify Livewire scripts are loaded
- Clear browser cache

### Email not sent
- Check mail configuration in `.env`
- Check Laravel logs: `storage/logs/laravel.log`
- Test mail connection: `php artisan tinker` then `Mail::raw('Test', function($msg) { $msg->to('test@example.com')->subject('Test'); });`

### Token invalid/expired
- Verify token exists in database
- Check `approved_at` is within 7 days
- Ensure `registered_at` is NULL

### CDC course not assigned
- Check course exists with slug `christian-development`
- Check Laravel logs for enrollment errors
- Verify course status is published/active

## Future Enhancements

Potential improvements:
1. SMS notifications for mobile users
2. Customizable email templates via admin
3. Configurable token expiration period
4. Welcome video/tutorial after registration
5. Auto-assignment of multiple courses based on interests
6. Integration with church management system
7. Two-factor authentication option

## Files Modified/Created

### Created Files
- `database/migrations/2026_01_09_215613_create_registration_interests_table.php`
- `app/Models/RegistrationInterest.php`
- `app/Filament/Resources/RegistrationInterestResource.php`
- `app/Filament/Resources/RegistrationInterestResource/Pages/ListRegistrationInterests.php`
- `app/Filament/Resources/RegistrationInterestResource/Pages/CreateRegistrationInterest.php`
- `app/Filament/Resources/RegistrationInterestResource/Pages/EditRegistrationInterest.php`
- `app/Livewire/RegistrationInterestModal.php`
- `resources/views/livewire/registration-interest-modal.blade.php`
- `app/Notifications/RegistrationInvitation.php`
- `resources/views/auth/member/register-with-token.blade.php`
- `docs/REGISTRATION_FLOW.md` (this file)

### Modified Files
- `routes/web.php` - Added token-based registration routes
- `app/Http/Controllers/Auth/MemberAuthController.php` - Added token registration methods
- `resources/views/layouts/base.blade.php` - Added Livewire styles/scripts and modal
- `resources/views/pages/about/our-ministry.blade.php` - Updated Join Us button

## Support

For issues or questions about this registration flow:
1. Check Laravel logs: `storage/logs/laravel.log`
2. Check Filament resource is registered
3. Verify database migration ran successfully
4. Test email configuration
5. Review this documentation

---

Last Updated: January 2026
