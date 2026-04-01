<?php

namespace App\Services;

use App\Models\City;

class ContentGeneratorService
{
    /**
     * শহরের জন্য সম্পূর্ণ সার্ভিস পেজ কন্টেন্ট জেনারেট করুন
     */
    public function generateServicePageContent(City $city, string $serviceType = 'general'): array
    {
        $state = $city->state;
        $cityName = $city->name;
        $stateCode = $state->code;
        $stateName = $state->name;
        $nearbyAreas = $city->getNearbyAreaNames();
        $nearbyText = implode(', ', array_slice($nearbyAreas, 0, 8));

        return match ($serviceType) {
            'general' => $this->generalContent($city, $cityName, $stateCode, $stateName, $nearbyText),
            'construction' => $this->constructionContent($city, $cityName, $stateCode, $stateName, $nearbyText),
            'wedding' => $this->weddingContent($city, $cityName, $stateCode, $stateName, $nearbyText),
            'event' => $this->eventContent($city, $cityName, $stateCode, $stateName, $nearbyText),
            default => $this->generalContent($city, $cityName, $stateCode, $stateName, $nearbyText),
        };
    }

    protected function generalContent(City $city, string $cityName, string $stateCode, string $stateName, string $nearbyText): array
    {
        $population = $city->population ? number_format($city->population) : 'a growing';
        $climate = $city->climate_info ?? 'a climate that supports year-round outdoor activities';
        $localEvents = $city->local_events ?? 'numerous community events, festivals, and outdoor gatherings throughout the year';
        $constructionInfo = $city->construction_info ?? 'a thriving construction industry with ongoing residential and commercial projects';

        $slug = "porta-potty-rental-{$city->slug}";

        $content = <<<CONTENT
## Reliable Porta Potty Rental Service in {$cityName}, {$stateCode}

Looking for a **porta potty rental in {$cityName}, {$stateCode}**? You've come to the right place. We provide clean, well-maintained portable toilet rentals for construction sites, outdoor events, weddings, parties, and more throughout the {$cityName} metropolitan area.

With a population of {$population} residents, {$cityName} is a vibrant city with {$climate}. Whether you're managing a construction project, planning an outdoor wedding, or organizing a community festival, having adequate portable sanitation is essential for the comfort and safety of your workers and guests.

## Portable Toilet Rental Options Available in {$cityName}

We offer a comprehensive range of portable restroom solutions to meet every need and budget in the {$cityName} area:

### Standard Porta Potty Units
Our standard portable toilets are the most popular choice for **construction sites and basic outdoor events in {$cityName}**. Each unit comes equipped with:
- Non-splash urinal
- Toilet seat with cover
- Hand sanitizer dispenser
- Interior lock indicator
- Ventilation system for odor control
- **Starting from \$89/week in {$cityName}**

### Deluxe Flushable Portable Restrooms
For events where you want to provide a more comfortable experience, our deluxe flushable units are the perfect choice for {$cityName} events:
- Flushing toilet mechanism
- Built-in hand wash station with soap
- Interior mirror
- Coat hook
- Enhanced ventilation
- **Starting from \$149/week in {$cityName}**

### ADA-Compliant Accessible Units
We provide fully ADA-compliant portable restrooms to ensure accessibility for all guests at your {$cityName} event or job site:
- Extra-wide door for wheelchair access
- Interior grab bars
- Lowered toilet seat height
- Spacious interior (meets ADA requirements)
- Non-slip flooring
- **Starting from \$175/week in {$cityName}**

### Luxury Restroom Trailers
For upscale events in {$cityName}, our luxury restroom trailers provide a premium experience:
- Climate-controlled interior
- Porcelain fixtures
- Hardwood-style flooring
- Vanity mirrors with lighting
- Premium hand soap and paper towels
- **Call for custom pricing in {$cityName}**

## Who Needs Porta Potty Rental in {$cityName}, {$stateCode}?

### Construction Companies & Contractors
{$cityName} has {$constructionInfo}. OSHA regulations require adequate sanitation facilities on construction sites. Our **construction porta potty rental in {$cityName}** includes:
- Weekly servicing and cleaning
- OSHA-compliant units
- Flexible rental terms (weekly, monthly, long-term)
- Fast delivery to any job site in the {$cityName} area

### Event Planners & Organizers
{$cityName} hosts {$localEvents}. Our event portable restroom rental service ensures your guests have access to clean, comfortable facilities. We provide:
- Multiple unit packages for events of any size
- Delivery and setup before your event
- Pickup after your event concludes
- Hand wash stations available

### Wedding Planners & Couples
Outdoor weddings are increasingly popular in {$cityName}. Don't let inadequate restroom facilities ruin your special day. Our **wedding porta potty rental in {$cityName}** offers:
- Elegant deluxe and luxury options
- Units that complement your wedding aesthetic
- Delivery and setup the day before
- Attendant service available

### Homeowners & DIY Projects
Planning a home renovation or hosting a large backyard party in {$cityName}? A portable toilet rental keeps your indoor bathrooms clean and provides convenience for workers and guests.

## Porta Potty Rental Pricing in {$cityName}, {$stateCode}

| Unit Type | Daily Rate | Weekly Rate | Monthly Rate |
|-----------|-----------|-------------|--------------|
| Standard | \$99-\$150 | \$89-\$175 | \$250-\$400 |
| Deluxe Flushable | \$150-\$250 | \$149-\$300 | \$400-\$650 |
| ADA Accessible | \$175-\$275 | \$175-\$350 | \$450-\$700 |
| Luxury Trailer | Call | Call | Call |

*Prices vary based on location, duration, and quantity. Delivery and pickup fees may apply. **Call for an exact quote for your {$cityName} location.***

## How Our Porta Potty Rental Works in {$cityName}

Getting a portable toilet delivered to your {$cityName} location is simple:

**Step 1: Call Us for a Free Quote**
Tell us about your needs — how many units, what type, delivery location in {$cityName}, and rental duration. We'll provide an instant quote over the phone.

**Step 2: We Deliver & Set Up**
Our team delivers clean, sanitized portable toilets to your {$cityName} location. We handle all setup and placement. Same-day delivery is available when you call before 2 PM.

**Step 3: We Service & Maintain**
For weekly and monthly rentals, we provide regular servicing that includes pumping, cleaning, sanitizing, and restocking supplies. Your units stay fresh and clean throughout your rental period.

**Step 4: We Pick Up**
When your rental period ends, we handle all pickup and removal. No hassle, no mess.

## Service Areas Near {$cityName}, {$stateCode}

We proudly serve {$cityName} and all surrounding communities, including {$nearbyText}, and more. No matter where you are in the greater {$cityName} metropolitan area, we can deliver portable toilets to your location.

## Why Choose Our Porta Potty Rental Service in {$cityName}?

✅ **Same-Day Delivery Available** — Call before 2 PM for same-day service in {$cityName}
✅ **Clean, Sanitized Units** — Every portable toilet is professionally cleaned and sanitized before delivery
✅ **Competitive Pricing** — Affordable rates for {$cityName} with no hidden fees
✅ **Weekly Servicing Included** — Regular cleaning and maintenance at no extra charge
✅ **Licensed & Insured** — Full coverage for your peace of mind
✅ **Flexible Rental Terms** — Daily, weekly, monthly, or long-term rentals available
✅ **Wide Selection** — Standard, deluxe, ADA, and luxury options
✅ **Local Service** — We know {$cityName} and can recommend the right solution for your needs

## Porta Potty Rental Tips for {$cityName} Residents

1. **Book Early for Peak Season** — Summer months (May-September) are the busiest time for porta potty rentals in {$cityName}. Book at least 1-2 weeks in advance during peak season.

2. **Know How Many You Need** — A general rule is 1 standard unit per 50 guests for a 4-hour event, or 1 unit per 10 workers on a construction site.

3. **Consider Placement** — Place units on level ground, away from food areas but accessible to guests. Our delivery team can help you determine the best placement at your {$cityName} location.

4. **Ask About Hand Wash Stations** — Adding hand wash stations improves hygiene and guest satisfaction at events.

5. **Plan for ADA Compliance** — If your event in {$cityName} is open to the public, you may be required to provide ADA-accessible units.

## Ready to Rent a Porta Potty in {$cityName}, {$stateCode}?

Don't wait until the last minute. **Call us now** for a free, no-obligation quote on porta potty rental in {$cityName}. Our friendly team is ready to help you find the perfect portable sanitation solution for your needs.

Whether you need one unit for a small home project or dozens for a large {$cityName} event, we've got you covered with clean, reliable, affordable portable toilet rentals.
CONTENT;

        return [
            'slug' => $slug,
            'h1_title' => "Porta Potty Rental in {$cityName}, {$stateCode} — Same-Day Delivery Available",
            'meta_title' => "Porta Potty Rental {$cityName} {$stateCode} | Same-Day Delivery | Free Quote",
            'meta_description' => "Need a porta potty rental in {$cityName}, {$stateCode}? Same-day delivery available. Clean portable toilets for construction, events & weddings. Call now for a free quote!",
            'content' => $content,
            'word_count' => str_word_count($content),
            'service_type' => 'general',
        ];
    }

