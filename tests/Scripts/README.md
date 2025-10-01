# Test Scripts

This directory contains custom test and utility scripts for the CityLife application.

## File Categories

### Test Scripts (`test_*.php`)
These are standalone test scripts for specific features and functionalities:

- `test_anniversary_reminder.php` - Tests anniversary reminder system
- `test_audit_trail.php` - Tests audit trail functionality
- `test_backup_system.php` - Tests backup system
- `test_birthday_reminder.php` - Tests birthday reminder system
- `test_birthday_widget.php` - Tests birthday widget functionality
- `test_export.php` - Tests data export functionality
- `test_export_complete.php` - Tests complete export functionality
- `test_gdpr_system.php` - Tests GDPR compliance features
- `test_last_login.php` - Tests last login tracking
- `test_mail.php` - Tests email functionality
- `test_permissions.php` - Tests role-based permissions
- `test_seo_optimization.php` - Tests SEO optimization features
- `test_social_media_integration.php` - Tests social media integration
- `test_summary.php` - Tests summary generation
- `test_updated_youth_camping_system.php` - Tests updated youth camping system
- `test_youth_camping_system.php` - Tests youth camping registration system

### Check Scripts (`check_*.php`)
These are utility scripts for checking system status and data:

- `check_notifications.php` - Checks notification system status
- `check_quiz.php` - Checks quiz functionality
- `check_table.php` - Checks database table status

### Setup Scripts (`create_test_*.php`)
These scripts create test data for development and testing:

- `create_test_birthdays.php` - Creates test birthday data

## Usage

These scripts can be run individually via command line:

```bash
php tests/Scripts/test_[feature_name].php
```

Or accessed via web browser (if properly configured) for web-based testing.

## Note

These are custom test scripts separate from the formal PHPUnit test suite located in:
- `tests/Feature/` - Feature tests
- `tests/Unit/` - Unit tests
