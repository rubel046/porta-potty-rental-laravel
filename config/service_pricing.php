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
        'standard' => ['low' => 89,  'high' => 175],
        'deluxe' => ['low' => 150, 'high' => 275],
        'ada' => ['low' => 125, 'high' => 250],
        'luxury' => ['low' => 500, 'high' => 2500],
        'shower' => ['low' => 150, 'high' => 400],
        'mobile' => ['low' => 500, 'high' => 2500],
        'vip' => ['low' => 800, 'high' => 5000],
        'construction' => ['low' => 89,  'high' => 175],
        'holding' => ['low' => 300, 'high' => 800],
        'sanitizer' => ['low' => 45,  'high' => 125],
        'dumpster' => ['low' => 250, 'high' => 650],
        'septic' => ['low' => 200, 'high' => 500],
        'general' => ['low' => 89,  'high' => 300],

        // Plumbing
        'emergency' => ['low' => 150,  'high' => 500],
        'drain-cleaning' => ['low' => 150,  'high' => 500],
        'hydro-jetting' => ['low' => 250,  'high' => 600],
        'pipe-repair' => ['low' => 200,  'high' => 800],
        'leak-detection' => ['low' => 150,  'high' => 500],
        'slab-leak' => ['low' => 500,  'high' => 2500],
        'water-heater' => ['low' => 500,  'high' => 2500],
        'tankless-water-heater' => ['low' => 800,  'high' => 3500],
        'sewer-line' => ['low' => 300,  'high' => 6000],
        'trenchless-sewer' => ['low' => 2000, 'high' => 8000],
        'sewer-inspection' => ['low' => 150,  'high' => 500],
        'toilet-repair' => ['low' => 100,  'high' => 400],
        'faucet-repair' => ['low' => 100,  'high' => 350],
        'fixture-installation' => ['low' => 100,  'high' => 500],
        'garbage-disposal' => ['low' => 150,  'high' => 500],
        'gas-line' => ['low' => 200,  'high' => 1200],
        'water-main' => ['low' => 500,  'high' => 3000],
        'water-line' => ['low' => 300,  'high' => 2000],
        'sump-pump' => ['low' => 300,  'high' => 1200],
        'backflow-testing' => ['low' => 100,  'high' => 400],
        'water-filtration' => ['low' => 500,  'high' => 3000],
        'water-softener' => ['low' => 500,  'high' => 2500],
        'bathroom-remodel' => ['low' => 500,  'high' => 5000],
        'kitchen-plumbing' => ['low' => 200,  'high' => 2000],
        'commercial-plumbing' => ['low' => 300,  'high' => 5000],
        'new-construction' => ['low' => 500,  'high' => 10000],
        'septic' => ['low' => 300,  'high' => 5000],
        'well-pump' => ['low' => 500,  'high' => 3000],
        'pipe-thawing' => ['low' => 150,  'high' => 600],
        'radiant-heating' => ['low' => 500,  'high' => 4000],
    ],

    'fallback' => ['low' => 89, 'high' => 300],
];
