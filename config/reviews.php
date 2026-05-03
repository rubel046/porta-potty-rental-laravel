<?php

/*
|--------------------------------------------------------------------------
| Aggregate Review Rating
|--------------------------------------------------------------------------
|
| Controls whether the site emits AggregateRating / Review schema markup.
|
| GOOGLE POLICY: Review-snippet guidelines prohibit marking up invented
| testimonials as Review/AggregateRating. Until you have a defensible
| source of real reviews (Google Business Profile export, verified
| on-site reviews, etc.), leave REVIEWS_COUNT unset. Schema will not be
| emitted. AI-generated testimonials can still display on pages as
| "example scenarios" — just not in schema markup.
|
| Set REVIEWS_COUNT only with a number you can prove.
|
*/

return [
    'rating' => (float) env('REVIEWS_RATING', 4.9),

    // null => NO AggregateRating schema emitted anywhere. This is the safe default.
    'count' => env('REVIEWS_COUNT') !== null ? (int) env('REVIEWS_COUNT') : null,

    'best_rating' => 5,
];
