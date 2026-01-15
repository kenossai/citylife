<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Spam Protection Settings
    |--------------------------------------------------------------------------
    |
    | Configure spam protection for contact forms and other user submissions
    |
    */

    // Enable/disable spam protection features
    'enabled' => env('SPAM_PROTECTION_ENABLED', true),

    // Honeypot field names (these should be hidden in forms)
    'honeypot_fields' => ['website', 'url', 'homepage'],

    // Minimum time (in seconds) required to fill out a form
    'minimum_form_time' => 3,

    // Maximum submissions per hour from the same IP
    'rate_limit_per_hour' => 3,

    // Maximum URLs allowed in a message
    'max_urls_in_message' => 2,

    // Blocked IP addresses
    'blocked_ips' => [
        '37.139.53.35',
        '107.189.30.236' // Spam detected on Jan 14, 2026
        // Add more IPs as you identify them
    ],

    // Disposable/temporary email domains to block
    'disposable_email_domains' => [
        '10minutemail.com', '10minutemail.net', '10minutemail.org',
        'guerrillamail.com', 'guerrillamail.net', 'guerrillamailblock.com',
        'mailinator.com', 'mailinator.net', 'mailinator2.com',
        'tempmail.com', 'tempmail.net', 'temp-mail.org', 'temp-mail.de',
        'throwaway.email', 'trashmail.com', 'trashmail.net',
        'yopmail.com', 'yopmail.net', 'yopmail.fr',
        'fakeinbox.com', 'fakemailgenerator.com',
        'sharklasers.com', 'grr.la',
        'maildrop.cc', 'mailnesia.com', 'mailcatch.com',
        'getnada.com', 'getairmail.com',
        'mintemail.com', 'mytemp.email', 'mytempmail.com',
        'dispostable.com', 'emailondeck.com',
        'burnermail.io', 'temp-mail.io',
        'disposablemail.com', 'spam4.me',
        'harakirimail.com', 'jetable.org',
        'mohmal.com', 'tmails.net',
    ],

    // Suspicious content patterns (regex)
    'suspicious_patterns' => [
        // SEO spam
        'https?:\/\/proff?seo\.ru',
        'https?:\/\/.*\.ru\/prodvizhenie',
        'SEO.*promotion',
        'купить|продвижение|рейтинг',

        // URL shorteners (commonly used in spam)
        'bit\.ly|tinyurl|goo\.gl|ow\.ly|t\.co|buff\.ly|is\.gd|cli\.gs',
        'u\.to|adf\.ly|bc\.vc|shorturl|tiny\.cc|cutt\.ly|rebrand\.ly',

        // Gambling/casino spam
        'casino|slot|poker|roulette|blackjack',
        'spin.*win|jackpot|lottery|betting',
        'reel.*cash|vault.*wins|prize.*wheel',

        // Crypto/investment spam
        'bitcoin|crypto|forex|trading|investment.*opportunity',
        'get.*rich|make.*money.*fast|passive.*income',
        'NFT|defi|web3.*opportunity',

        // Pharma spam
        'viagra|cialis|pharmacy|pills|medication',
        'weight.*loss|diet.*pills',

        // Generic spam patterns
        'click.*here.*now|limited.*time.*offer|act.*now',
        'congratulations.*won|claim.*prize|free.*gift',
        'your.*wallet.*deserves|earn.*extra.*income',
        'work.*from.*home|business.*opportunity',

        // Excessive URLs (more than 3 URLs is suspicious)
        '(https?:\/\/[^\s]+.*){3,}',
    ],

    // Suspicious email patterns
    'suspicious_email_patterns' => [
        // Random character emails
        '^[a-z]{1,3}\d+@',
        // All numbers before @
        '^\d+@',
    ],

    // Log all spam attempts
    'log_spam_attempts' => true,

    // Notify admin of spam attempts (daily digest)
    'notify_admin' => false,
];