    protected function constructionContent(City $city, string $cityName, string $stateCode, string $stateName, string $nearbyText): array
    {
        $slug = "construction-porta-potty-rental-{$city->slug}";

        $content = <<<CONTENT
## Construction Site Porta Potty Rental in {$cityName}, {$stateCode}

Need **portable toilets for your construction site in {$cityName}**? We provide OSHA-compliant porta potty rentals specifically designed for job sites throughout the {$cityName} metropolitan area. Keep your crew comfortable, your site compliant, and your project on track.

## OSHA Portable Toilet Requirements for {$cityName} Construction Sites

The Occupational Safety and Health Administration (OSHA) requires employers to provide toilet facilities for all employees. Here are the key requirements for construction sites in {$cityName}, {$stateCode}:

| Number of Workers | Minimum Toilets Required |
|-------------------|-------------------------|
| 1-20 workers | 1 toilet |
| 21-40 workers | 2 toilets |
| 41-60 workers | 3 toilets |
| 61-80 workers | 4 toilets |
| 81-100 workers | 5 toilets |
| Over 100 | 1 additional per 40 workers |

**Failure to comply with OSHA sanitation requirements can result in fines ranging from \$1,000 to \$70,000 per violation.** Don't risk it — call us for compliant portable toilet rental in {$cityName}.

## Our Construction Porta Potty Rental Services in {$cityName}

### Standard Job Site Units
- Heavy-duty construction-grade portable toilets
- Built to withstand daily use by construction crews
- Non-splash urinal and toilet seat
- Hand sanitizer dispenser
- Ventilation system
- **From \$89/week with weekly servicing in {$cityName}**

### High-Rise & Multi-Story Options
For multi-story construction projects in {$cityName}, we offer units that can be crane-lifted to upper floors:
- Lightweight yet durable construction
- Crane-liftable design
- Compact footprint for tight spaces

### Hand Wash Stations
Add portable hand wash stations to your {$cityName} job site:
- Foot-pump operated
- Fresh water supply
- Soap dispenser
- Paper towel holder
- **From \$45/week**

## Construction Rental Packages for {$cityName} Contractors

| Package | Includes | Weekly Rate |
|---------|----------|-------------|
| Solo Contractor | 1 Standard Unit + Weekly Service | \$89-\$125 |
| Small Crew (5-20) | 1-2 Units + Hand Wash + Weekly Service | \$150-\$250 |
| Medium Site (20-50) | 2-3 Units + Hand Wash + 2x Weekly Service | \$275-\$450 |
| Large Site (50-100) | 5+ Units + Hand Wash + 3x Weekly Service | Call for Quote |

## Why {$cityName} Contractors Choose Us

✅ **OSHA Compliant Units** — Avoid costly fines
✅ **Reliable Weekly Servicing** — Clean units keep workers happy
✅ **Flexible Terms** — Week-to-week, no long-term contracts required
✅ **Fast Delivery** — Same-day delivery available in {$cityName}
✅ **Job Site Placement** — We work with your foreman on optimal placement
✅ **Volume Discounts** — Multi-unit and long-term discounts available
✅ **Serving All of {$cityName}** — Including {$nearbyText}

## How to Order Construction Porta Potties in {$cityName}

1. **Call us** with your job site address in {$cityName}, number of workers, and expected project duration
2. We recommend the right number and type of units
3. Units delivered and placed at your convenience
4. Regular servicing keeps everything clean and stocked
5. Pickup when your project is complete

**Call now for a free construction site porta potty quote in {$cityName}, {$stateCode}.**
CONTENT;

        return [
            'slug' => $slug,
            'h1_title' => "Construction Site Porta Potty Rental in {$cityName}, {$stateCode}",
            'meta_title' => "Construction Porta Potty Rental {$cityName} {$stateCode} | OSHA Compliant | Weekly Service",
            'meta_description' => "OSHA compliant portable toilet rental for construction sites in {$cityName}, {$stateCode}. Weekly servicing included. Fast delivery. Call for contractor pricing!",
            'content' => $content,
            'word_count' => str_word_count($content),
            'service_type' => 'construction',
        ];
    }

