# Bible School International - Quick Start Guide

## For Administrators

### Step 1: Create Your First Event

1. Log in to the admin panel
2. Navigate to **Bible School → Events**
3. Click **New**
4. Fill in the details:
   ```
   Title: Bible School International 2026
   Year: 2026
   Start Date: January 15, 2026
   End Date: January 20, 2026
   Location: Online
   Description: A week-long intensive Bible study program
   ```
5. Upload an event image (optional but recommended)
6. Ensure **Is Active** is checked
7. Click **Save**

### Step 2: Add Video Resources

1. Navigate to **Bible School → Videos**
2. Click **New**
3. Select your event from the dropdown
4. Enter video details:
   ```
   Title: Opening Session - Welcome
   Description: Introduction to the Bible School program
   Video URL: https://youtube.com/watch?v=YOUR_VIDEO_ID
   Duration: 3600 (in seconds, e.g., 1 hour)
   Order: 1
   ```
5. Upload a thumbnail (optional)
6. Ensure **Is Active** is checked
7. Click **Save**
8. Repeat for additional videos, incrementing the order number

**Supported Video URLs:**
- YouTube: `https://youtube.com/watch?v=VIDEO_ID`
- Vimeo: `https://vimeo.com/VIDEO_ID`
- Direct MP4: `https://yoursite.com/videos/video.mp4`

### Step 3: Add Audio Resources

1. Navigate to **Bible School → Audios**
2. Click **New**
3. Select your event
4. Enter audio details:
   ```
   Title: Morning Devotional - Day 1
   Description: Daily devotional for reflection
   Audio URL: https://yoursite.com/audio/devotional1.mp3
   Duration: 1800 (30 minutes)
   Order: 1
   ```
5. Click **Save**

### Step 4: Generate Access Codes

1. Navigate to **Bible School → Access Codes**
2. Click **New**
3. The system auto-generates a unique code (e.g., "K7X9M2QA")
4. Fill in student details:
   ```
   Code: K7X9M2QA (auto-generated, can be customized)
   Student Name: John Smith
   Student Email: john@example.com
   Event: Bible School International 2026
   ```
5. Optionally set an expiration date (e.g., February 1, 2026)
6. Ensure **Is Active** is checked
7. Click **Save**
8. **Copy the code** to send to the student

**Bulk Code Generation:**
- Create multiple codes by repeating this process for each student
- Keep a spreadsheet tracking: Student Name, Email, Code, Event

### Step 5: Distribute Codes to Students

Send an email to each student with their unique code:

```
Subject: Your Bible School International 2026 Access Code

Dear [Student Name],

Welcome to Bible School International 2026!

Your unique access code is: K7X9M2QA

To access the resources:
1. Visit: https://yoursite.com/bible-school-international
2. Click on "Bible School International 2026"
3. Enter your access code when prompted
4. Enjoy unlimited access to all videos and audios!

Your access code is valid until [expiration date if set].

Blessings,
Bible School Team
```

### Step 6: Monitor Usage

1. Navigate to **Bible School → Access Codes**
2. View the list of all codes
3. Check the **Usage Count** column to see how many times each code has been used
4. Check **Last Used At** to see when the student last accessed content
5. Use filters to:
   - View codes for a specific event
   - See only active codes
   - Find expired codes

### Quick Troubleshooting

**Student can't access content:**
1. Verify the code is active (Is Active = Yes)
2. Check it hasn't expired (Expires At is in the future or empty)
3. Confirm the code matches the event they're trying to access
4. Ask student to try uppercase version of the code

**Video won't play:**
1. Test the video URL in your browser
2. For YouTube: Make sure the video is not private
3. For Vimeo: Check privacy settings
4. For direct URLs: Verify the file exists and is accessible

**Audio won't play:**
1. Test the audio URL directly
2. Ensure the file format is supported (MP3, WAV, OGG)
3. Check file permissions on your server

## For Students

### How to Access Resources

1. **Receive Your Code**
   - You will receive an email with your unique access code
   - Example: "K7X9M2QA"

2. **Visit the Website**
   - Go to: `https://yoursite.com/bible-school-international`

3. **Find Your Event**
   - Browse the events listed
   - Click "View Resources" on your event

4. **Enter Your Code**
   - You'll see a form asking for your access code
   - Type or paste your code (case doesn't matter)
   - Click "Unlock Resources"

5. **Access Content**
   - Once unlocked, you can view all videos and audios
   - Click on any video/audio to watch/listen
   - Your access stays active during your session

### Tips for Students

- **Bookmark the Page**: Save the event page in your browser
- **Session Length**: Access lasts as long as you keep your browser open
- **Multiple Devices**: You may need to enter the code on each device
- **Code Issues**: Contact your administrator if the code doesn't work
- **Best Experience**: Use Chrome, Firefox, or Safari for best compatibility

## Common Questions

### For Administrators

**Q: Can one student have multiple codes?**
A: Yes, but typically one code per event is sufficient. Each code grants access to all resources in that event.

**Q: Can I reuse codes?**
A: Technically yes, but not recommended for security. Each student should have a unique code.

**Q: How do I deactivate a code?**
A: Edit the access code and uncheck "Is Active" or set an expiration date in the past.

**Q: Can I see what videos a student watched?**
A: Currently, you can only see how many times they used their code, not specific viewing history.

**Q: What happens if a code expires?**
A: The student will be unable to access resources and will need a new active code.

### For Students

**Q: How long does my access last?**
A: Access lasts for your browser session. You may need to re-enter the code if you close your browser.

**Q: Can I share my code?**
A: No, codes are personal and tracked to your name. Sharing may result in access being revoked.

**Q: Can I download the videos/audios?**
A: Not directly through the platform. Use the browser's built-in tools if download is needed.

**Q: What if I lost my code?**
A: Contact the administrator who can look up your code by your name.

**Q: Can I use my code on multiple devices?**
A: Yes, but you'll need to enter it on each device.

## Next Steps

### After Setup

1. **Test Everything**: Before the event, test all videos and audios
2. **Send Reminder**: Email students a few days before with their codes
3. **Be Available**: Plan to monitor usage and help students during the first day
4. **Gather Feedback**: After the event, survey students about their experience

### Ongoing Management

- **Archive Old Events**: Set older events to inactive when no longer needed
- **Update Content**: Add new videos/audios as they become available
- **Manage Codes**: Deactivate codes after events conclude
- **Review Usage**: Check which resources are most popular

## Need Help?

- **Documentation**: See full documentation in `docs/BIBLE_SCHOOL_INTERNATIONAL.md`
- **Technical Issues**: Check application logs for errors
- **Feature Requests**: Submit through your development team

---

**Version**: 1.0  
**Last Updated**: January 31, 2026
