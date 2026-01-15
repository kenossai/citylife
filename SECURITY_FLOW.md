# Member Security & Verification Flow

## Current Implementation (3-Step Security)

### For NEW Member Registrations:

1. **User Registers** (`/member/register`)
   - User fills registration form
   - System creates member account with:
     - `email_verified_at` = NULL (unverified)
     - `approved_at` = NULL (unapproved)
     - `is_active` = true
   - ✉️ **Verification email sent automatically**
   - User redirected to login page with message

2. **Email Verification** (User clicks link in email)
   - User receives email with verification link
   - Clicks link → `/verify-email/{token}`
   - System updates: `email_verified_at` = now()
   - User sees: "Email verified! Pending admin approval"

3. **Admin Approval** (In Filament Admin Panel)
   - Admin reviews new member in Filament
   - Sets `approved_at` = now()
   - Optionally sets `approved_by` (admin user ID)
   - Can add `approval_notes`

4. **User Can Login**
   - After both verification AND approval
   - Login checks all three:
     - ✅ Email verified
     - ✅ Admin approved
     - ✅ Account active

### For EXISTING Members (Before Security Update):

- All existing members were automatically:
  - ✅ Email verified (`email_verified_at` set)
  - ✅ Admin approved (`approved_at` set)
- They can login immediately with existing credentials

## Login Validation Checks

When a member tries to login, the system checks:

1. **Credentials Match** - Email & password correct
2. **Email Verified** - `email_verified_at` IS NOT NULL
3. **Admin Approved** - `approved_at` IS NOT NULL
4. **Account Active** - `is_active` = true

If ANY check fails, login is denied with specific error message.

## Email Configuration

### Local Development (Mailtrap)
- Emails sent to: `sandbox.smtp.mailtrap.io`
- Check: https://mailtrap.io inbox

### Production
Update `.env` with real SMTP:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com  # or your provider
MAIL_PORT=587
MAIL_USERNAME=your-email@domain.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@citylifecc.com
MAIL_FROM_NAME="CityLife Church"
```

## Artisan Commands

### Approve All Existing Members
```bash
php artisan members:approve-all
```
- Verifies and approves all members
- Run this on production after deployment

### Send Verification Email
```bash
# To specific member
php artisan members:send-verification user@example.com

# To all unverified members
php artisan members:send-verification
```

### Test Email Configuration
```bash
php artisan mail:test user@example.com
```

## Security Benefits

✅ **Prevents Unauthorized Access**
- No one can access system without email verification
- Admin must approve every new member

✅ **Prevents Spam Registrations**
- Email verification filters out fake emails
- Admin review prevents bot accounts

✅ **Audit Trail**
- `email_verified_at` - when email was verified
- `approved_at` - when admin approved
- `approved_by` - which admin approved

✅ **Existing Members Protected**
- Automatic approval migration prevents lockout
- No disruption to current members

## Troubleshooting

### "User not receiving verification email"

1. **Check if member is already verified:**
   ```bash
   php artisan tinker
   >>> App\Models\Member::where('email', 'user@example.com')->first(['email_verified_at', 'approved_at'])
   ```

2. **Check mail configuration:**
   ```bash
   php artisan config:show mail
   ```

3. **Send verification manually:**
   ```bash
   php artisan members:send-verification user@example.com
   ```

4. **Test email sending:**
   ```bash
   php artisan mail:test user@example.com
   ```

5. **Check Mailtrap inbox** (local) or mail logs (production)

### "Member can't login after verification"

Member needs BOTH verification AND approval:
- Check: `email_verified_at` IS NOT NULL
- Check: `approved_at` IS NOT NULL
- Admin must approve in Filament panel

## File Locations

- **Middleware**: `app/Http/Middleware/MemberAuthenticated.php`
- **Login Controller**: `app/Http/Controllers/Auth/MemberAuthController.php`
- **Verification Notification**: `app/Notifications/MemberEmailVerification.php`
- **Member Model**: `app/Models/Member.php`
- **Routes**: `routes/web.php` (line ~133 - member.auth middleware)

## Production Deployment Checklist

- [ ] Update `.env` with production SMTP credentials
- [ ] Run `php artisan migrate` (adds verification columns)
- [ ] Run `php artisan members:approve-all` (approve existing members)
- [ ] Test registration flow
- [ ] Test email delivery
- [ ] Verify admin can approve new members in Filament