    protected function weddingContent(City $city, string $cityName, string $stateCode, string $stateName, string $nearbyText): array
    {
        $slug = "wedding-porta-potty-rental-{$city->slug}";

        $content = <<<CONTENT
## Wedding Portable Restroom Rental in {$cityName}, {$stateCode}

Planning an outdoor wedding in {$cityName}? Don't let restroom logistics stress you out. Our **wedding porta potty rental service in {$cityName}** provides elegant, clean portable restroom solutions that your guests will actually appreciate.

## Why You Need Portable Restrooms for Your {$cityName} Wedding

If your wedding venue in {$cityName} is outdoors — a barn, vineyard, garden, farm, or private property — you likely need portable restroom facilities. Even indoor venues sometimes need supplemental restrooms for large guest counts.

**Signs you need wedding portable restrooms:**
- Your venue is outdoors with no permanent restrooms
- Your venue's restrooms can't handle your guest count
- You want to keep venue restrooms clean for the bridal party
- Your ceremony and reception are in different locations
- You're hosting a multi-day wedding weekend in {$cityName}

## Wedding Restroom Options in {$cityName}

### Luxury Restroom Trailers (Most Popular for Weddings)
Our premium restroom trailers are designed to match the elegance of your {$cityName} wedding:
- **Climate-controlled** interior (A/C and heat)
- Porcelain toilets and sinks
- Hardwood-style flooring
- Vanity mirrors with professional lighting
- Fresh flower vase holders
- Premium hand soap, lotion, and towels
- Separate men's and women's sides
- Exterior lighting for evening events
- **Perfect for {$cityName} weddings with 50-300+ guests**

### Deluxe Flushable Units
A step above standard units, ideal for more casual {$cityName} weddings:
- Flushing toilet
- Built-in sink with running water
- Interior mirror
- Can be decorated to match your theme
- **Great for intimate {$cityName} weddings with 25-75 guests**

## How Many Restrooms for Your {$cityName} Wedding?

| Guest Count | Recommended Units | Best Option |
|-------------|-------------------|-------------|
| 25-50 | 1 Luxury Trailer or 2 Deluxe | Luxury Trailer |
| 50-100 | 1 Large Luxury Trailer or 3 Deluxe | Luxury Trailer |
| 100-150 | 2 Luxury Trailers or 4-5 Deluxe | 2 Luxury Trailers |
| 150-250 | 2-3 Luxury Trailers | Luxury Trailers |
| 250+ | 3+ Luxury Trailers | Call for Custom Plan |

*Tip: If alcohol is being served at your {$cityName} wedding, add 15-20% more restroom capacity.*

## Wedding Porta Potty Rental Tips for {$cityName} Couples

1. **Book 3-6 months in advance** — Wedding season in {$cityName} fills up fast, especially for luxury trailers
2. **Schedule delivery for the day before** — Gives you time to inspect and decorate
3. **Consider placement carefully** — Close enough to be convenient, far enough to be discreet
4. **Add a restroom attendant** — Keeps facilities pristine throughout your celebration
5. **Decorate the exterior** — Flowers, signage, and lighting can make portable restrooms blend with your wedding decor
6. **Don't forget the rehearsal dinner** — If it's also outdoors in {$cityName}, you may need units for that too

## Serving {$cityName} Wedding Venues

We deliver to all wedding venues in the {$cityName} area, including {$nearbyText}, and surrounding communities. Whether your venue is a downtown rooftop, a rural farm, or a lakeside estate, we can accommodate your needs.

**Call now to discuss your {$cityName} wedding restroom needs. We'll help you choose the perfect option for your special day.**
CONTENT;

        return [
            'slug' => $slug,
            'h1_title' => "Wedding Portable Restroom Rental in {$cityName}, {$stateCode}",
            'meta_title' => "Wedding Porta Potty Rental {$cityName} {$stateCode} | Luxury Restroom Trailers",
            'meta_description' => "Elegant portable restroom rental for weddings in {$cityName}, {$stateCode}. Luxury trailers & deluxe units. Make your outdoor wedding comfortable. Call for wedding packages!",
            'content' => $content,
            'word_count' => str_word_count($content),
            'service_type' => 'wedding',
        ];
    }

