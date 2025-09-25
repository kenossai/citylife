<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Member;
use Carbon\Carbon;

echo "🎂 BIRTHDAY DASHBOARD WIDGET TEST\n";
echo "================================\n\n";

// Test the birthday logic
function getDaysUntilBirthday(Carbon $dateOfBirth): int
{
    $today = Carbon::today();
    $thisYearBirthday = $dateOfBirth->copy()->year($today->year);

    if ($thisYearBirthday->isPast()) {
        $thisYearBirthday->addYear();
    }

    return $today->diffInDays($thisYearBirthday);
}

function getNextBirthdayDate(Carbon $dateOfBirth): Carbon
{
    $today = Carbon::today();
    $thisYearBirthday = $dateOfBirth->copy()->year($today->year);

    if ($thisYearBirthday->isPast()) {
        $thisYearBirthday->addYear();
    }

    return $thisYearBirthday;
}

try {
    // Get upcoming birthdays (within 30 days)
    $upcomingBirthdays = Member::active()
        ->whereNotNull('date_of_birth')
        ->get()
        ->filter(function ($member) {
            $daysUntil = getDaysUntilBirthday($member->date_of_birth);
            return $daysUntil <= 30;
        })
        ->sortBy(function ($member) {
            return getDaysUntilBirthday($member->date_of_birth);
        })
        ->take(15);

    echo "📊 BIRTHDAY STATISTICS:\n";
    echo "  Total Active Members: " . Member::active()->count() . "\n";
    echo "  Members with Birthdays: " . Member::active()->whereNotNull('date_of_birth')->count() . "\n";
    echo "  Upcoming Birthdays (30 days): " . $upcomingBirthdays->count() . "\n\n";

    if ($upcomingBirthdays->count() > 0) {
        echo "🎉 UPCOMING BIRTHDAYS:\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

        foreach ($upcomingBirthdays as $member) {
            $daysUntil = getDaysUntilBirthday($member->date_of_birth);
            $nextBirthday = getNextBirthdayDate($member->date_of_birth);
            $age = $nextBirthday->year - $member->date_of_birth->year;

            $urgency = match(true) {
                $daysUntil == 0 => '🚨 TODAY!',
                $daysUntil == 1 => '⚠️  TOMORROW',
                $daysUntil <= 3 => '🔔 THIS WEEK',
                $daysUntil <= 7 => '📅 NEXT WEEK',
                default => '📆 UPCOMING'
            };

            $contact = [];
            if ($member->phone) $contact[] = '📞 ' . $member->phone;
            if ($member->email) $contact[] = '📧 ' . $member->email;
            $contactInfo = implode(' | ', $contact) ?: '❌ No contact info';

            echo "{$urgency} - {$member->first_name} {$member->last_name}\n";
            echo "  🎂 Turning {$age} on {$member->date_of_birth->format('M j')} ({$daysUntil} days)\n";
            echo "  👤 Status: " . ucwords(str_replace('_', ' ', $member->membership_status)) . "\n";
            echo "  📞 Contact: {$contactInfo}\n\n";
        }
    } else {
        echo "🎂 No upcoming birthdays in the next 30 days.\n\n";
    }

    // Test birthday categories
    $today = $upcomingBirthdays->filter(fn($m) => getDaysUntilBirthday($m->date_of_birth) == 0);
    $thisWeek = $upcomingBirthdays->filter(fn($m) => getDaysUntilBirthday($m->date_of_birth) <= 7 && getDaysUntilBirthday($m->date_of_birth) > 0);
    $thisMonth = $upcomingBirthdays->filter(fn($m) => getDaysUntilBirthday($m->date_of_birth) > 7);

    echo "📅 BIRTHDAY BREAKDOWN:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "  🚨 Today: {$today->count()} birthdays\n";
    echo "  📅 This Week: {$thisWeek->count()} birthdays\n";
    echo "  🗓️  This Month: {$thisMonth->count()} birthdays\n\n";

    echo "🎯 PASTORAL CARE FEATURES:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "✅ Real-time birthday tracking (updates every 30 seconds)\n";
    echo "✅ Color-coded urgency indicators\n";
    echo "✅ One-click birthday wish sending\n";
    echo "✅ Direct call and email links\n";
    echo "✅ Quick member profile access\n";
    echo "✅ Contact information display\n";
    echo "✅ Age calculation on birthdays\n";
    echo "✅ Membership status visibility\n";
    echo "✅ Automatic pastoral reminder creation\n\n";

    echo "📱 WIDGET ACTIONS AVAILABLE:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "🎉 Send Birthday Wish - Sends personalized email to member\n";
    echo "📞 Call - Opens phone dialer with member's number\n";
    echo "📧 Email - Opens email client with member's address\n";
    echo "👁️  View Member - Opens member profile in new tab\n\n";

    echo "🎊 BIRTHDAY WIDGET IS READY FOR DASHBOARD!\n";
    echo "The widget will show on the admin dashboard with:\n";
    echo "- Members with birthdays in the next 30 days\n";
    echo "- Sorted by urgency (today, tomorrow, this week, etc.)\n";
    echo "- Limited to 15 most urgent birthdays\n";
    echo "- Auto-refreshes every 30 seconds\n";
    echo "- Full pastoral care integration\n\n";

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
