<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Event;
use App\Models\News;
use App\Services\SocialMediaService;

echo "Social Media Integration Test\n";
echo "=============================\n\n";

// Test the service
$service = new SocialMediaService();

// Get a sample event
$event = Event::published()->first();
if ($event) {
    echo "📅 Testing Event Content Formatting:\n";
    echo "Event: {$event->title}\n";

    try {
        $content = $service->formatEventContent($event);
        echo "✅ Content formatted successfully\n";
        echo "Preview:\n" . str_repeat("-", 50) . "\n";
        echo $content['text'] . "\n";
        echo str_repeat("-", 50) . "\n\n";
    } catch (Exception $e) {
        echo "❌ Error formatting event content: " . $e->getMessage() . "\n\n";
    }
} else {
    echo "⚠️  No published events found for testing\n\n";
}

// Get a sample news item
$news = News::published()->first();
if ($news) {
    echo "📰 Testing News Content Formatting:\n";
    echo "Article: {$news->title}\n";

    try {
        $content = $service->formatAnnouncementContent($news);
        echo "✅ Content formatted successfully\n";
        echo "Preview:\n" . str_repeat("-", 50) . "\n";
        echo $content['text'] . "\n";
        echo str_repeat("-", 50) . "\n\n";
    } catch (Exception $e) {
        echo "❌ Error formatting news content: " . $e->getMessage() . "\n\n";
    }
} else {
    echo "⚠️  No published news found for testing\n\n";
}

// Test configuration
echo "🔧 Configuration Status:\n";
$platforms = ['facebook', 'twitter', 'instagram', 'linkedin'];

foreach ($platforms as $platform) {
    $enabled = config("services.{$platform}.enabled", false);
    $status = $enabled ? "✅ Enabled" : "⚪ Disabled";
    echo "- " . ucfirst($platform) . ": {$status}\n";
}

echo "\n📊 Social Media Posts Summary:\n";
echo "- Total Posts: " . \App\Models\SocialMediaPost::count() . "\n";
echo "- Published: " . \App\Models\SocialMediaPost::where('status', 'published')->count() . "\n";
echo "- Failed: " . \App\Models\SocialMediaPost::where('status', 'failed')->count() . "\n";
echo "- Scheduled: " . \App\Models\SocialMediaPost::where('status', 'scheduled')->count() . "\n";

echo "\n🚀 Setup Instructions:\n";
echo "1. Copy settings from .env.social-media.example to your .env file\n";
echo "2. Configure your social media platform credentials\n";
echo "3. Set SOCIAL_MEDIA_AUTO_POST_ENABLED=true to enable auto-posting\n";
echo "4. Use the admin panel to manage social media posts\n";
echo "5. Run 'php artisan social-media:process-scheduled' for scheduled posts\n";

echo "\n✨ Social Media Integration is ready to use!\n";
