<?php

namespace App\Services;

use App\Models\City;
use Illuminate\Support\Facades\Log;

class ContentGeneratorService
{
    protected ?MultiAiService $aiService = null;

    protected ?ImageService $imageService = null;

    public function __construct()
    {
        if (app()->bound(MultiAiService::class)) {
            $this->aiService = app(MultiAiService::class);
        }

        if (app()->bound(ImageService::class)) {
            $this->imageService = app(ImageService::class);
        }
    }

    public function generateServicePageContent(City $city, string $serviceType = 'general'): array
    {
        if ($this->aiService) {
            return $this->generateFromAI($city, $serviceType);
        }

        return match ($serviceType) {
            'general' => $this->generalContent($city, $city->name, $city->state->code, $city->state->name, implode(', ', array_slice($city->getNearbyAreaNames(), 0, 8))),
            'construction' => $this->constructionContent($city, $city->name, $city->state->code, $city->state->name, implode(', ', array_slice($city->getNearbyAreaNames(), 0, 8))),
            'wedding' => $this->weddingContent($city, $city->name, $city->state->code, $city->state->name, implode(', ', array_slice($city->getNearbyAreaNames(), 0, 8))),
            'event' => $this->eventContent($city, $city->name, $city->state->code, $city->state->name, implode(', ', array_slice($city->getNearbyAreaNames(), 0, 8))),
            'luxury' => $this->luxuryContent($city, $city->name, $city->state->code, $city->state->name, implode(', ', array_slice($city->getNearbyAreaNames(), 0, 8))),
            'party' => $this->partyContent($city, $city->name, $city->state->code, $city->state->name, implode(', ', array_slice($city->getNearbyAreaNames(), 0, 8))),
            'emergency' => $this->emergencyContent($city, $city->name, $city->state->code, $city->state->name, implode(', ', array_slice($city->getNearbyAreaNames(), 0, 8))),
            'residential' => $this->residentialContent($city, $city->name, $city->state->code, $city->state->name, implode(', ', array_slice($city->getNearbyAreaNames(), 0, 8))),
            default => $this->generalContent($city, $city->name, $city->state->code, $city->state->name, implode(', ', array_slice($city->getNearbyAreaNames(), 0, 8))),
        };
    }

    protected function generateFromAI(City $city, string $serviceType): array
    {
        $state = $city->state;
        $serviceLabels = [
            'general' => 'General Porta Potty Rental',
            'construction' => 'Construction Site Porta Potty',
            'wedding' => 'Wedding Event Porta Potty',
            'event' => 'Event Porta Potty Rental',
            'luxury' => 'Luxury Restroom Trailer',
            'party' => 'Party Porta Potty Rental',
            'emergency' => 'Emergency Portable Toilet',
            'residential' => 'Residential Porta Potty',
        ];
        $serviceLabel = $serviceLabels[$serviceType] ?? 'General Porta Potty Rental';
        $nearbyAreas = implode(', ', array_slice($city->getNearbyAreaNames(), 0, 8));
        $population = $city->population ? number_format($city->population) : 'N/A';
        $serviceLink = '/services#'.$serviceType;

        $prompt = <<<PROMPT
For {$serviceLabel} in {$city->name}, {$state->code}:

Return ONLY this exact format (replace CONTENT with markdown):
[METADATA]
{"slug":"{$serviceType}-porta-potty-rental-{$city->slug}","h1_title":"{$serviceLabel} in {$city->name}, {$state->code} | Potty Direct","meta_title":"{$serviceLabel} in {$city->name}, {$state->code} | Fast Delivery | Potty Direct","meta_description":"{$serviceLabel} in {$city->name}, {$state->code}. Same-day delivery. Call for quote!"}
[/METADATA]
[CONTENT]
Write 150 words of SEO content in markdown. Start with ## heading. Include bullet points, {$city->name}, {$state->code}, pricing, CTA.
[/CONTENT]
PROMPT;

        $rawResponse = $this->aiService->generateContent($prompt);

        if (! $rawResponse) {
            throw new \RuntimeException("AI generation failed for {$city->name} ({$serviceType}) - All API keys exhausted or unavailable");
        }

        $result = [
            'slug' => "{$serviceType}-porta-potty-rental-{$city->slug}",
            'h1_title' => "{$serviceLabel} in {$city->name}, {$state->code} | Potty Direct",
            'meta_title' => "{$serviceLabel} in {$city->name}, {$state->code} | Fast Delivery | Potty Direct",
            'meta_description' => "{$serviceLabel} in {$city->name}, {$state->code}. Same-day delivery. Call for quote!",
            'content' => '',
        ];

        if (preg_match('/\[CONTENT\]\s*(.*?)\s*\[\/CONTENT\]/is', $rawResponse, $matches)) {
            $result['content'] = trim($matches[1]);
        }

        if (preg_match('/\[METADATA\]\s*(\{.*?\})\s*\[\/METADATA\]/is', $rawResponse, $matches)) {
            $metadata = json_decode($matches[1], true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($metadata)) {
                $result = array_merge($result, $metadata);
            }
        }

        if (empty($result['content'])) {
            Log::warning('AI content parse failed, using fallback', [
                'city' => $city->name,
                'service_type' => $serviceType,
            ]);

            return $this->getFallbackContent($city, $serviceType);
        }

        $content = $result['content'];

        $images = $this->getImagesForContent($city, $serviceType);
        $contentWithImages = $this->embedImagesInContent($content, $images);

        return [
            'slug' => $result['slug'] ?? "porta-potty-rental-{$city->slug}",
            'service_type' => $serviceType,
            'h1_title' => $result['h1_title'] ?? '',
            'meta_title' => $result['meta_title'] ?? '',
            'meta_description' => $result['meta_description'] ?? '',
            'content' => $contentWithImages,
            'images' => $images,
            'word_count' => str_word_count(strip_tags($content)),
        ];
    }