    protected function eventContent(City $city, string $cityName, string $stateCode, string $stateName, string $nearbyText): array
    {
        $slug = "event-porta-potty-rental-{$city->slug}";

        $content = <<<CONTENT
## Event Porta Potty Rental in {$cityName}, {$stateCode}

Hosting an outdoor event in {$cityName}? From music festivals and community fairs to corporate picnics and sporting events, we provide **portable toilet rentals for events of every size** throughout the {$cityName} area.

## Event Types We Serve in {$cityName}

### Festivals & Community Events
{$cityName} is known for its vibrant community events. We provide portable sanitation for:
- Music festivals and concerts
- Food festivals and fairs
- Art shows and cultural events
- Community celebrations
- Holiday events (4th of July, Labor Day, etc.)

### Corporate Events
- Company picnics and team building events
- Outdoor conferences and meetings
- Grand openings and ribbon cuttings
- Employee appreciation events

### Sporting Events
- Tournaments and competitions
- 5K runs and marathons
- Little League and youth sports
- Tailgating events

### Private Parties
- Birthday parties and celebrations
- Family reunions and cookouts
- Graduation parties
- Block parties and neighborhood events

## How Many Porta Potties for Your {$cityName} Event?

| Event Size | Duration 4 Hours | Duration 8 Hours | Duration Full Day |
|------------|-------------------|-------------------|-------------------|
| 25 guests | 1 unit | 1-2 units | 2 units |
| 50 guests | 1-2 units | 2-3 units | 3 units |
| 100 guests | 2-3 units | 3-4 units | 4-5 units |
| 250 guests | 5-6 units | 7-8 units | 9-10 units |
| 500 guests | 10-12 units | 14-16 units | 18-20 units |
| 1000+ guests | Call for custom plan | Call for custom plan | Call for custom plan |

*If alcohol is served, increase by 20%. If food is served, increase by 10%.*

## Event Rental Packages in {$cityName}

### Small Event Package (up to 50 guests)
- 2 Standard Units OR 1 Deluxe Unit
- 1 Hand Wash Station
- Delivery, setup, and pickup included
- **From \$275 for a single-day event in {$cityName}**

### Medium Event Package (50-150 guests)
- 3-5 Standard Units + 1 ADA Unit
- 2 Hand Wash Stations
- Delivery, setup, and pickup included
- **From \$550 for a single-day event in {$cityName}**

### Large Event Package (150-500 guests)
- 8-15 Units (mix of Standard, Deluxe, ADA)
- 4+ Hand Wash Stations
- On-site servicing for multi-day events
- **Call for custom pricing in {$cityName}**

### Festival Package (500+ guests)
- Custom unit count based on attendance
- Luxury restroom trailers available
- Dedicated on-site attendants
- Multi-day servicing schedule
- **Call for festival pricing in {$cityName}**

## Event Planning Tips for {$cityName}

1. **Book 2-4 weeks in advance** — Especially during {$cityName}'s busy event season (April-October)
2. **Provide clear delivery instructions** — Include venue address, gate codes, and placement preferences
3. **Plan for peak usage times** — During intermissions, halftime, or meal times, restroom usage spikes
4. **Add hand wash stations** — Health departments may require them for events with food service
5. **Consider ADA units** — Public events in {$cityName} should include accessible restrooms
6. **Ask about lighting** — For evening events, ensure restroom areas are well-lit

## Service Areas for Events Near {$cityName}

We deliver portable toilets for events throughout the {$cityName} metropolitan area, including {$nearbyText}, and all surrounding communities. No event is too big or too small.

**Call now to get a free event porta potty quote for your {$cityName} event!**
CONTENT;

        return [
            'slug' => $slug,
            'h1_title' => "Event Porta Potty Rental in {$cityName}, {$stateCode}",
            'meta_title' => "Event Portable Toilet Rental {$cityName} {$stateCode} | Festivals, Parties & More",
            'meta_description' => "Portable toilet rental for events in {$cityName}, {$stateCode}. Festivals, parties, corporate events & more. Multiple unit packages available. Call for event pricing!",
            'content' => $content,
            'word_count' => str_word_count($content),
            'service_type' => 'event',
        ];
    }

