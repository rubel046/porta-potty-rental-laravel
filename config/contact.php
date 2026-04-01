<?php

return [
    'phone' => [
        'raw' => env('CONTACT_PHONE_RAW', '+18885550199'),
        'display' => env('CONTACT_PHONE_DISPLAY', '(888) 555-0199'),
    ],
    'hours' => '24/7 Emergency Service',
    'email' => env('CONTACT_EMAIL', 'info@pottydirect.com'),
    'website' => env('APP_URL', 'https://pottydirect.com'),
];
