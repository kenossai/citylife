# Bible School International Feature

## Overview
The Bible School International feature provides a comprehensive system for managing and delivering educational content (videos and audios) to students through a secure access code system. Students can only access resources by entering a unique code assigned to them.

## Features

### 1. **Event Management**
- Create and manage Bible School events by year
- Each event can have:
  - Title, description, and year
  - Start and end dates
  - Location information
  - Featured image
  - Active/inactive status

### 2. **Video & Audio Resources**
- Upload and organize video and audio content
- Each resource includes:
  - Title and description
  - URL to the media file (supports YouTube, Vimeo, and direct URLs)
  - Thumbnail images (for videos)
  - Duration tracking
  - Order for display organization
  - Active/inactive status

### 3. **Access Code System**
- **Unique Codes**: Each student receives a unique access code
- **Code Features**:
  - Auto-generated 8-character codes
  - Student name and email tracking
  - Usage statistics (count and last used date)
  - Expiration dates (optional)
  - Active/inactive status
- **Access Control**: Resources are locked until valid code is entered
- **Session-based**: Once unlocked, access persists for the session

### 4. **Archive System**
- Browse events by year
- View all events or filter by specific year
- Maintains historical record of all Bible School events

## Admin Panel (Filament)

### Navigation
All Bible School resources are grouped under "Bible School" in the admin sidebar:
- Events (Academic Cap icon)
- Videos (Video Camera icon)
- Audios (Musical Note icon)
- Access Codes (Key icon)

### Managing Events
1. Navigate to **Bible School → Events**
2. Click **New** to create an event
3. Fill in:
   - Event title and description
   - Year (required)
   - Start/end dates (optional)
   - Location (optional)
   - Upload event image (optional)
   - Set active status
4. Save the event

### Managing Videos
1. Navigate to **Bible School → Videos**
2. Click **New** to add a video
3. Select the event this video belongs to
4. Enter video details:
   - Title and description
   - Video URL (YouTube, Vimeo, or direct link)
   - Upload thumbnail (optional)
   - Duration in seconds (optional)
   - Display order (controls listing order)
   - Set active status
5. Save the video

### Managing Audios
1. Navigate to **Bible School → Audios**
2. Click **New** to add an audio
3. Select the event
4. Enter audio details:
   - Title and description
   - Audio URL (direct link to audio file)
   - Duration in seconds (optional)
   - Display order
   - Set active status
5. Save the audio

### Managing Access Codes
1. Navigate to **Bible School → Access Codes**
2. Click **New** to create a code
3. A unique code is auto-generated (can be customized)
4. Select the event this code grants access to
5. Enter student information:
   - Student name (required)
   - Student email (optional)
6. Set optional expiration date
7. View usage statistics on existing codes:
   - Number of times used
   - Last used date

**Filtering Options:**
- Filter by event
- Filter by active status
- Filter to show expired codes

## Public Pages

### Main Pages

#### 1. Bible School International Index
**URL**: `/bible-school-international`

Displays all active Bible School events with:
- Event cards showing title, year, location, and image
- Count of videos and audios
- Year filter buttons at the top
- "View Resources" button for each event

#### 2. Archive by Year
**URL**: `/bible-school-international/archive/{year}`

Shows events filtered by a specific year with the same layout as the index page.

#### 3. Event Details
**URL**: `/bible-school-international/event/{id}`

Displays:
- Event information and image
- Access code entry form (if not unlocked)
- Grid of videos and audios (locked/unlocked based on access)
- Click on any resource to view details

#### 4. Video Detail Page
**URL**: `/bible-school-international/event/{eventId}/video/{videoId}`

**Without Access Code:**
- Shows lock icon and access code form
- Blurred preview of thumbnail
- Cannot play video

**With Access Code:**
- Embedded video player (supports YouTube, Vimeo, direct URLs)
- Full video information
- Duration display
- Related videos from the same event

#### 5. Audio Detail Page
**URL**: `/bible-school-international/event/{eventId}/audio/{audioId}`

**Without Access Code:**
- Shows lock icon and access code form
- Blurred preview
- Cannot play audio

**With Access Code:**
- HTML5 audio player
- Full audio information
- Duration display
- Related audios from the same event

## How Access Codes Work

### Student Experience

1. **Receive Code**: Student receives a unique 8-character code (e.g., "ABC12DEF")
2. **Browse Events**: Student visits the Bible School International page
3. **Select Event**: Clicks on an event they want to access
4. **Enter Code**: 
   - On event page: Unlocks all resources for that event
   - On resource page: Unlocks that specific resource
5. **Access Granted**: Code is validated and resources become accessible
6. **Session Persistence**: Access remains active for the browser session

### Code Validation

The system checks:
- ✅ Code exists in database
- ✅ Code matches the event
- ✅ Code is active
- ✅ Code hasn't expired (if expiration set)

### Security Features

- Codes are case-insensitive
- Access is session-based (resets when browser closes)
- Usage is tracked for analytics
- Codes can be deactivated or set to expire
- Each code is unique and tied to one event

## Database Structure

### Tables Created

