<?php

return [
    'mode' => env('PAYPAL_MODE', 'sandbox'), // 'sandbox' or 'live'
    'sandbox_button_id' => env('PAYPAL_SANDBOX_BUTTON_ID', 'TEST_BUTTON_ID'),
    'live_button_id' => env('PAYPAL_LIVE_BUTTON_ID', ''),
    'test_username' => env('PAYPAL_TEST_USERNAME', ''),

    'sandbox_url' => 'https://www.sandbox.paypal.com/donate/?hosted_button_id=',
    'sandbox_paypal_me' => 'https://www.sandbox.paypal.com/paypalme/',
    'live_url' => 'https://www.paypal.com/donate/?hosted_button_id=',
    'live_paypal_me' => 'https://www.paypal.me/',
];
