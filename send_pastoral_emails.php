<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\PastoralNotification;
use App\Mail\PastoralReminderMail;
use Illuminate\Support\Facades\Mail;

// Get pending notifications
$pendingNotifications = PastoralNotification::where('status', 'pending')->get();

echo "Found {$pendingNotifications->count()} pending pastoral notifications.\n";
echo "Sending emails...\n\n";

foreach ($pendingNotifications as $notification) {
    try {
        echo "Sending notification ID {$notification->id} to {$notification->recipient_email}...\n";

        // Send the email
        Mail::to($notification->recipient_email)->send(new PastoralReminderMail($notification));

        // Mark as sent
        $notification->markAsSent();

        echo "✅ Email sent successfully!\n";
        echo "Subject: {$notification->subject}\n";
        echo "Recipient: {$notification->recipient_email}\n\n";

    } catch (\Exception $e) {
        echo "❌ Failed to send notification ID {$notification->id}: " . $e->getMessage() . "\n\n";
        $notification->markAsFailed($e->getMessage());
    }
}

echo "Email sending process completed!\n";
