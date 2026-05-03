<?php

/*
|--------------------------------------------------------------------------
| Service Price Ranges (USD)
|--------------------------------------------------------------------------
|
| Used in AggregateOffer JSON-LD schema and the SEO meta-description hook
| on city/service pages.
|
| IMPORTANT: publishing prices that don't match what a caller hears is a
| conversion killer AND a Google trust signal problem. Keep 'enabled'=false
| until you have verified ranges. When enabled, these show up in both
| schema.org markup (AggregateOffer) and your SERP meta descriptions
| ("From $89/day").
|
| To enable:
|   1. Research actual market prices (call 3 competitors)
|   2. Update the ranges below with YOUR pricing
|   3. Set SERVICE_PRICING_SCHEMA=true in .env
|
*/

return [
    // Master switch. Off => no AggregateOffer schema, no price hint in meta.
    'enabled' => filter_var(env('SERVICE_PRICING_SCHEMA', false), FILTER_VALIDATE_BOOLEAN),

    'unit' => 'USD',
    'period' => 'day',

    'ranges' => [
        'standard'     => ['low' => 89,  'high' => 175],
        'deluxe'       => ['low' => 150, 'high' => 275],
        'ada'          => ['low' => 125, 'high' => 250],
        'luxury'       => ['low' => 500, 'high' => 2500],
        'shower'       => ['low' => 150, 'high' => 400],
        'mobile'       => ['low' => 500, 'high' => 2500],
        'vip'          => ['low' => 800, 'high' => 5000],
        'construction' => ['low' => 89,  'high' => 175],
        'holding'      => ['low' => 300, 'high' => 800],
        'sanitizer'    => ['low' => 45,  'high' => 125],
        'dumpster'     => ['low' => 250, 'high' => 650],
        'septic'       => ['low' => 200, 'high' => 500],
        'general'      => ['low' => 89,  'high' => 300],
    ],

    'fallback' => ['low' => 89, 'high' => 300],
];
