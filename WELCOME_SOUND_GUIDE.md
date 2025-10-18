# Adding Welcome Sound to CityLife Website

## How the Welcome Sound Works

I've implemented a sophisticated welcome sound system that:

1. **Plays automatically** when visitors first arrive at the homepage
2. **Respects browser autoplay policies** (requires user interaction in some browsers)
3. **Remembers user preferences** (if they disable sound, it stays disabled)
4. **Plays only once per session** (won't replay if user navigates back)
5. **Has volume control** and mute functionality
6. **Shows welcome notifications** to enhance user experience

## Audio Files Needed

Place your audio files in `/public/assets/audio/` with these names:
- `welcome-sound.mp3` (primary format - MP3 for broad compatibility)
- `welcome-sound.ogg` (optional - for Firefox/other browsers)

## Recommended Audio Specifications

### File Format:
- **Primary**: MP3 (best browser support)
- **Secondary**: OGG (for Firefox compatibility)
- **Avoid**: WAV (too large), AAC (limited support)

### Audio Properties:
- **Duration**: 3-8 seconds (optimal for welcome sound)
- **File Size**: Under 500KB (for fast loading)
- **Sample Rate**: 44.1kHz or 48kHz
- **Bit Rate**: 128-192 kbps (good quality vs. size balance)
- **Volume**: Normalize to -3dB to -6dB (prevents clipping)

### Content Suggestions:
- **Church bells** (classic, recognizable)
- **Soft hymn melody** (peaceful, welcoming)
- **Organ chord progression** (traditional church sound)
- **Acoustic guitar welcome** (modern, warm)
- **Gentle piano melody** (elegant, calming)

## Features Implemented

### 1. Smart Autoplay Handling
```javascript
// Handles modern browser autoplay restrictions
const playPromise = audio.play();
if (playPromise !== undefined) {
    playPromise.then(() => {
        // Success
    }).catch(error => {
        // Autoplay prevented - show notification
    });
}
```

### 2. User Preference Storage
- Uses `localStorage` to remember if user disabled sound
- Uses `sessionStorage` to track if sound played this session

### 3. Audio Control Button
- Fixed position button (top-right corner)
- Toggle between mute/unmute
- Visual feedback with icons and hover effects

### 4. Welcome Notifications
- Shows "Welcome to CityLife Church! 🎵" message
- Auto-dismisses after 5 seconds
- Can be closed manually

### 5. Responsive Design
- Adapts to mobile devices
- Maintains usability across screen sizes

## How to Add Your Audio Files

### Option 1: Free Audio Resources
1. **Freesound.org** - Search for "church bells" or "hymn"
2. **Zapsplat** - Professional sound effects (free account)
3. **BBC Sound Effects** - High-quality, royalty-free

### Option 2: Create Your Own
1. Record in your church during service
2. Use apps like GarageBand (Mac) or Audacity (free)
3. Edit to 3-8 seconds, fade in/out

### Option 3: Commission Custom Audio
1. Hire a musician to create a custom welcome melody
2. Use your church's existing recordings
3. Contact local music students for affordable options

## Installation Steps

1. **Add your audio file**:
   ```bash
   # Copy your audio file to:
   cp your-welcome-sound.mp3 public/assets/audio/welcome-sound.mp3
   ```

2. **Test the implementation**:
   - Visit the homepage
   - Check that the audio control button appears
   - Test mute/unmute functionality
   - Verify it only plays once per session

3. **Optional: Add OGG version**:
   ```bash
   # Convert MP3 to OGG for better browser support
   ffmpeg -i welcome-sound.mp3 welcome-sound.ogg
   ```

## Browser Compatibility

### Supported Browsers:
- ✅ Chrome 66+ (autoplay with user interaction)
- ✅ Firefox 69+ (autoplay with user interaction)  
- ✅ Safari 11+ (autoplay with user interaction)
- ✅ Edge 79+ (autoplay with user interaction)

### Autoplay Policies:
- **Desktop**: Usually allows autoplay after first user interaction
- **Mobile**: Stricter - often requires explicit user interaction
- **Solution**: Our implementation handles this gracefully

## Customization Options

### Change Audio Volume:
```javascript
audio.volume = 0.3; // 30% volume (current setting)
```

### Modify Welcome Message:
```javascript
function showWelcomeNotification(message = 'Your custom message here! 🎵')
```

### Adjust Button Position:
```css
.audio-control {
    top: 100px;    /* Distance from top */
    right: 20px;   /* Distance from right */
}
```

## Troubleshooting

### Audio Not Playing:
1. Check browser console for errors
2. Verify audio file exists and is accessible
3. Test with user interaction (click somewhere first)
4. Check browser autoplay settings

### Performance Issues:
1. Ensure audio file is under 500KB
2. Use MP3 format for best compatibility
3. Consider lazy loading for very large files

### Mobile Issues:
1. Test on actual devices (not just browser dev tools)
2. Consider shorter audio clips for mobile
3. Ensure button is touch-friendly (45px+ size)

The implementation is now ready! Just add your audio file and the welcome sound will work beautifully on your CityLife website.
