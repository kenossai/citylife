<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Mail;

try {
    Mail::raw('Test email from Laravel for Mailpit', function ($message) {
        $message->to('test@example.com')
            ->subject('Simple Test Email');
    });

    echo "✅ Email sent successfully!\n";
    echo "Check Mailpit at: http://localhost:8025\n";
} catch (Exception $e) {
    echo "❌ Email failed: " . $e->getMessage() . "\n";
}