    /**
     * শহরের জন্য FAQs জেনারেট করুন
     */
    public function generateFaqs(City $city, ?string $serviceType = null): array
    {
        $cityName = $city->name;
        $stateCode = $city->state->code;

        $faqs = [
            [
                'question' => "How much does a porta potty rental cost in {$cityName}, {$stateCode}?",
                'answer' => "Porta potty rental prices in {$cityName} typically range from \$89-\$175 per week for standard units, \$149-\$300 for deluxe flushable units, and \$175-\$350 for ADA accessible units. Luxury restroom trailers are available at custom pricing. Call us for an exact quote based on your specific needs and location in {$cityName}.",
            ],
            [
                'question' => "Do you offer same-day porta potty delivery in {$cityName}?",
                'answer' => "Yes! We offer same-day delivery in {$cityName} and surrounding areas when you call before 2 PM. Subject to availability. For guaranteed delivery, we recommend booking at least 24-48 hours in advance.",
            ],
            [
                'question' => "How often are porta potties serviced in {$cityName}?",
                'answer' => "For weekly and monthly rentals in {$cityName}, our standard service includes once-per-week cleaning, pumping, sanitizing, and restocking of supplies. For high-traffic locations or events, we offer twice-weekly or daily servicing at additional cost.",
            ],
            [
                'question' => "How many porta potties do I need for my event in {$cityName}?",
                'answer' => "As a general guideline: 1 unit per 50 guests for a 4-hour event, or 1 unit per 25 guests for an 8-hour event. If alcohol is being served, add 20% more units. For construction sites, OSHA requires 1 unit per 20 workers. Call us and we'll help you determine the right number for your {$cityName} event.",
            ],
            [
                'question' => "What areas do you serve near {$cityName}?",
                'answer' => "We serve {$cityName} and all surrounding communities in the greater {$cityName} metropolitan area. This includes ".implode(', ', array_slice($city->getNearbyAreaNames(), 0, 6)).', and more. Call us to confirm delivery to your specific location.',
            ],
            [
                'question' => "Do you provide ADA-accessible portable restrooms in {$cityName}?",
                'answer' => "Yes, we offer fully ADA-compliant portable restrooms in {$cityName}. These units feature extra-wide doors for wheelchair access, interior grab bars, lowered seats, and spacious interiors that meet all ADA requirements. Public events may be required to include accessible units.",
            ],
            [
                'question' => "Can I rent a porta potty for just one day in {$cityName}?",
                'answer' => "Absolutely! We offer daily, weekend, weekly, and monthly rental options in {$cityName}. Single-day rentals are perfect for parties, events, and special occasions. Call us for single-day pricing in your area.",
            ],
            [
                'question' => "What is included in the porta potty rental price in {$cityName}?",
                'answer' => "Our rental price in {$cityName} includes delivery, setup, pickup, and for weekly/monthly rentals, regular servicing (pumping, cleaning, sanitizing, and restocking toilet paper and hand sanitizer). There are no hidden fees — the price we quote is the price you pay.",
            ],
        ];

        return $faqs;
    }

