# Social Media Integration Documentation

## Overview

The Social Media Integration system automatically posts events and announcements to various social media platforms including Facebook, Twitter/X, Instagram, and LinkedIn.

## Features

### 1. Automatic Posting
- **Events**: Automatically post new events when they are published
- **News/Announcements**: Automatically post news articles when published
- **Smart Content Formatting**: Each platform gets optimized content formatting
- **Image Support**: Automatically includes featured images where supported

### 2. Manual Posting
- **Bulk Actions**: Post multiple events/announcements at once
- **Individual Actions**: Post specific items via admin interface
- **Platform Selection**: Choose which platforms to post to
- **Preview**: See how content will look before posting

### 3. Scheduled Posting
- **Future Scheduling**: Schedule posts for specific dates and times
- **Automatic Processing**: Background command processes scheduled posts
- **Timezone Support**: Respects application timezone settings

### 4. Post Management
- **Status Tracking**: Monitor success/failure of posts
- **Retry Failed Posts**: Easily retry posts that failed
- **Platform Links**: Direct links to posts on social media platforms
- **Analytics**: Track posting performance across platforms

## Setup Instructions

### 1. Environment Configuration

Copy the social media configuration to your `.env` file:

```bash
# Enable/disable social media features
SOCIAL_MEDIA_AUTO_POST_ENABLED=true
SOCIAL_MEDIA_AUTO_POST_EVENTS=true
SOCIAL_MEDIA_AUTO_POST_ANNOUNCEMENTS=true

# Platform-specific settings
FACEBOOK_ENABLED=true
FACEBOOK_APP_ID=your_app_id
FACEBOOK_ACCESS_TOKEN=your_access_token
FACEBOOK_PAGE_ID=your_page_id
```

### 2. Platform Setup

#### Facebook
1. Create a Facebook App at [developers.facebook.com](https://developers.facebook.com)
2. Add your Facebook Page
3. Generate a Page Access Token with `pages_manage_posts` permission
4. Add credentials to `.env` file

#### Twitter/X
1. Create a Twitter Developer Account
2. Create a new App in the Twitter Developer Portal
3. Generate API keys and Bearer Token
4. Add credentials to `.env` file

#### Instagram
1. Set up Facebook Business integration (Instagram uses Facebook's API)
2. Connect your Instagram Business Account
3. Generate access tokens with Instagram permissions
4. Add credentials to `.env` file

#### LinkedIn
1. Create a LinkedIn Developer Application
2. Request access to LinkedIn Pages API
3. Generate access tokens for your organization
4. Add credentials to `.env` file

### 3. Scheduled Posts Processing

Add this to your `app/Console/Kernel.php` scheduler:

```php
protected function schedule(Schedule $schedule)
{
    // Process scheduled social media posts every 15 minutes
    $schedule->command('social-media:process-scheduled')
             ->everyFifteenMinutes();
}
```

Or run manually:
```bash
php artisan social-media:process-scheduled
```

## Usage

### Automatic Posting

When you publish an event or news article in the admin panel, it will automatically post to enabled social media platforms if auto-posting is enabled.

### Manual Posting

1. Go to Events or News in the admin panel
2. Click the "Post to Social Media" action on any published item
3. Select the platforms you want to post to
4. Click "Post"

### Scheduled Posting

1. Go to "Social Media Posts" in the admin panel
2. Click "Create"
3. Select your content and platforms
4. Set status to "Scheduled" and choose a date/time
5. The post will be automatically published at the scheduled time

### Managing Posts

- View all social media posts in the "Social Media Posts" section
- Filter by platform, status, or content type
- Retry failed posts
- View posts directly on social media platforms

## Content Formatting

### Events
- Event title and description
- Date, time, and location
- Host and speaker information
- Registration requirements
- Direct link to event page
- Relevant hashtags

### News/Announcements
- Article title and excerpt
- Author information
- Direct link to full article
- Relevant hashtags

## Error Handling

- Failed posts are logged with detailed error messages
- Retry functionality for failed posts
- Email notifications for critical failures (if configured)
- Comprehensive logging for debugging

## API Limitations

### Facebook
- Rate limits: 200 calls per hour per user
- Image requirements: Min 1200x630px recommended

### Twitter/X
- Character limit: 280 characters
- Rate limits: 300 posts per 15 minutes

### Instagram
- Requires images for all posts
- Image requirements: Min 1080x1080px
- Rate limits: 25 posts per day

### LinkedIn
- Rate limits: 100 posts per day per organization
- Character limit: 3000 characters

## Troubleshooting

### Common Issues

1. **Posts failing with authentication errors**
   - Check that API credentials are correct
   - Verify token permissions and expiration
   - Regenerate tokens if necessary

2. **Instagram posts failing**
   - Ensure featured image is present
   - Check image dimensions and format
   - Verify Instagram Business Account connection

3. **Character limit exceeded**
   - Content is automatically truncated for Twitter
   - Consider shortening event descriptions
   - URLs are automatically shortened

4. **Rate limits exceeded**
   - Posts will be queued and retried automatically
   - Consider reducing posting frequency
   - Stagger posts across different times

### Debug Mode

Enable detailed logging by setting:
```bash
LOG_LEVEL=debug
```

Check logs at `storage/logs/laravel.log` for detailed error information.

## Security Considerations

- Store API credentials securely in `.env` file
- Use environment-specific credentials (staging vs production)
- Regularly rotate access tokens
- Monitor for unauthorized access
- Keep API dependencies updated

## Support

For issues with the social media integration:

1. Check the logs for detailed error messages
2. Verify API credentials and permissions
3. Test with a simple post first
4. Contact the development team with specific error details

## Future Enhancements

- Analytics integration for engagement metrics
- Content scheduling recommendations
- A/B testing for post content
- Multi-language support
- Custom hashtag management
- Image optimization and resizing
