<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Mail;

echo "Testing mail configuration...\n";

try {
    Mail::raw('This is a test email from Laravel CityLife application.', function($message) {
        $message->to('test@example.com')
                ->subject('Test Email from CityLife')
                ->from('noreply@citylife.local', 'CityLife Test');
    });

    echo "✅ Mail sent successfully!\n";
    echo "Check your MailHog interface at http://localhost:8025\n";

} catch (Exception $e) {
    echo "❌ Mail failed: " . $e->getMessage() . "\n";
}

// Also check if there are any recent mail-related logs
echo "\n📋 Checking recent logs...\n";
$logPath = storage_path('logs/laravel.log');
if (file_exists($logPath)) {
    $lines = file($logPath);
    $recentLines = array_slice($lines, -50); // Get last 50 lines
    $mailLines = array_filter($recentLines, function($line) {
        return stripos($line, 'mail') !== false || stripos($line, 'email') !== false;
    });

    if (!empty($mailLines)) {
        echo "Recent mail-related log entries:\n";
        foreach ($mailLines as $line) {
            echo $line;
        }
    } else {
        echo "No recent mail-related log entries found.\n";
    }
} else {
    echo "No log file found.\n";
}
