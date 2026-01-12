# ChurchSuite Integration

This document explains the ChurchSuite integration feature that automatically transfers CDC (Christian Development Course) graduates to your ChurchSuite account.

## Overview

When a member successfully completes the CDC course, their data is automatically transferred to ChurchSuite, making them a full member in your church management system. This eliminates manual data entry and ensures consistency between your Laravel application and ChurchSuite.

## Features

- **Automatic Transfer**: CDC course graduates are automatically synced to ChurchSuite upon course completion
- **One-Click Manual Sync**: Admin can manually sync any member to ChurchSuite from the admin panel
- **Bulk Sync**: Transfer multiple members at once using bulk actions
- **Sync Status Tracking**: Track which members have been synced, pending, or failed
- **Error Logging**: Detailed error logs for troubleshooting failed syncs
- **Duplicate Prevention**: Checks if member already exists in ChurchSuite before creating

## Setup Instructions

### 1. Configure ChurchSuite API Credentials

Add the following to your `.env` file:

```env
CHURCHSUITE_API_URL=https://api.churchsuite.com/v1
CHURCHSUITE_ACCOUNT_NAME=your-account-name
CHURCHSUITE_API_KEY=your-api-key
```

**How to get your credentials:**

1. Log in to your ChurchSuite account
2. Go to **Settings** → **API & Integrations**
3. Generate a new API key with the following permissions:
   - Read/Write access to Contacts
   - Read access to Tags
4. Copy your account name (visible in your ChurchSuite URL: `https://your-account-name.churchsuite.com`)
5. Copy the generated API key

### 2. Run Database Migration

Execute the migration to add ChurchSuite tracking fields to the members table:

```bash
php artisan migrate
```

This adds the following fields to the `members` table:
- `churchsuite_id` - Stores the ChurchSuite contact ID
- `churchsuite_synced_at` - Timestamp of last successful sync
- `churchsuite_sync_status` - Status: pending, synced, or failed
- `churchsuite_sync_error` - Error message if sync failed

### 3. Test Connection

You can test your ChurchSuite connection using the service:

```php
$service = app(\App\Services\ChurchSuiteService::class);
$result = $service->testConnection();

if ($result['success']) {
    echo "Connected successfully!";
} else {
    echo "Connection failed: " . $result['message'];
}
```

## How It Works

### Automatic Transfer on CDC Completion

When a member completes the CDC course:

1. The `CourseEnrollment` model's `markAsCompleted()` method is called
2. It checks if the course is a CDC course (title contains "Christian Development" or "CDC")
3. If yes, it triggers `transferToChurchSuiteIfEligible()`
4. The `ChurchSuiteService` transfers the member data via API
5. Member's sync status is updated accordingly
6. User sees a success message on their dashboard

### Manual Transfer from Admin Panel

Admins can manually transfer members:

**Single Transfer:**
1. Navigate to **Members** in the admin panel
2. Find the member you want to sync
3. Click the **Sync to ChurchSuite** action button
4. Confirm the transfer
5. View success/error notification

**Bulk Transfer:**
1. Navigate to **Members** in the admin panel
2. Select multiple members using checkboxes
3. Click **Bulk Actions** → **Sync to ChurchSuite**
4. Confirm the transfer
5. View summary of successful and failed transfers

### Bulk Sync CDC Graduates

To sync all CDC graduates who haven't been synced yet:

```php
$service = app(\App\Services\ChurchSuiteService::class);
$results = $service->bulkTransferCDCGraduates();

// Results contain:
// - total: Total number of graduates found
// - successful: Number of successful transfers
// - failed: Number of failed transfers
// - errors: Array of error messages
```

## Data Mapping

The following member data is transferred to ChurchSuite:

| Laravel Field | ChurchSuite Field |
|---------------|-------------------|
| first_name | first_name |
| last_name | last_name |
| middle_name | middle_name |
| email | email |
| phone | mobile |
| alternative_phone | telephone |
| address | address.line1 |
| city | address.city |
| postal_code | address.postcode |
| country | address.country |
| gender | sex (m/f) |
| marital_status | marital |
| date_of_birth | date_of_birth |
| occupation | job |
| employer | employer |

