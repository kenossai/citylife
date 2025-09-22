<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\PastoralNotification;

echo "Recent Pastoral Notifications:\n";
echo "=" . str_repeat("=", 50) . "\n";

$notifications = PastoralNotification::latest()->take(4)->get();

foreach ($notifications as $notification) {
    echo "ID: {$notification->id}\n";
    echo "Type: {$notification->notification_type}\n";
    echo "Subject: {$notification->subject}\n";
    echo "To: {$notification->recipient_email}\n";
    echo "Message: " . substr($notification->message, 0, 100) . "...\n";
    echo "Sent: " . ($notification->sent_at ? 'Yes' : 'Pending') . "\n";
    echo "Created: {$notification->created_at}\n";
    echo "-" . str_repeat("-", 50) . "\n";
}