    /**
     * শহরের জন্য Testimonials জেনারেট করুন
     */
    public function generateTestimonials(City $city): array
    {
        $cityName = $city->name;

        $firstNames = ['Mike', 'Sarah', 'David', 'Jennifer', 'Robert', 'Lisa', 'James', 'Amanda', 'Chris', 'Emily'];
        $lastInitials = ['R', 'T', 'M', 'K', 'S', 'W', 'J', 'B', 'H', 'L'];
        $titles = [
            'General Contractor', 'Event Planner', 'Project Manager',
            'Wedding Coordinator', 'Property Manager', 'Homeowner',
            'Festival Organizer', 'Construction Foreman', 'Business Owner', 'Party Planner',
        ];

        $reviews = [
            [
                'content' => "Ordered 5 units for our construction site in {$cityName}. Delivered same day, spotlessly clean. Weekly servicing has been reliable. Best porta potty rental company we've used!",
                'rating' => 5,
                'service_type' => 'construction',
            ],
            [
                'content' => "Used their deluxe units for our outdoor wedding in {$cityName}. Guests were genuinely impressed! The units were immaculate and the delivery team was professional. Highly recommend!",
                'rating' => 5,
                'service_type' => 'wedding',
            ],
            [
                'content' => "We've been renting from them for our annual {$cityName} community festival for 3 years now. Always on time, always clean, always professional. Great pricing too!",
                'rating' => 5,
                'service_type' => 'event',
            ],
            [
                'content' => "Needed a last-minute porta potty for a home renovation project in {$cityName}. Called at 10 AM, had a unit delivered by 2 PM. Excellent service!",
                'rating' => 5,
                'service_type' => 'general',
            ],
            [
                'content' => "Rented 8 units for a company picnic in {$cityName}. Everything was set up perfectly. The hand wash stations were a great addition. Will definitely use again!",
                'rating' => 5,
                'service_type' => 'event',
            ],
            [
                'content' => "As a general contractor working across {$cityName}, I need a reliable porta potty provider. These guys deliver — literally and figuratively. Fair prices, clean units, no hassle.",
                'rating' => 5,
                'service_type' => 'construction',
            ],
        ];

        $testimonials = [];
        foreach ($reviews as $i => $review) {
            $testimonials[] = array_merge($review, [
                'customer_name' => $firstNames[$i].' '.$lastInitials[$i].'.',
                'customer_title' => $titles[$i],
                'city_id' => $city->id,
                'is_featured' => $i < 3,
                'is_active' => true,
            ]);
        }

        return $testimonials;
    }

    /**
     * সব সার্ভিস টাইপের পেজ একসাথে জেনারেট করুন
     */
    public function generateAllPagesForCity(City $city): array
    {
        $pages = [];
        $types = ['general', 'construction', 'wedding', 'event'];

        foreach ($types as $type) {
            $pages[$type] = $this->generateServicePageContent($city, $type);
        }

        return $pages;
    }
}
