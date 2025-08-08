# Course Registration Notification System

This system provides email and SMS notifications for course registrations.

## Features

- **Email Notifications**: Sent via Laravel's mail system with detailed course information
- **SMS Notifications**: Configurable SMS service with multiple driver support
- **Database Notifications**: Stored for tracking and admin review
- **Queue Processing**: Notifications are queued for better performance

## Configuration

### Email Setup
Configure your mail settings in `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourchurch.com
MAIL_FROM_NAME="Your Church Name"
```

### SMS Setup
Add SMS configuration to `.env`:
```env
SMS_DRIVER=log  # Options: log, twilio, vonage

# For Twilio (when SMS_DRIVER=twilio)
TWILIO_SID=your-twilio-sid
TWILIO_TOKEN=your-twilio-token
TWILIO_FROM=your-twilio-phone-number

# For Vonage (when SMS_DRIVER=vonage)
VONAGE_API_KEY=your-vonage-key
VONAGE_API_SECRET=your-vonage-secret
VONAGE_FROM=your-vonage-number
```

### Queue Processing
For production, run the queue worker:
```bash
php artisan queue:work
```

## How It Works

1. **User Registration**: When a user registers for a course, the system:
   - Creates/updates member record
   - Creates course enrollment
   - Sends email notification
   - Sends SMS notification (if phone provided)
   - Stores notification in database

2. **Notification Content**:
   - **Email**: Detailed course information with course link
   - **SMS**: Brief confirmation with course name and start date

3. **Error Handling**: If notifications fail, the registration still succeeds, but errors are logged

## SMS Drivers

### Log Driver (Default)
- Logs SMS messages to Laravel log files
- Perfect for development/testing
- No external service required

### Twilio Driver
- Production-ready SMS service
- Requires Twilio account and credentials
- TODO: Implementation needed

### Vonage Driver  
- Alternative SMS service
- Requires Vonage account and credentials
- TODO: Implementation needed

## Customization

### Email Template
The email notification is defined in `app/Notifications/CourseRegistrationConfirmation.php`. You can customize:
- Subject line
- Email content
- Styling
- Additional course information

### SMS Message
The SMS content is generated in the `getSmsMessage()` method and can be customized for your needs.

## Testing

Run the test suite to verify notifications:
```bash
php artisan test --filter CourseRegistrationNotificationTest
```

## Future Enhancements

- [ ] Implement Twilio SMS integration
- [ ] Implement Vonage SMS integration  
- [ ] Add notification preferences for users
- [ ] Course reminder notifications
- [ ] Lesson available notifications
- [ ] Certificate completion notifications