    protected function getImagesForContent(City $city, string $serviceType): array
    {
        if (! $this->imageService) {
            return [];
        }

        try {
            $images = $this->imageService->getRandomImagesForContent(3);

            return $images;
        } catch (\Exception $e) {
            Log::warning("Failed to get images for content: {$e->getMessage()}");

            return [];
        }
    }

    protected function embedImagesInContent(string $content, array $images): string
    {
        if (empty($images)) {
            return $content;
        }

        $imageSection = "\n\n## Our Work\n\n";
        $imageSection .= "See our porta potty units in action:\n\n";

        foreach ($images as $image) {
            $altText = ucfirst(str_replace(['-', '_', '.'], ' ', pathinfo($image['filename'], PATHINFO_FILENAME)));
            $encodedUrl = str_replace(' ', '%20', $image['url']);
            $imageSection .= "![{$altText}]({$encodedUrl})\n";
        }

        $imageSection .= "\n---\n";

        if (str_contains(strtolower($content), '## why choose')) {
            $parts = preg_split('/(## why choose)/i', $content, 2);
            if (count($parts) === 3) {
                return $parts[1].$parts[2].$imageSection;
            }
        }

        return $content.$imageSection;
    }

    public function getAiService(): ?MultiAiService
    {
        return $this->aiService;
    }