**Custom Fields (stored in ChurchSuite custom_fields):**
- membership_number
- membership_status
- first_visit_date
- membership_date
- baptism_status
- baptism_date
- previous_church
- emergency_contact_name
- emergency_contact_phone
- emergency_contact_relationship

**Communication Preferences:**
- receives_newsletter → general_email
- receives_sms → general_sms

## Sync Status

Members can have the following sync statuses:

- **null/Not Synced**: Member has never been synced to ChurchSuite
- **pending**: Sync is scheduled but not yet completed
- **synced**: Successfully synced to ChurchSuite
- **failed**: Sync attempt failed (see `churchsuite_sync_error` for details)

## Viewing Sync Status in Admin Panel

The Members table includes:
- **ChurchSuite** column showing sync status badge
- **Last Synced** column (hidden by default, toggle to show)
- Filter members by sync status
- Sync action visible only for non-synced members

## Error Handling

If a sync fails:

1. The error is logged to Laravel logs
2. The `churchsuite_sync_status` is set to 'failed'
3. The error message is stored in `churchsuite_sync_error`
4. Admin is notified via Filament notification
5. Admin can retry the sync after investigating the error

Common errors:
- **Invalid API credentials**: Check your .env configuration
- **Member already exists**: ChurchSuite may already have this contact
- **Missing required fields**: Ensure member has at least first_name, last_name, and email
- **API rate limit**: Wait and retry later

## Troubleshooting

### Members not syncing automatically

1. Check that ChurchSuite credentials are configured in `.env`
2. Verify the CDC course title contains "Christian Development" or "CDC"
3. Check Laravel logs for error messages: `storage/logs/laravel.log`
4. Test the connection using the `testConnection()` method

### Duplicate members in ChurchSuite

The integration currently creates new contacts. To update existing contacts:
- Manually link members by setting their `churchsuite_id` field
- Use the `updateMember()` method instead of `transferMember()`

### Sync status stuck on "pending"

If sync status shows "pending" but member wasn't transferred:
1. Check Laravel logs for errors
2. Manually trigger sync from admin panel
3. Check ChurchSuite API status

## API Reference

### ChurchSuiteService Methods

**`transferMember(Member $member): array`**
- Transfers a new member to ChurchSuite
- Returns: `['success' => bool, 'message' => string, 'churchsuite_id' => ?int]`

**`updateMember(Member $member): array`**
- Updates an existing ChurchSuite contact
- Requires member to have a `churchsuite_id`
- Returns: `['success' => bool, 'message' => string]`

**`testConnection(): array`**
- Tests API connectivity and credentials
- Returns: `['success' => bool, 'message' => string]`

**`bulkTransferCDCGraduates(): array`**
- Transfers all unsynced CDC graduates
- Returns: `['total' => int, 'successful' => int, 'failed' => int, 'errors' => array]`

**`isConfigured(): bool`**
- Checks if ChurchSuite credentials are configured
- Returns: true if all credentials are set

## Security Considerations

1. **API Key Protection**: Never commit your `.env` file with real API keys
2. **HTTPS Only**: ChurchSuite API requires HTTPS
3. **Data Privacy**: Ensure GDPR compliance when transferring member data
4. **Access Control**: Only admins can trigger manual syncs
5. **Audit Trail**: All syncs are logged with timestamps

## Future Enhancements

Potential improvements:
- Two-way sync (update Laravel from ChurchSuite changes)
- Sync photos/avatars
- Sync family relationships
- Sync ministry involvement
- Sync giving records
- Webhook support for real-time updates
- Scheduled batch sync via cron job

## Support

For issues or questions:
1. Check Laravel logs: `storage/logs/laravel.log`
2. Review ChurchSuite API documentation: https://github.com/ChurchSuite/churchsuite-api
3. Contact your system administrator
