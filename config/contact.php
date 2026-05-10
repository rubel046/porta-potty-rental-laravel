<?php

return [
    'phone' => [
        'raw' => env('CONTACT_PHONE_RAW', '+18885550199'),
        'display' => env('CONTACT_PHONE_DISPLAY', '(888) 555-0199'),
    ],

    'hours' => env('CONTACT_HOURS_LABEL', '24/7 Emergency Service'),

    // Machine-readable hours used for schema markup and the hours-aware CTA.
    // Format: HH:MM (24-hour). Set CONTACT_HOURS_OPEN=00:00 and CONTACT_HOURS_CLOSE=23:59
    // for a truly 24/7 operation.
    'hours_open' => env('CONTACT_HOURS_OPEN', '07:00'),
    'hours_close' => env('CONTACT_HOURS_CLOSE', '20:00'),
    'timezone' => env('BUSINESS_TIMEZONE', 'America/Chicago'),

    'website' => env('APP_URL', 'https://pottydirect.com'),
];