**bible_school_events**
- id, title, description, year, start_date, end_date, location, image, is_active, timestamps

**bible_school_videos**
- id, bible_school_event_id, title, description, video_url, thumbnail, duration, order, is_active, timestamps

**bible_school_audios**
- id, bible_school_event_id, title, description, audio_url, duration, order, is_active, timestamps

**bible_school_access_codes**
- id, code, student_name, student_email, bible_school_event_id, is_active, expires_at, last_used_at, usage_count, timestamps

## Models

### BibleSchoolEvent
**Relationships:**
- `videos()` - HasMany relationship
- `audios()` - HasMany relationship
- `accessCodes()` - HasMany relationship

**Scopes:**
- `active()` - Filter active events
- `byYear($year)` - Filter by year

### BibleSchoolVideo
**Relationships:**
- `event()` - BelongsTo relationship

**Scopes:**
- `active()` - Filter active videos

**Attributes:**
- `formatted_duration` - Returns duration in HH:MM:SS or MM:SS format

### BibleSchoolAudio
**Relationships:**
- `event()` - BelongsTo relationship

**Scopes:**
- `active()` - Filter active audios

**Attributes:**
- `formatted_duration` - Returns duration in HH:MM:SS or MM:SS format

### BibleSchoolAccessCode
**Relationships:**
- `event()` - BelongsTo relationship

**Scopes:**
- `active()` - Filter active and non-expired codes

**Methods:**
- `generateUniqueCode()` - Static method to create unique codes
- `recordUsage()` - Increment usage counter and update last used date
- `isValid()` - Check if code is active and not expired

## Routes

```php
// Public routes
GET  /bible-school-international
GET  /bible-school-international/archive/{year}
GET  /bible-school-international/event/{id}
GET  /bible-school-international/event/{eventId}/video/{videoId}
GET  /bible-school-international/event/{eventId}/audio/{audioId}
POST /bible-school-international/event/{eventId}/verify-code
POST /bible-school-international/event/{eventId}/{resourceType}/{resourceId}/verify-code
```

## Usage Examples

### Creating a Complete Event

1. **Create Event**
   - Title: "Bible School International 2026"
   - Year: 2026
   - Dates: Jan 15-20, 2026
   - Location: "Online"

2. **Add Videos**
   - "Opening Session - Welcome" (order: 1)
   - "Session 2 - The Gospel" (order: 2)
   - "Session 3 - Prayer" (order: 3)

3. **Add Audios**
   - "Morning Devotional - Day 1" (order: 1)
   - "Morning Devotional - Day 2" (order: 2)

4. **Generate Access Codes**
   - Create code for "John Smith" → Gets unique code
   - Create code for "Jane Doe" → Gets unique code
   - Set expiration date (optional): Feb 1, 2026

5. **Distribute Codes**
   - Send codes to students via email
   - Students use codes to access content

### Monitoring Usage

1. Go to **Bible School → Access Codes**
2. View the list showing:
   - Each code and student name
   - Usage count
   - Last used date
   - Active status
3. Filter by event to see all codes for a specific event
4. Deactivate codes if needed

## Best Practices

### For Administrators

1. **Event Setup**
   - Create events well in advance
   - Use descriptive titles and clear descriptions
   - Add high-quality images for better engagement

2. **Resource Organization**
   - Use the order field to sequence content logically
   - Include descriptions for all videos/audios
   - Test all URLs before publishing
   - Add thumbnails to make videos more appealing

3. **Access Code Management**
   - Generate codes just before the event
   - Keep track of student emails for communication
   - Set expiration dates for time-limited access
   - Monitor usage to identify issues

4. **Content Strategy**
   - Make sure URLs are accessible and not behind paywalls
   - For YouTube, use embed-friendly URLs
   - Test video/audio playback before event
   - Provide duration information for better UX

### For Students

1. **Getting Started**
   - Receive and save your unique access code
   - Visit the Bible School International page
   - Select your event

2. **Accessing Content**
   - Enter your code once per event
   - Access remains active during your session
   - Bookmark pages for easy return

3. **Troubleshooting**
   - Check code carefully (case-insensitive)
   - Ensure code matches the event
   - Contact admin if code doesn't work
   - Clear browser cache if issues persist

## Future Enhancements

Potential features to consider:

1. **Download Options**: Allow students to download resources
2. **Progress Tracking**: Track which videos/audios each student has completed
3. **Certificates**: Generate completion certificates
4. **Discussion Forums**: Add comment sections for each resource
5. **Quizzes**: Add assessments after each session
6. **Live Streaming**: Integrate live event streaming
7. **Mobile App**: Dedicated mobile application
8. **Offline Access**: Download content for offline viewing
9. **Multi-language Support**: Translate interface and add subtitles
10. **Analytics Dashboard**: Detailed usage reports and statistics

## Support

For technical support or questions:
- Check the admin panel documentation
- Review this guide
- Contact your system administrator
- Check logs for error messages

## Changelog

### Version 1.0 (January 31, 2026)
- Initial release
- Event management system
- Video and audio resource management
- Access code security system
- Archive by year functionality
- Admin panel with Filament
- Responsive public pages
