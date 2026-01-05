# Contact Form Spam Protection

## Overview
The contact form now includes comprehensive spam protection to prevent automated bot submissions and malicious content.

## Spam Protection Features

### 1. **Honeypot Fields**
- Hidden fields (`website`, `url`) that are invisible to real users
- Bots automatically fill these fields, identifying themselves
- Submissions with filled honeypot fields are silently rejected

### 2. **Rate Limiting**
- Maximum 3 submissions per hour from the same IP address
- Prevents spam flooding from a single source
- Returns error message after limit exceeded

### 3. **Time-Based Validation**
- Tracks how long the form is displayed before submission
- Submissions faster than 3 seconds are rejected (bots typically submit instantly)
- Hidden timestamp field validates human behavior

### 4. **Suspicious Content Detection**
Automatically detects and blocks:
- Russian SEO spam links (proffseo.ru, prodvizhenie, etc.)
- URL shorteners (bit.ly, tinyurl, goo.gl)
- Cyrillic spam keywords
- Common spam patterns

### 5. **Admin Tools**

#### Mail Manager Features
Access via Filament admin panel → Mail Manager

**Individual Actions:**
- **Mark as Spam**: Flag suspicious messages
- **Not Spam**: Restore falsely flagged messages
- **View & Reply**: Read and respond to messages

**Bulk Actions:**
- **Mark as Spam**: Flag multiple messages at once
- **Delete Spam**: Permanently remove spam messages
- **Archive**: Move messages to archived status

**Filters:**
- Filter by spam status (spam only / legitimate only)
- Filter by subject type
- Filter by message status

### 6. **Command Line Tools**

#### Clean Spam Submissions
```bash
# Preview what would be deleted (dry run)
php artisan contact:clean-spam --dry-run

# Actually delete spam
php artisan contact:clean-spam
```

This command:
- Scans all contact submissions for suspicious patterns
- Shows you what will be deleted
- Requires confirmation before deletion
- Logs all deletions for audit trail

## For Cloud Deployment

The spam protection works automatically on Laravel Cloud. Make sure these are deployed:

1. **Migration**: Adds `is_spam`, `spam_reason`, and `user_agent` columns
2. **Updated ContactController**: Contains all spam detection logic
3. **Updated contact form**: Includes honeypot and timestamp fields

## Handling Spam

### Immediate Actions
1. Go to Mail Manager in admin panel
2. Filter by "Spam only" to see flagged messages
3. Review and confirm they are spam
4. Select all spam messages
5. Use "Delete Spam" bulk action

### Regular Maintenance
Run the cleanup command weekly:
```bash
php artisan contact:clean-spam
```

### If Spam Gets Through
1. Open the message in Mail Manager
2. Click "Mark as Spam" action
3. This:
   - Flags the message
   - Archives it
   - Records the spam reason
   - Helps improve future detection

### If Legitimate Message Marked as Spam
1. Filter by "Spam only"
2. Find the message
3. Click "Not Spam" action
4. This:
   - Removes spam flag
   - Changes status to "new"
   - Makes it visible in inbox again

## Spam Patterns to Watch For

Common indicators:
- Messages in Russian or other unexpected languages
- URLs to promotional/SEO services
- Generic subject like "General Inquiry" with promotional content
- Same email domain sending multiple similar messages
- Messages with only links, no personal content

## Database Fields

New fields in `contact_submissions` table:
- `is_spam` (boolean): Whether message is flagged as spam
- `spam_reason` (text): Why it was marked as spam
- `user_agent` (string): Browser/bot user agent for tracking

## Logs

All spam detection events are logged:
```bash
# View spam detection logs
tail -f storage/logs/laravel.log | grep spam
```

Log entries include:
- IP address
- Detection reason (honeypot, rate limit, suspicious content)
- User agent
- Timestamp

## Future Enhancements

Consider adding:
- Google reCAPTCHA v3 for invisible bot detection
- Machine learning-based spam scoring
- Automatic blocking of repeat offender IPs
- Email verification for first-time contacts
- CAPTCHA for users who trigger rate limits