    protected function getFallbackContent(City $city, string $serviceType): array
    {
        $cityName = $city->name;
        $stateCode = $city->state->code;
        $stateName = $city->state->name;
        $nearbyText = implode(', ', array_slice($city->getNearbyAreaNames(), 0, 8));

        $result = match ($serviceType) {
            'general' => $this->generalContent($city, $cityName, $stateCode, $stateName, $nearbyText),
            'construction' => $this->constructionContent($city, $cityName, $stateCode, $stateName, $nearbyText),
            'wedding' => $this->weddingContent($city, $cityName, $stateCode, $stateName, $nearbyText),
            'event' => $this->eventContent($city, $cityName, $stateCode, $stateName, $nearbyText),
            'luxury' => $this->luxuryContent($city, $cityName, $stateCode, $stateName, $nearbyText),
            'party' => $this->partyContent($city, $cityName, $stateCode, $stateName, $nearbyText),
            'emergency' => $this->emergencyContent($city, $cityName, $stateCode, $stateName, $nearbyText),
            'residential' => $this->residentialContent($city, $cityName, $stateCode, $stateName, $nearbyText),
            default => $this->generalContent($city, $cityName, $stateCode, $stateName, $nearbyText),
        };

        $images = $this->getImagesForContent($city, $serviceType);
        $result['content'] = $this->embedImagesInContent($result['content'], $images);
        $result['images'] = $images;

        return $result;
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
- **Weekly servicing included**

### Deluxe Flushable Portable Restrooms
For events where you want to provide a more comfortable experience, our deluxe flushable units are the perfect choice for {$cityName} events:
- Flushing toilet mechanism
- Built-in hand wash station with soap
- Interior mirror
- Coat hook
- Enhanced ventilation

### ADA-Compliant Accessible Units
We provide fully ADA-compliant portable restrooms to ensure accessibility for all guests at your {$cityName} event or job site:
- Extra-wide door for wheelchair access
- Interior grab bars
- Lowered toilet seat height
- Spacious interior (meets ADA requirements)
- Non-slip flooring

### Luxury Restroom Trailers
For upscale events in {$cityName}, our [luxury restroom trailers](/luxury-restroom-trailer-rental-{$city->slug}) provide a premium experience:
- Climate-controlled interior
- Porcelain fixtures
- Hardwood-style flooring
- Vanity mirrors with lighting
- Premium hand soap and paper towels

## Who Needs Porta Potty Rental in {$cityName}, {$stateCode}?

### Construction Companies & Contractors
{$cityName} has {$constructionInfo}. OSHA regulations require adequate sanitation facilities on construction sites. Our [construction porta potty rental in {$cityName}](/construction-porta-potty-rental-{$city->slug}) includes:
- Weekly servicing and cleaning
- OSHA-compliant units
- Flexible rental terms (weekly, monthly, long-term)
- Fast delivery to any job site in the {$cityName} area

### Event Planners & Organizers
{$cityName} hosts {$localEvents}. Our [event portable restroom rental service](/event-porta-potty-rental-{$city->slug}) ensures your guests have access to clean, comfortable facilities. We provide:
- Multiple unit packages for events of any size
- Delivery and setup before your event
- Pickup after your event concludes
- Hand wash stations available

### Wedding Planners & Couples
Outdoor weddings are increasingly popular in {$cityName}. Don't let inadequate restroom facilities ruin your special day. Our [wedding porta potty rental in {$cityName}](/wedding-porta-potty-rental-{$city->slug}) offers:
- Elegant deluxe and luxury options
- Units that complement your wedding aesthetic
- Delivery and setup the day before
- Attendant service available

### Homeowners & DIY Projects
Planning a home renovation or hosting a large backyard party in {$cityName}? A portable toilet rental keeps your indoor bathrooms clean and provides convenience for workers and guests.

## Porta Potty Rental Pricing in {$cityName}, {$stateCode}

Pricing varies based on several factors:

- **Unit type** — Standard, deluxe, ADA, or luxury
- **Quantity** — Discounts available for multi-unit rentals
- **Rental duration** — Daily, weekly, monthly rates
- **Location** — Delivery distance may affect pricing

*Delivery and pickup fees may apply. Call for an exact quote for your {$cityName} location.*

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
- **Weekly servicing included**

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

## Construction Rental Packages for {$cityName} Contractors

We offer flexible packages tailored to your job site needs:

- **Solo Contractor** — 1 Standard Unit + Weekly Service
- **Small Crew (5-20)** — 1-2 Units + Hand Wash + Weekly Service
- **Medium Site (20-50)** — 2-3 Units + Hand Wash + 2x Weekly Service
- **Large Site (50-100)** — 5+ Units + Hand Wash + 3x Weekly Service

*Call us for custom quotes based on your specific project requirements.*

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

If your wedding venue in {$cityName} is outdoors — a barn, vineyard, garden, farm, or private property — you likely need portable restroom facilities. Even indoor venues sometimes need supplemental restrooms for large guest count.

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

### Deluxe Flushable Units
A step above standard units, ideal for more casual {$cityName} weddings:
- Flushing toilet
- Built-in sink with running water
- Interior mirror
- Can be decorated to match your theme

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

### Medium Event Package (50-150 guests)
- 3-5 Standard Units + 1 ADA Unit
- 2 Hand Wash Stations
- Delivery, setup, and pickup included

### Large Event Package (150-500 guests)
- 8-15 Units (mix of Standard, Deluxe, ADA)
- 4+ Hand Wash Stations
- On-site servicing for multi-day events

### Festival Package (500+ guests)
- Custom unit count based on attendance
- Luxury restroom trailers available
- Dedicated on-site attendants
- Multi-day servicing schedule

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

    protected function luxuryContent(City $city, string $cityName, string $stateCode, string $stateName, string $nearbyText): array
    {
        $slug = "luxury-restroom-trailer-rental-{$city->slug}";

        $content = <<<CONTENT
## Luxury Restroom Trailer Rental in {$cityName}, {$stateCode}

When ordinary portable toilets won't cut it, our **luxury restroom trailers** deliver the premium experience your guests expect. Perfect for upscale weddings, VIP events, and corporate functions throughout {$cityName} and the greater {$stateCode} area.

## Why Choose Luxury Restroom Trailers in {$cityName}?

Standard portable toilets serve their purpose, but when you're investing in a memorable event, your restroom facilities should match the quality of your venue and catering. Our luxury restroom trailers provide:

- **Climate-controlled comfort** — Air conditioning and heating for any weather
- **Porcelain fixtures** — Real toilets that flush, not chemical commodes
- **Premium amenities** — Hand soap, lotion, and fresh towels
- **Elegant interiors** — Tasteful decor that complements your event style
- **Privacy and dignity** — Individual stalls with proper locking doors

## Luxury Trailer Options Available in {$cityName}

### Executive Series (2-Station)
Perfect for intimate gatherings and small weddings in {$cityName}:
- 2 private stalls (1 unisex or men's/womened sides)
- Air conditioning and heating
- Porcelain flushing toilets
- Vanity sink with mirror
- Wood-style flooring
- LED lighting

### Ambassador Series (4-Station)
Ideal for medium-sized events and weddings in {$cityName}:
- 4 private stalls with men's and women's sides
- Full climate control
- Porcelain toilets and sinks
- Vanity mirrors with Hollywood lighting
- Premium sound system
- Under-sink storage

### Presidential Series (6-Station)
For large weddings and corporate events in {$cityName}:
- 6+ private stalls
- Full climate control
- Multiple wash stations
- Premium granite countertops
- Built-in stereo system
- Exterior lighting package
- Attendant-ready design

## Who Books Luxury Restroom Trailers in {$cityName}?

### High-End Weddings
Your wedding day deserves the best. Luxury trailers provide the comfort and elegance your guests appreciate, especially for outdoor barn weddings, vineyard ceremonies, and country club events in {$cityName}.

### Corporate Events
Company galas, product launches, and executive retreats in {$cityName} require facilities that reflect your brand's professionalism.

### VIP Areas
Music festivals, sporting events, and exclusive gatherings in {$cityName} often book luxury trailers for VIP sections.

### Private Estates
Grand openings and high-profile events at private properties in {$cityName} benefit from premium portable restrooms.

## How Many Luxury Trailers for Your {$cityName} Event?

| Guest Count | Recommended |
|-------------|-------------|
| 50-100 guests | 1 Executive Series |
| 100-200 guests | 1 Ambassador Series |
| 200-400 guests | 2 Ambassador Series or 1 Presidential |
| 400+ guests | Custom configuration |

*These recommendations assume standard 4-6 hour events. Longer events or events with alcohol may require additional units.*

## What Sets Our Luxury Trailers Apart in {$cityName}?

✅ **Spotlessly Clean** — Every trailer is meticulously cleaned and sanitized before delivery
✅ **Reliable Performance** — Self-contained water and waste systems mean no plumbing connection needed
✅ **Professional Setup** — Our team handles placement, leveling, and connection
✅ **Flexible Terms** - Single-day to multi-week rentals available
✅ **Delivery Throughout {$cityName}** — Including {$nearbyText} and all surrounding areas

## Planning Your Luxury Restroom Rental in {$cityName}

1. **Book Early** — Luxury trailers book fast, especially during {$cityName}'s peak wedding season (April-October)
2. **Consider Placement** — Trailers need level ground and clearance for truck access
3. **Plan for Power** — Some units need generator or shore power connection
4. **Discuss Decor** — We can coordinate trailer appearance with your event theme

## Service Area for Luxury Restroom Trailers

We deliver luxury restroom trailers throughout the {$cityName} metropolitan area, including {$nearbyText}, and all of {$stateCode}. Our fleet includes multiple trailer options, so we can accommodate events of any size.

**Call now to discuss your luxury restroom trailer needs in {$cityName}, {$stateCode}. We'll help you select the perfect trailer for your event.**
CONTENT;

        return [
            'slug' => $slug,
            'h1_title' => "Luxury Restroom Trailer Rental in {$cityName}, {$stateCode}",
            'meta_title' => "Luxury Restroom Trailer Rental {$cityName} {$stateCode} | Premium Event Restrooms",
            'meta_description' => "Rent luxury restroom trailers in {$cityName}, {$stateCode} for weddings, corporate events & VIP gatherings. Climate-controlled, elegant facilities. Call for availability!",
            'content' => $content,
            'word_count' => str_word_count($content),
            'service_type' => 'luxury',
        ];
    }

    protected function partyContent(City $city, string $cityName, string $stateCode, string $stateName, string $nearbyText): array
    {
        $slug = "party-porta-potty-rental-{$city->slug}";

        $content = <<<CONTENT
## Party Porta Potty Rental in {$cityName}, {$stateCode}

Hosting a party or backyard celebration in {$cityName}? A portable toilet rental keeps your guests comfortable and protects your indoor bathrooms from heavy traffic. Whether it's a birthday bash, family reunion, or graduation party, we've got you covered.

## Why Rent a Porta Potty for Your {$cityName} Party?

Let's face it — when you have 20+ guests in your {$cityName} home, one or two bathrooms just isn't enough. Portable toilets solve this problem:

- **Protect your home** — No one tracking mud through the house or clogging your toilets
- **Convenience for guests** — No waiting in line or asking where the bathroom is
- **Outdoor events** — Perfect for pool parties, BBQs, and tent celebrations
- **Multi-day events** — Weekend-long parties need proper facilities

## Party Rental Options in {$cityName}

### Standard Units
Perfect for casual backyard parties in {$cityName}:
- Compact and reliable
- Hand sanitizer included
- Ventilation for odor control
- Easy to place anywhere on your property

### Deluxe Units
For parties where you want to impress your {$cityName} guests:
- Flushing toilet
- Built-in sink with soap
- Interior mirror
- More spacious interior

### Hand Wash Stations
Always a smart addition to your {$cityName} party:
- Foot-pump operated (no touching)
- Fresh water and soap
- Paper towel dispenser

## How Many Porta Potties for Your {$cityName} Party?

| Guest Count | Recommended Units |
|-------------|-------------------|
| Up to 20 | 1 Standard Unit |
| 20-50 | 1-2 Standard Units |
| 50-100 | 2-3 Units |
| 100+ | 3+ Units or Deluxe |

## Planning Tips for {$cityName} Parties

1. **Book 1-2 weeks ahead** — Weekends fill up fast during party season
2. **Choose placement wisely** — Place on level ground, away from the food area
3. **Consider the weather** — Add a tent or canopy for rainy days
4. **Tell guests** — Let them know portable restrooms are available

## Service Areas for Party Rentals

We deliver porta potties for parties throughout the {$cityName} area, including {$nearbyText}. No party is too small — we serve {$cityName} residents with reliable, clean units.

**Call now to reserve your party porta potties in {$cityName}!**
CONTENT;

        return [
            'slug' => $slug,
            'h1_title' => "Party Porta Potty Rental in {$cityName}, {$stateCode}",
            'meta_title' => "Party Porta Potty Rental {$cityName} {$stateCode} | Backyard Party Rentals",
            'meta_description' => "Rent porta potties for your {$cityName} party or backyard celebration. Clean units, fast delivery. Perfect for birthdays, reunions & more. Call now!",
            'content' => $content,
            'word_count' => str_word_count($content),
            'service_type' => 'party',
        ];
    }

    protected function emergencyContent(City $city, string $cityName, string $stateCode, string $stateName, string $nearbyText): array
    {
        $slug = "emergency-porta-potty-rental-{$city->slug}";

        $content = <<<CONTENT
## Emergency Porta Potty Rental in {$cityName}, {$stateCode}

When you need porta potties fast in {$cityName}, we're here to help. Storm damage, flooding, plumbing emergencies — we understand life happens, and sometimes you need immediate sanitation solutions.

## Emergency Situations We Serve in {$cityName}

### Plumbing Failures
When your home's plumbing goes down in {$cityName}, a portable toilet provides immediate relief:
- Sewer backups
- Burst pipes
- Water heater failures
- Renovations that knock out bathrooms

### Storm Damage
After severe weather hits {$cityName}, we respond quickly:
- Flood damage
- Wind damage
- Power outages affecting sewage systems

### Insurance Claims
We work with {$cityName} homeowners and insurance adjusters:
- Detailed receipts for documentation
- Quick delivery when you need it most
- Flexible rental terms during recovery

## Why {$cityName} Residents Choose Us for Emergencies

✅ **Fast Response** — We understand emergencies don't wait
✅ **Same-Day Delivery** — Available when you call early
✅ **Flexible Terms** — Daily and weekly options
✅ **No Hidden Fees** — Upfront pricing you can count on
✅ **Licensed & Insured** — Professional service you can trust

## Emergency Delivery in {$cityName}

When disaster strikes in {$cityName}, call us first. We'll get portable toilets to your property as quickly as possible. Our team understands the urgency and works fast to help you get back to normal.

## Service Area for Emergency Rentals

We provide emergency porta potty rentals throughout {$cityName} and the greater {$stateCode} area. No matter when disaster strikes, we're ready to help {$cityName} residents.

**Call now for emergency porta potty rental in {$cityName}. We'll respond fast.**
CONTENT;

        return [
            'slug' => $slug,
            'h1_title' => "Emergency Porta Potty Rental in {$cityName}, {$stateCode}",
            'meta_title' => "Emergency Porta Potty Rental {$cityName} {$stateCode} | Fast Delivery",
            'meta_description' => "Emergency porta potty rental in {$cityName}, {$stateCode}. Same-day delivery available for plumbing failures, storm damage & more. Call now for quick relief!",
            'content' => $content,
            'word_count' => str_word_count($content),
            'service_type' => 'emergency',
        ];
    }

    protected function residentialContent(City $city, string $cityName, string $stateCode, string $stateName, string $nearbyText): array
    {
        $slug = "residential-porta-potty-rental-{$city->slug}";

        $content = <<<CONTENT
## Residential Porta Potty Rental in {$cityName}, {$stateCode}

Home projects in {$cityName} often need portable sanitation solutions. Whether you're renovating, building an addition, or tackling a big DIY project, a porta potty keeps your project moving and your household comfortable.

## When {$cityName} Homeowners Need Portable Toilets

### Home Renovations
Bathroom or kitchen remodels in {$cityName} can take weeks or months. A portable toilet keeps your workers comfortable:
- No portable trailers to rent or buy
- Workers stay on site longer
- Protects your temporary bathroom setup

### Home Additions & New Construction
Building a garage, shed, or guest house in {$cityName}? New construction needs proper facilities:
- OSHA-compliant for any hired workers
- Keeps construction traffic out of your main house
- Flexible delivery and pickup

### Major DIY Projects
Tackling a big project yourself in {$cityName}?
- Landscaping or deck building
- Pool installation
- Pole barn or workshop construction

### Pool or Hot Tub Installation
Having a pool or hot tub installed in {$cityName} means heavy traffic and potential messes:
- Protects your new investment
- Keeps workers focused on the job
- No portable trailers needed

## Residential Rental Options in {$cityName}

### Standard Units
Perfect for home projects:
- Reliable and easy to use
- Hand sanitizer included
- Weekly servicing available

### Deluxe Units
When you want more comfort:
- Flushing toilet
- Built-in sink
- More interior space

## Why {$cityName} Homeowners Choose Us

✅ **Flexible Scheduling** — Delivery and pickup when you need it
✅ **No Long-Term Contracts** — Rent for as long as your project takes
✅ **Clean Units** — Every unit sanitized before delivery
✅ **Competitive Pricing** — Affordable for any budget
✅ **Local Service** — We know {$cityName} and surrounding areas

## How It Works for {$cityName} Residents

1. **Call us** — Tell us about your project and how long you'll need the unit
2. **We deliver** — Bring the porta potty to your {$cityName} property
3. **We service** — Weekly cleaning and restocking available
4. **We pick up** — When your project is done, we remove the unit

**Call now to discuss your residential porta potty needs in {$cityName}, {$stateCode}.**
CONTENT;

        return [
            'slug' => $slug,
            'h1_title' => "Residential Porta Potty Rental in {$cityName}, {$stateCode}",
            'meta_title' => "Residential Porta Potty Rental {$cityName} {$stateCode} | Home Projects",
            'meta_description' => "Portable toilet rental for home projects in {$cityName}, {$stateCode}. Renovations, additions & DIY projects. Flexible terms, clean units. Call for free quote!",
            'content' => $content,
            'word_count' => str_word_count($content),
            'service_type' => 'residential',
        ];
    }

    /**
     * শহরের জন্য FAQs জেনারেট করুন
     */
    public function generateFaqs(City $city, ?string $serviceType = null): array
    {
        $cityName = $city->name;
        $stateCode = $city->state->code;

        $generalFaqs = [
            [
                'question' => "How much does a porta potty rental cost in {$cityName}, {$stateCode}?",
                'answer' => "Porta potty rental prices in {$cityName} vary based on unit type, quantity, and rental duration. Standard units, deluxe flushable, and ADA accessible units all have different pricing. Call us for an exact quote tailored to your specific needs and location in {$cityName}.",
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

        $constructionFaqs = [
            [
                'question' => "How many porta potties do I need for my construction site in {$cityName}?",
                'answer' => "OSHA requires 1 toilet per 20 workers for construction sites in {$cityName}. For 10 workers, one standard unit suffices. Larger crews need multiple units. We can help you calculate the right number based on your crew size.",
            ],
            [
                'question' => "Are your construction porta potties OSHA compliant in {$cityName}?",
                'answer' => "Yes, all our portable toilets for construction sites in {$cityName} meet OSHA requirements. This includes proper ventilation, hand sanitizer dispensers, and non-splash urinals. We help keep your site compliant.",
            ],
            [
                'question' => "Do you offer weekly servicing for construction sites in {$cityName}?",
                'answer' => "Yes! Weekly servicing is included with our construction site rentals in {$cityName}. This includes pumping, cleaning, sanitizing, and restocking toilet paper and hand sanitizer. Extra servicing available for high-traffic sites.",
            ],
            [
                'question' => "Can you deliver porta potties to multiple locations in {$cityName}?",
                'answer' => "Absolutely. If your construction project in {$cityName} has multiple sites or phases, we can deliver units to each location. Call us to discuss your project requirements.",
            ],
            [
                'question' => "Do you offer hand wash stations for construction sites in {$cityName}?",
                'answer' => "Yes, we offer portable hand wash stations that can be added to your {$cityName} construction site. These foot-pump operated stations include fresh water, soap, and paper towels for proper hand hygiene.",
            ],
        ];

        $weddingFaqs = [
            [
                'question' => "How many portable restrooms do I need for my {$cityName} wedding?",
                'answer' => "For weddings in {$cityName}, we recommend 1 luxury trailer per 50 guests, or 1 deluxe unit per 25-30 guests. If serving alcohol, add 15-20% more units. We'll help you calculate the perfect amount.",
            ],
            [
                'question' => "What type of portable restrooms are best for weddings in {$cityName}?",
                'answer' => "For weddings in {$cityName}, luxury restroom trailers are the most popular choice. They offer climate control, flushing toilets, and elegant interiors that match wedding aesthetics. Deluxe units work well for more casual celebrations.",
            ],
            [
                'question' => "How far in advance should I book wedding porta potties in {$cityName}?",
                'answer' => "We recommend booking 3-6 months in advance for {$cityName} weddings, especially during peak season (April-October). Luxury trailers book fast. Last-minute bookings are subject to availability.",
            ],
            [
                'question' => "Do you deliver wedding restrooms the day before in {$cityName}?",
                'answer' => "Yes, we typically deliver wedding portable restrooms the day before your event in {$cityName}. This gives you time to inspect the units and coordinate any decorating. Setup is always included.",
            ],
            [
                'question' => "Can the portable restrooms be decorated to match my wedding theme in {$cityName}?",
                'answer' => "Yes! Our deluxe and luxury units at your {$cityName} wedding can be decorated with flowers, signage, and lighting to blend with your wedding aesthetic. Many couples add decorative elements to make the restrooms feel cohesive with their venue.",
            ],
        ];

        $eventFaqs = [
            [
                'question' => "How many porta potties do I need for my {$cityName} event?",
                'answer' => "For events in {$cityName}, plan 1 unit per 50 guests for a 4-hour event, or 1 unit per 25 guests for an 8-hour event. Add 20% more if alcohol is served, 10% more if food is served. Large events may need more units.",
            ],
            [
                'question' => "Do you offer hand wash stations for events in {$cityName}?",
                'answer' => "Yes, hand wash stations are available for events in {$cityName}. They're recommended for events with food service and help meet health department requirements. Adding hand wash stations improves guest satisfaction.",
            ],
            [
                'question' => "How early should I book porta potties for my {$cityName} event?",
                'answer' => "Book 2-4 weeks in advance for events in {$cityName}. Summer weekends fill up fast (April-October). Large events (500+ guests) should book 6-8 weeks ahead. Last-minute bookings subject to availability.",
            ],
            [
                'question' => "Do you provide ADA-accessible units for public events in {$cityName}?",
                'answer' => "Yes, we recommend including ADA-accessible units for public events in {$cityName}. These units meet all ADA requirements with extra-wide doors, grab bars, and spacious interiors. Many public events are legally required to provide accessible restrooms.",
            ],
            [
                'question' => "Can you service porta potties during my multi-day event in {$cityName}?",
                'answer' => "Yes! For multi-day events in {$cityName}, we offer daily or twice-daily servicing. This keeps units clean and stocked throughout your event. We'll work with you to create a servicing schedule.",
            ],
        ];

        $luxuryFaqs = [
            [
                'question' => "What is included in a luxury restroom trailer rental in {$cityName}?",
                'answer' => "Our luxury trailers in {$cityName} include climate control (A/C and heat), porcelain flushing toilets, vanity sinks with mirrors, premium hand soap and lotion, paper towels, and elegant interior lighting. Everything is spotlessly clean before delivery.",
            ],
            [
                'question' => "How many guests can a luxury restroom trailer accommodate in {$cityName}?",
                'answer' => "Our Executive Series (2-station) handles up to 100 guests. The Ambassador Series (4-station) serves up to 200 guests. The Presidential Series (6-station) accommodates 400+ guests. Call us to discuss your {$cityName} event needs.",
            ],
            [
                'question' => "Do luxury trailers need electricity in {$cityName}?",
                'answer' => "Most luxury trailers in {$cityName} need either a generator or shore power connection. Some units are self-contained with built-in batteries. We'll discuss power requirements when you book your {$cityName} event.",
            ],
            [
                'question' => "How far in advance should I book a luxury restroom trailer in {$cityName}?",
                'answer' => "Book 3-6 months in advance for luxury trailers in {$cityName}, especially for spring and fall weddings. Our trailer fleet is limited and demand is high during {$cityName}'s peak event season.",
            ],
        ];

        $partyFaqs = [
            [
                'question' => "How many porta potties do I need for my {$cityName} party?",
                'answer' => "For backyard parties in {$cityName}, plan 1 standard unit per 20-25 guests. A party with 30-50 guests typically needs 2 units. Larger parties may need more. We'll help you determine the right amount for your {$cityName} celebration.",
            ],
            [
                'question' => "Do you offer single-day rentals for parties in {$cityName}?",
                'answer' => "Yes! Single-day rentals are perfect for parties in {$cityName}. We deliver in the morning and pick up the next day or at a time that works for you. This is ideal for birthdays, graduations, and backyard BBQs.",
            ],
            [
                'question' => "What's the best placement for porta potties at my {$cityName} party?",
                'answer' => "Place portable toilets on level ground away from your {$cityName} party's food area but close enough for guest convenience. Consider privacy and accessibility. Our team can help with placement when we deliver.",
            ],
        ];

        $emergencyFaqs = [
            [
                'question' => "Do you offer emergency delivery in {$cityName}?",
                'answer' => "Yes! We understand emergencies don't wait. For {$cityName} emergencies, we offer same-day delivery when you call early. We respond fast to plumbing failures, storm damage, and other urgent situations.",
            ],
            [
                'question' => "How quickly can I get a porta potty in {$cityName} during an emergency?",
                'answer' => "For {$cityName} emergencies, we prioritize fast delivery. Call us as soon as you know you need a unit. We'll get portable toilets to your property as quickly as possible, often same-day.",
            ],
            [
                'question' => "Can you help with insurance documentation for my {$cityName} emergency?",
                'answer' => "Yes, we provide detailed receipts and documentation for insurance claims in {$cityName}. Our units are licensed and insured, so you have proper records for your claim. Just let us know you need documentation.",
            ],
        ];

        $residentialFaqs = [
            [
                'question' => "How long can I rent a porta potty for my {$cityName} home project?",
                'answer' => "We offer flexible rental terms for {$cityName} home projects — daily, weekly, monthly, or as long as your project takes. No long-term contracts required. Rent for exactly as long as you need.",
            ],
            [
                'question' => "Do you deliver porta potties to residential homes in {$cityName}?",
                'answer' => "Yes! We deliver to residential properties throughout {$cityName}. Whether you're doing a major renovation, building an addition, or a DIY project, we can deliver a portable toilet to your home.",
            ],
            [
                'question' => "What's the best type of porta potty for home renovations in {$cityName}?",
                'answer' => "For {$cityName} home renovations, a standard unit works well for most projects. If you want more comfort during a long remodel, consider a deluxe unit with flushing toilet and sink. We'll help you choose.",
            ],
        ];

        $faqsByType = [
            'general' => $generalFaqs,
            'construction' => $constructionFaqs,
            'wedding' => $weddingFaqs,
            'event' => $eventFaqs,
            'luxury' => $luxuryFaqs,
            'party' => $partyFaqs,
            'emergency' => $emergencyFaqs,
            'residential' => $residentialFaqs,
        ];

        return $faqsByType[$serviceType] ?? $generalFaqs;
    }

    /**
     * শহরের জন্য Testimonials জেনারেট করুন
     */
    public function generateTestimonials(City $city, ?string $serviceType = null): array
    {
        $cityName = $city->name;

        $generalTestimonials = [
            ['name' => 'Mike R.', 'title' => 'Homeowner', 'text' => "Needed a last-minute porta potty for a home renovation project in {$cityName}. Called at 10 AM, had a unit delivered by 2 PM. Excellent service!", 'rating' => 5],
            ['name' => 'Jennifer K.', 'title' => 'Property Manager', 'text' => "We've used them for multiple properties across {$cityName}. Always reliable, always clean. Best portable toilet service in the area.", 'rating' => 5],
            ['name' => 'Robert S.', 'title' => 'Business Owner', 'text' => "Rented units for our office building project in {$cityName}. The team was professional, delivery was quick, and the units were spotless.", 'rating' => 5],
        ];

        $constructionTestimonials = [
            ['name' => 'Mike R.', 'title' => 'General Contractor', 'text' => "Ordered 5 units for our construction site in {$cityName}. Delivered same day, spotlessly clean. Weekly servicing has been reliable. Best porta potty rental company we've used!", 'rating' => 5],
            ['name' => 'James W.', 'title' => 'Construction Foreman', 'text' => "As a general contractor working across {$cityName}, I need a reliable porta potty provider. These guys deliver — literally and figuratively. Fair prices, clean units, no hassle.", 'rating' => 5],
            ['name' => 'David C.', 'title' => 'Project Manager', 'text' => "We've been using their construction site units for years in {$cityName}. OSHA compliant, always on time, and their weekly service keeps our sites clean.", 'rating' => 5],
        ];

        $weddingTestimonials = [
            ['name' => 'Sarah T.', 'title' => 'Wedding Coordinator', 'text' => "Used their deluxe units for our outdoor wedding in {$cityName}. Guests were genuinely impressed! The units were immaculate and the delivery team was professional. Highly recommend!", 'rating' => 5],
            ['name' => 'Emily L.', 'title' => 'Newlywed', 'text' => "Our wedding in {$cityName} needed luxury trailers and they delivered! The trailers were beautiful, clean, and our guests loved them. Worth every penny.", 'rating' => 5],
            ['name' => 'Amanda B.', 'title' => 'Event Planner', 'text' => "Booked luxury restroom trailers for a high-end wedding in {$cityName}. The service was exceptional. They even decorated the trailers to match the wedding theme!", 'rating' => 5],
        ];

        $eventTestimonials = [
            ['name' => 'Lisa M.', 'title' => 'Festival Organizer', 'text' => "We've been renting from them for our annual {$cityName} community festival for 3 years now. Always on time, always clean, always professional. Great pricing too!", 'rating' => 5],
            ['name' => 'Chris H.', 'title' => 'Corporate Event Planner', 'text' => "Rented 8 units for a company picnic in {$cityName}. Everything was set up perfectly. The hand wash stations were a great addition. Will definitely use again!", 'rating' => 5],
            ['name' => 'David C.', 'title' => 'Sports Event Coordinator', 'text' => "Needed portable toilets for a 5K run in {$cityName}. They delivered early, setup was quick, and pickup was seamless. Great experience!", 'rating' => 5],
        ];

        $luxuryTestimonials = [
            ['name' => 'Sarah T.', 'title' => 'Wedding Planner', 'text' => "The luxury restroom trailer for our {$cityName} wedding was absolutely stunning. Climate controlled, beautiful interior, and spotlessly clean. Guests thought they were regular bathrooms!", 'rating' => 5],
            ['name' => 'Michael J.', 'title' => 'Corporate Event Director', 'text' => "Booked luxury trailers for our VIP gala in {$cityName}. The trailers were top-notch — porcelain toilets, nice lighting, everything. Our executives were impressed.", 'rating' => 5],
            ['name' => 'Jessica R.', 'title' => 'Event Coordinator', 'text' => "Luxury trailers for our {$cityName} corporate retreat. The climate control was a lifesaver during the summer heat. Professional service from start to finish.", 'rating' => 5],
        ];

        $partyTestimonials = [
            ['name' => 'Amanda B.', 'title' => 'Party Planner', 'text' => "Hosted a 50th birthday party in {$cityName} and needed extra bathrooms. The porta potties arrived on time, were clean, and pickup was breeze. Highly recommend!", 'rating' => 5],
            ['name' => 'Chris H.', 'title' => 'Homeowner', 'text' => "Rented units for my daughter's graduation party in {$cityName}. Great service, clean units, and very reasonable. Saved my indoor bathrooms from 50 guests!", 'rating' => 5],
            ['name' => 'Jennifer K.', 'title' => 'Family Reunion Host', 'text' => "We had 40 family members at our {$cityName} reunion. The portable toilet was a lifesaver! Delivery was quick and the unit was clean. Will use again.", 'rating' => 5],
        ];

        $emergencyTestimonials = [
            ['name' => 'Robert S.', 'title' => 'Homeowner', 'text' => "Our sewer backed up on a Sunday in {$cityName}. Called them and they had a unit to us within 2 hours. Incredible emergency service!", 'rating' => 5],
            ['name' => 'Mike R.', 'title' => 'Property Manager', 'text' => "Had a major plumbing failure at one of my {$cityName} properties. They got me a unit same-day. The documentation helped with insurance. Great responsive service.", 'rating' => 5],
            ['name' => 'David C.', 'title' => 'Business Owner', 'text' => "Our restaurant in {$cityName} had an emergency and needed a portable toilet fast. They delivered within hours. Incredibly helpful during a stressful time.", 'rating' => 5],
        ];

        $residentialTestimonials = [
            ['name' => 'Chris H.', 'title' => 'Homeowner', 'text' => "Doing a major kitchen remodel in {$cityName}. The portable toilet made so much sense — workers had their own bathroom and my family didn't lose access to ours. Great idea!", 'rating' => 5],
            ['name' => 'Emily L.', 'title' => 'DIY Enthusiast', 'text' => "Building a backyard deck in {$cityName} and rented a porta potty for a few weeks. It was so convenient. Delivery was easy and pickup was scheduled around my project.", 'rating' => 5],
            ['name' => 'Amanda B.', 'title' => 'Homeowner', 'text' => "Rented for our pool installation in {$cityName}. The workers used it instead of tracking through the house. Made the project so much easier. Will definitely rent again.", 'rating' => 5],
        ];

        $testimonialsByType = [
            'general' => $generalTestimonials,
            'construction' => $constructionTestimonials,
            'wedding' => $weddingTestimonials,
            'event' => $eventTestimonials,
            'luxury' => $luxuryTestimonials,
            'party' => $partyTestimonials,
            'emergency' => $emergencyTestimonials,
            'residential' => $residentialTestimonials,
        ];

        $testimonials = $testimonialsByType[$serviceType] ?? $generalTestimonials;

        return array_map(function ($t) use ($city) {
            return [
                'customer_name' => $t['name'],
                'customer_title' => $t['title'],
                'content' => $t['text'],
                'rating' => $t['rating'],
                'city_id' => $city->id,
                'is_featured' => true,
                'is_active' => true,
            ];
        }, $testimonials);
    }

    /**
     * সব সার্ভিস টাইপের পেজ একসাথে জেনারেট করুন
     */
    public function generateAllPagesForCity(City $city): array
    {
        $pages = [];
        $types = ['general', 'construction', 'wedding', 'event', 'luxury', 'party', 'emergency', 'residential'];

        foreach ($types as $type) {
            $pages[$type] = $this->generateServicePageContent($city, $type);
        }

        return $pages;
    }
}
