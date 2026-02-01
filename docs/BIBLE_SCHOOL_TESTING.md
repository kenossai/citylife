# Bible School International - Testing with Sample Data

## Quick Test Setup

To test the Bible School International feature with sample data:

### 1. Run the Seeder

```bash
php artisan db:seed --class=BibleSchoolSeeder
```

This will create:
- **2 Events**: Bible School 2026 and 2025
- **7 Videos**: 5 for 2026, 2 for 2025
- **4 Audios**: All for 2026 event
- **11 Access Codes**: 5 students × 2 events + 1 demo code

### 2. Demo Access Code

For quick testing, use this access code:

```
Code: DEMO2026
Event: Bible School International 2026
```

### 3. Test Flow

#### Admin Panel Testing
1. Visit `/admin` and navigate to **Bible School**
2. Check **Events** - you should see 2 events
3. Check **Videos** - you should see 7 videos
4. Check **Audios** - you should see 4 audios
5. Check **Access Codes** - you should see 11 codes

#### Public Page Testing
1. Visit `/bible-school-international`
2. You should see 2 events listed
3. Click on "Bible School International 2026"
4. You'll see an access code form
5. Enter: `DEMO2026`
6. Click "Unlock Resources"
7. You should now see all videos and audios unlocked
8. Click on any video to test the video player
9. Click on any audio to test the audio player

#### Archive Testing
1. Visit `/bible-school-international/archive/2026`
2. You should see only the 2026 event
3. Visit `/bible-school-international/archive/2025`
4. You should see only the 2025 event

### 4. Sample Data Created

#### Events
- **Bible School International 2026**
  - Dates: Jan 15-20, 2026
  - Location: Online
  - Status: Active
  - Videos: 5
  - Audios: 4

- **Bible School International 2025**
  - Dates: Jan 10-15, 2025
  - Location: Online
  - Status: Active
  - Videos: 2
  - Audios: 0

#### Sample Videos (2026)
1. Opening Session - Welcome & Introduction (1 hour)
2. Session 2 - The Gospel of Grace (1.5 hours)
3. Session 3 - The Power of Prayer (1.25 hours)
4. Session 4 - Walking in the Spirit (1.17 hours)
5. Closing Session - Going Forward (50 minutes)

#### Sample Audios (2026)
1. Morning Devotional - Day 1 (30 minutes)
2. Morning Devotional - Day 2 (30 minutes)
3. Morning Devotional - Day 3 (30 minutes)
4. Worship & Praise Audio (45 minutes)

#### Sample Access Codes
- 5 students with codes for both events
- Demo code: `DEMO2026` (easy to remember)
- 2026 codes: Valid for 3 months
- 2025 codes: Expired (for testing expired codes)

### 5. Testing Scenarios

#### Test 1: Valid Access Code
1. Go to event page
2. Enter `DEMO2026`
3. ✅ Should grant access

#### Test 2: Invalid Access Code
1. Go to event page
2. Enter `INVALID123`
3. ❌ Should show error message

#### Test 3: Expired Code
1. Go to 2025 event page
2. Try any 2025 access code from admin panel
3. ❌ Should show expired error

#### Test 4: Video Playback
1. Unlock 2026 event with `DEMO2026`
2. Click on any video
3. ✅ Should show video player (YouTube embed)

#### Test 5: Audio Playback
1. Unlock 2026 event with `DEMO2026`
2. Click on any audio
3. ✅ Should show HTML5 audio player

#### Test 6: Session Persistence
1. Unlock event with code
2. Navigate to different pages
3. ✅ Access should remain active
4. Close browser
5. Reopen and visit event
6. ❌ Should require code again

### 6. Customizing Sample Data

To modify the sample data:

1. Edit `database/seeders/BibleSchoolSeeder.php`
2. Change event details, add more videos/audios
3. Re-run the seeder:
   ```bash
   # Reset and re-seed
   php artisan migrate:fresh
   php artisan db:seed --class=BibleSchoolSeeder
   ```

### 7. Cleanup

To remove sample data:

```bash
# Option 1: Delete specific records via admin panel
# Option 2: Reset migrations
php artisan migrate:fresh

# Then re-run migrations
php artisan migrate
```

### 8. Production Data

When ready for production:

1. **Don't run the seeder** on production
2. Create real events through admin panel
3. Upload actual video/audio URLs
4. Generate real access codes for students
5. Set appropriate expiration dates

### 9. Important Notes

#### Video URLs
- Sample URLs point to YouTube (placeholder)
- Replace with actual video URLs in production
- Supports: YouTube, Vimeo, Direct MP4

#### Audio URLs
- Sample URLs are placeholders
- Replace with actual audio file URLs
- Supports: MP3, WAV, OGG formats

#### Access Codes
- `DEMO2026` is for testing only
- Delete or deactivate before production
- Generate unique codes for real students

### 10. Verification Checklist

After running seeder, verify:

- [ ] Events appear in admin panel
- [ ] Videos appear in admin panel
- [ ] Audios appear in admin panel
- [ ] Access codes appear in admin panel
- [ ] Events display on `/bible-school-international`
- [ ] Archive pages work for each year
- [ ] Access code `DEMO2026` unlocks 2026 event
- [ ] Videos are visible after unlock
- [ ] Audios are visible after unlock
- [ ] Related content shows on detail pages

### 11. Troubleshooting

**No events showing:**
- Check database connection
- Verify seeder ran successfully
- Check `is_active` status in database

**Access code not working:**
- Verify code is uppercase (system converts)
- Check code matches event ID
- Verify code is active and not expired

**Videos/Audios not loading:**
- Sample URLs are placeholders
- Replace with real URLs for actual playback

## Next Steps

1. ✅ Run the seeder
2. ✅ Test all functionality
3. ✅ Familiarize yourself with admin panel
4. ✅ Delete sample data
5. ✅ Create real events
6. ✅ Add real content
7. ✅ Generate student codes
8. ✅ Launch!

---

**Happy Testing!** 🎉
