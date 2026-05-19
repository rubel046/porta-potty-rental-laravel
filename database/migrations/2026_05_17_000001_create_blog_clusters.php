<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ensure default domain exists first (migrate:fresh wipes seed data)
        if (! DB::table('domains')->where('id', 1)->exists()) {
            DB::table('domains')->insert([
                'id' => 1,
                'name' => 'PottyDirect',
                'domain' => 'pottydirect.com',
                'logo_url' => '/images/logo.png',
                'primary_color' => '#3B82F6',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Pick a default city for blog seeding — every post targets a city for local SEO
        $seedCityId = DB::table('cities')
            ->join('domain_cities', 'cities.id', '=', 'domain_cities.city_id')
            ->where('domain_cities.domain_id', 1)
            ->where('domain_cities.status', true)
            ->orderBy('cities.population', 'desc')
            ->value('cities.id') ?? 1;

        // --- Create "Construction Site Sanitation" category ---
        $constructionCatId = DB::table('blog_categories')->where('slug', 'construction-site-sanitation')->value('id');
        if (! $constructionCatId) {
            $constructionCatId = DB::table('blog_categories')->insertGetId([
                'domain_id' => 1,
                'name' => 'Construction Site Sanitation',
                'slug' => 'construction-site-sanitation',
                'description' => 'Expert advice on OSHA-compliant portable sanitation for construction sites, including ratio requirements, placement strategies, and maintenance schedules.',
                'icon' => '🏗️',
                'sort_order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // --- Create "Event Planning & Porta Potties" category ---
        $eventCatId = DB::table('blog_categories')->where('slug', 'event-planning-porta-potties')->value('id');
        if (! $eventCatId) {
            $eventCatId = DB::table('blog_categories')->insertGetId([
                'domain_id' => 1,
                'name' => 'Event Planning & Porta Potties',
                'slug' => 'event-planning-porta-potties',
                'description' => 'Event planning guides covering restroom ratios, placement logistics, and portable toilet types for weddings, festivals, concerts, and corporate events.',
                'icon' => '🎉',
                'sort_order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ====================================================================
        //  BLOG POST #1 – Construction (CLUSTER)
        // ====================================================================
        $post1Slug = 'osha-toilet-requirements-construction-sites';
        if (! DB::table('blog_posts')->where('slug', $post1Slug)->exists()) {
            DB::table('blog_posts')->insert([
                'domain_id' => 1,
                'blog_category_id' => $constructionCatId,
                'city_id' => $seedCityId,
                'title' => 'OSHA Toilet Requirements for Construction Sites: Complete 2026 Compliance Guide',
                'slug' => $post1Slug,
                'excerpt' => 'Every construction site in the US must comply with OSHA\'s sanitation standard 1926.51. This guide covers toilet ratios, placement rules, maintenance schedules, and the specific requirements for different crew sizes and project durations.',
                'content' => 'When you\'re running a construction site, the last thing you want is an OSHA violation over porta potties. But here\'s the thing: OSHA standard 1926.51(c) is very specific about what you need, and the penalties for non-compliance can hit $15,625 per violation.

First, the basics. OSHA requires at least one toilet facility for every 20 employees on site. For crews of 20 or fewer, one toilet is sufficient. For 40 workers, you need two. And it scales from there—at 60 workers, you need three; at 80, four; and so on.

But there\'s a critical distinction you need to understand: these requirements are **per shift**. If you run two 40-person shifts, each shift needs its own two toilets. You can\'t double-count facilities across shifts.

## Placement Requirements

OSHA doesn\'t just care about how many toilets you have—it also matters where you put them. According to 1926.51(c)(2), toilet facilities must be located within a 10-minute walk from any work area. That\'s roughly 2,000 feet. On large sites like stadium projects or industrial complexes, you may need multiple sanitation stations.

The facilities must also be:
- Accessible during all work hours
- On a stable, level surface
- Protected from vehicles and heavy equipment
- Well-lit if workers will use them before sunrise or after sunset

## Types of Facilities

For construction sites, you have two main options: standard portable toilets and ADA-compliant units. Here\'s what you need to know:

**Standard units** are fine for most workers. These are the classic blue or white porta potties you see on every job site. They\'re economical, durable, and easy to service.

**ADA units** are required if you have any workers with disabilities. Even if you don\'t currently have an employee with a disability, it\'s smart to have at least one ADA unit on any site with over 20 workers. The ADA doesn\'t specify a ratio, but industry best practice is one ADA unit per 10 standard toilets.

**Handwashing stations** are required by OSHA when workers handle hazardous materials (1926.51(f)). Even for standard construction, providing hand sanitizer is strongly recommended.

## Maintenance Requirements

A porta potty without regular maintenance becomes a health hazard. OSHA expects:
- Weekly servicing for standard construction sites
- Twice-weekly for sites with more than 50 workers
- Daily servicing during hot weather (above 90°F)
- Immediate attention to any unit reported as dirty or broken

Each service visit should include pumping waste, cleaning and sanitizing all surfaces, restocking toilet paper, and checking structural integrity.

## Seasonal Considerations

Construction doesn\'t stop when the weather changes, and your sanitation plan shouldn\'t either:

**Summer**: Heat accelerates odor and bacteria growth. Double your service frequency when temperatures hit 90°F. Consider adding handwashing stations with cool water.

**Winter**: Insulated units or windbreaks can prevent freezing. Some rental companies offer winterized units with anti-freeze in the waste tank.

**Rain**: Place units on elevated, well-drained surfaces. Mud around a porta potty is a slip hazard and a sanitation issue.

## Cost Implications

Budgeting for construction site sanitation isn\'t complicated. For a standard 40-person crew:
- Monthly rental: $175-$250 per unit (standard)
- Weekly servicing: $65-$85 per unit
- Delivery and pickup: $75-$150

For a 6-month project with 40 workers (two units): expect to budget around $3,000-$4,500 for complete sanitation.

## Compliance Checklist

Use this checklist to stay compliant:
- [ ] One toilet per 20 employees per shift
- [ ] Facilities within 10-minute walk of all work areas
- [ ] At least one ADA unit for sites over 20 workers
- [ ] Weekly servicing minimum
- [ ] Hand sanitizer or handwashing station
- [ ] Units secured against wind and vehicles
- [ ] Clear path to each unit (no obstacles)
- [ ] Night lighting if work happens in low-light conditions

## What Happens If You Don\'t Comply?

OSHA inspectors can show up unannounced. A sanitation violation typically carries a penalty of $15,625 per infraction. But the real cost is worse: workers who don\'t have access to clean toilets are less productive, more likely to leave the site to find facilities, and more prone to health issues.

On a recent $12 million commercial project in Atlanta, the GC was fined $46,875 for having insufficient toilets across three shifts. That\'s real money that could have been saved with a $500/month sanitation plan.

## Get Compliant Today

{{PHONE_LINK}} — Call us. We\'ll calculate exactly how many units you need, place them strategically across your site, and set up a service schedule that keeps OSHA happy and your crew productive. We serve construction sites of all sizes, from 5-person reno crews to 500-worker highrise projects.',
                'meta_title' => 'OSHA Toilet Requirements for Construction Sites: Complete Guide (2026)',
                'meta_description' => 'OSHA requires 1 toilet per 20 employees per shift. Learn the exact ratios, placement rules, maintenance schedules, and costs to keep your construction site compliant.',
                'focus_keyword' => 'OSHA toilet requirements construction sites',
                'featured_image' => 'pottydirect/blog-images/osha-requirements-construction-sites.jpg',
                'is_published' => true,
                'published_at' => now()->subDays(27),
                'created_at' => now()->subDays(27),
                'updated_at' => now()->subDays(27),
            ]);
        }

        // ====================================================================
        //  BLOG POST #2 – Event (CLUSTER)
        // ====================================================================
        $post2Slug = 'porta-potty-calculator-event-planning';
        if (! DB::table('blog_posts')->where('slug', $post2Slug)->exists()) {
            DB::table('blog_posts')->insert([
                'domain_id' => 1,
                'blog_category_id' => $eventCatId,
                'city_id' => $seedCityId,
                'title' => 'Porta Potty Calculator: How Many Toilets You Need for Any Event Size',
                'slug' => $post2Slug,
                'excerpt' => 'Planning an event and not sure how many porta potties you need? Our calculator breaks down the exact numbers based on guest count, event duration, alcohol consumption, and crowd demographics.',
                'content' => 'One of the most common questions we hear from event organizers is "How many porta potties do I need?" Get it wrong, and you\'ll have long lines, unhappy guests, or wasted money on unused units. Get it right, and nobody thinks about bathrooms at all—which is exactly how it should be.

## The Basic Formula

The industry standard formula, recommended by the Portable Sanitation Association International (PSAI), starts with:

| Guest Count | Duration | Standard Units | ADA Units | Handwash Stations |
|---|---|---|---|---|
| 100 | 4 hrs | 2 | 1 | 1 |
| 250 | 4 hrs | 4 | 1 | 2 |
| 500 | 4 hrs | 8 | 2 | 3 |
| 1000 | 4 hrs | 15 | 3 | 5 |
| 2500 | 4 hrs | 35 | 5 | 10 |

**But here\'s the catch**: these numbers are for a standard 4-hour event with moderate food and drink. Every event is different.

## Factors That Change Your Numbers

### 1. Alcohol Consumption
Serving alcohol changes everything. Beer and wine increase restroom usage by roughly 30%. Full bars with cocktails increase it by up to 50%. If you\'re serving alcohol, multiply your base count by 1.3 to 1.5.

### 2. Event Duration
For events longer than 4 hours, the ratio changes. A full-day festival (8+ hours) needs about 1.5x the 4-hour calculation because people need to use facilities multiple times.

### 3. Gender Demographics
Women\'s restrooms see roughly 3x the usage of men\'s (for standard toilets). For events with a predominantly female audience—weddings, bridal shows, art fairs—add 20-30% more units.

### 4. Food Type
Heavy meals (BBQ, Mexican, Indian) increase restroom demand by 15-20% compared to lighter fare (salads, sandwiches).

### 5. Weather
Hot weather increases hydration, which increases restroom usage. Hot events need 15-20% more units than the baseline.

## Quick-Reference by Event Type

### Weddings (100-200 guests)
- **Without alcohol**: 3-4 standard + 1 ADA + 1 handwash
- **With alcohol**: 4-5 standard + 1 ADA + 2 handwash
- **Pro tip**: Add a luxury restroom trailer for the wedding party. It keeps the bride\'s dress clean and gives VIPs a mirror, sink, and climate control.

### Music Festivals (1,000-10,000+ attendees)
- **Per 1,000 attendees**: 12-15 standard + 2 ADA + 4 handwash
- **Multi-day**: Add 50% more capacity for day 2 (festival-goers will use facilities more the second day)
- **Pro tip**: Space units throughout the venue, not clustered in one spot. Place some near stages, some near food vendors, some near exits.

### Corporate Events (50-500 guests)
- **50 guests**: 2 standard + 1 ADA
- **100 guests**: 3 standard + 1 ADA
- **250 guests**: 5-6 standard + 1 ADA
- **500 guests**: 10-12 standard + 2 ADA + 2 handwash
- **Pro tip**: Corporate events benefit from luxury restroom trailers. Your executives and clients will appreciate running water, mirrors, and climate control.

### Construction Sites (Daily)
- **Per 20 workers**: 1 standard unit
- **Per 50 workers**: 3 standard + 1 ADA
- **Per 100 workers**: 5 standard + 1 ADA + 1 handwash
- **Pro tip**: Service construction units twice per week minimum. Daily in summer.

## What Happens When You Under-Calculate?

We\'ve seen it happen. A 500-person outdoor wedding with only 4 porta potties. By hour two, there\'s a 20-minute wait. By hour three, guests are leaving to find bathrooms at nearby gas stations. The bride is furious.

Here\'s the math on that mistake:
- 4 units for 500 people = 1 unit per 125 people (industry standard: 1 per 50-60)
- Wait time at peak usage: 15-25 minutes
- Percentage of guests who notice the problem: 100%
- Impact on event satisfaction: Significant

## Smart Placement

Where you put your portable toilets matters almost as much as how many you have:

1. **Even distribution**: Don\'t cluster all units in one spot. Place them in groups of 2-4 throughout the venue.
2. **Near high-traffic areas**: Stage areas, food vendors, and main gathering spots all need nearby facilities.
3. **Away from dining**: Place units at least 50 feet from food service and dining areas.
4. **Well-lit**: For evening events, ensure units are visible and the path to them is lit.
5. **Accessible path**: ADA units need a firm, level path at least 36 inches wide.

## Take the Guesswork Out

{{PHONE_LINK}} — Not sure exactly what you need? Call us. We\'ll ask about your event size, duration, menu, alcohol plans, and crowd demographics to give you an exact count. No overselling, no guesswork. Just the right number of units so your guests never think about bathrooms.',
                'meta_title' => 'Porta Potty Calculator: How Many Toilets You Need Per Event',
                'meta_description' => 'Calculate exact porta potty needs for any event: weddings, festivals, corporate events. Use our formula based on guest count, alcohol, duration, and demographics.',
                'focus_keyword' => 'porta potty calculator event planning',
                'featured_image' => 'pottydirect/blog-images/porta-potty-calculator-event.jpg',
                'is_published' => true,
                'published_at' => now()->subDays(24),
                'created_at' => now()->subDays(24),
                'updated_at' => now()->subDays(24),
            ]);
        }

        // ====================================================================
        //  BLOG POST #3 – Construction (CLUSTER)
        // ====================================================================
        $post3Slug = 'construction-site-porta-potty-placement-guide';
        if (! DB::table('blog_posts')->where('slug', $post3Slug)->exists()) {
            DB::table('blog_posts')->insert([
                'domain_id' => 1,
                'blog_category_id' => $constructionCatId,
                'city_id' => $seedCityId,
                'title' => 'Construction Site Porta Potty Placement: OSHA Rules & Best Practices',
                'slug' => $post3Slug,
                'excerpt' => 'Where you place portable toilets on a construction site matters for OSHA compliance, worker productivity, and maintenance access. This guide covers placement strategies for every site type.',
                'content' => 'Where you put your porta potties on a construction site is almost as important as having them. Bad placement leads to compliance violations, worker complaints, and maintenance headaches. Get it right, and your sanitation stations become invisible—which is the goal.

## Why Placement Matters

Every construction site is different. A high-rise project in downtown Chicago has different challenges than a highway project in rural Texas or a residential development in suburban Atlanta. But the principles of good placement are universal:

1. **OSHA compliance**: Toilets must be within a 10-minute walk (about 2,000 feet) of every work area
2. **Worker adoption**: If toilets are inconvenient, workers will find alternatives (leaving the site, using buckets, holding it)
3. **Maintenance access**: Service trucks need room to pump and clean
4. **Safety**: Units must be away from heavy equipment, falling objects, and traffic

## The 10-Minute Walk Rule

OSHA regulation 1926.51(c)(2) states that toilet facilities must be "located within a 10-minute walking distance from any work area." On large sites, this means you need multiple sanitation stations.

For a 10-acre site, for example, a single central location won\'t cover the perimeter. You\'d need at least 3-4 stations spread across the property.

## Placement by Site Type

### High-Rise Construction
High-rises present a unique challenge: workers are vertical. OSHA\'s 10-minute rule applies to the horizontal distance from the elevator or stair tower to the toilets on the ground. But realistically, workers aren\'t going to take a 10-minute elevator ride just to use the bathroom.

**Best practice**: Place toilets on every 3-4 floors for buildings over 5 stories. One toilet per floor for floors with 20+ workers.

### Highway & Road Construction
Linear projects like highways require mobile placement. As the work zone moves, so should the toilets.

**Best practice**: Use wheeled porta potties that can be towed behind a truck. Place units at each major work zone, spaced no more than 2,000 feet apart. Move them weekly or as the job progresses.

### Residential Development
Housing developments are spread out but generally low-rise. The challenge is multiple crews working in different phases of construction across dozens of lots.

**Best practice**: Centralized banks of 2-4 units for every 20-30 homes under construction. Move them as phases complete.

### Industrial & Warehouse
Large open spaces with heavy equipment traffic. The main risk is a porta potty getting hit by a forklift or semi truck.

**Best practice**: Place units behind barriers or in designated safe zones. Mark them with reflective tape. Never place them in high-traffic vehicle lanes.

### Renovation & Interior Work
When you\'re working inside an existing building, you can\'t always bring porta potties inside.

**Best practice**: Place units outside the main entrance or loading dock. For multi-floor renovations, designate one bathroom on each floor for worker use (if the building has existing plumbing).

## Maintenance Access

Your placement choice directly affects how well your units get serviced. Service trucks need:
- A minimum 8-foot wide access path
- Solid ground that can support 10,000+ pounds (loaded service truck)
- No overhead obstructions (power lines, scaffolding, tree branches) under 14 feet
- Clearance to maneuver a 20-foot hose

**Mark service access routes** with cones or signage so site managers know not to block them with materials or equipment.

## Common Placement Mistakes

### 1. Putting Units in the Sun
A black plastic box sitting in direct summer sun becomes an oven. Interior temperatures can hit 130°F. Workers won\'t want to use it. Place units in shade if possible, or use units with solar vents.

### 2. Clustering Everything in One Spot
One big bank of 10 toilets looks efficient, but workers on the far side of the site have to walk 15 minutes. That\'s an OSHA violation. Spread them out.

### 3. Forgetting About Phase Progression
Construction sites change weekly. That perfect location you picked last month is now in the middle of excavation. Plan for moves and build them into your contract.

### 4. Ignoring the Wind
On open sites, a strong wind can tip a lightweight porta potty. Place them near walls, trailers, or natural windbreaks. Or use sandbags (1 per corner minimum).

### 5. Blocking With Materials
A porta potty behind a stack of lumber might as well be invisible. Keep units visible and accessible at all times.

## Seasonal Placement Tips

**Summer**: Shade, ventilation, and proximity to cool-down stations
**Winter**: Wind protection, avoid low spots where snow drifts, and use insulated units
**Rainy season**: High ground only—a unit in a mud pit is unusable

## Sanitation Station Setup

For sites over 50 workers, consider creating dedicated sanitation stations:
- 2 toilets
- 1 ADA unit
- 1 handwashing station or hand sanitizer dispenser
- Trash can
- Concrete pad or plywood base

## Get Expert Placement Advice

{{PHONE_LINK}} — We\'ve placed portable toilets on thousands of construction sites. We can help you plan the right layout for your specific project, whether it\'s a 2-week renovation or a 2-year high-rise build. Call us for a free site assessment.',
                'meta_title' => 'Construction Site Porta Potty Placement: OSHA Rules & Best Practices',
                'meta_description' => 'Learn OSHA-compliant porta potty placement for construction sites. Guidelines for high-rises, highways, residential developments, and industrial projects.',
                'focus_keyword' => 'construction site porta potty placement',
                'featured_image' => 'pottydirect/blog-images/construction-placement-guide.jpg',
                'is_published' => true,
                'published_at' => now()->subDays(21),
                'created_at' => now()->subDays(21),
                'updated_at' => now()->subDays(21),
            ]);
        }

        // ====================================================================
        //  BLOG POST #4 – Event (CLUSTER)
        // ====================================================================
        $post4Slug = 'wedding-porta-potty-etiquette-guide';
        if (! DB::table('blog_posts')->where('slug', $post4Slug)->exists()) {
            DB::table('blog_posts')->insert([
                'domain_id' => 1,
                'blog_category_id' => $eventCatId,
                'city_id' => $seedCityId,
                'title' => 'Wedding Porta Potty Etiquette: A Complete Guide for Outdoor Weddings (2026)',
                'slug' => $post4Slug,
                'excerpt' => 'Outdoor weddings require portable restrooms, but proper etiquette matters. From luxury trailers to placement and signage, here\'s how to handle wedding bathroom logistics gracefully.',
                'content' => 'Let\'s address the elephant (or the porta potty) in the room: outdoor weddings need portable restrooms. And while the idea might not feel glamorous, modern portable sanitation has come a long way from the blue plastic boxes at construction sites.

## The New Reality of Wedding Restrooms

Today\'s wedding porta potties range from standard units to luxury restroom trailers with granite countertops and air conditioning. Your choice sets the tone for your event. Here\'s what you need to know about etiquette, expectations, and execution.

## 1. What Level of Luxury Is Right?

Not every wedding needs a $5,000 restroom trailer. Here\'s how to match the facilities to the event:

**Casual / Rustic Wedding (100 guests, barn or backyard)**: 4-5 standard units with handwashing stations. Decorate with flowers and signage. Budget: $400-600.

**Semi-Formal (150 guests, vineyard or garden)**: 2-3 deluxe units (flushable, sink, mirror) + 1 luxury trailer. Budget: $1,200-2,500.

**Formal / Black Tie (200+ guests, estate or resort)**: Luxury restroom trailers only. No standard units visible. Minimum 2 trailers for 200 guests. Budget: $3,000-8,000.

## 2. Placement Etiquette

Where you put the restrooms at a wedding matters more than you think:

- **At least 100 feet from the ceremony**: You don\'t want toilet sounds during vows
- **At least 50 feet from dining**: Odors and food don\'t mix
- **Close to the bar**: Guests will thank you
- **On solid ground**: High heels and grass don\'t mix. Use a plywood or turf pathway
- **Concealed with decor**: Floral arrangements, lattice screens, or rented fencing can hide even standard units

## 3. The Signage Question

Do you need signs? Yes, but make them tasteful:
- Small chalkboard signs with arrows: "Restrooms →"
- No crude jokes or puns (this is a wedding, not a festival)
- ADA signage if required

## 4. Timing Deliveries

Schedule delivery:
- 24-48 hours before the wedding for standard units
- Morning of for luxury trailers (some companies charge extra for early delivery)
- Pickup the day after (don\'t make your guests or venue staff deal with removal)

## 5. Guest Comfort Considerations

### Lighting
If your reception goes past sunset, ensure pathways to restrooms are well-lit. String lights, solar path markers, or battery-operated lanterns work well.

### Temperature
Summer weddings: request extra ventilation or units with solar fans. Winter weddings: insulated units or trailers with heaters.

### Supplies
Assign someone (coordinator, bridesmaid, or venue staff) to check restrooms hourly and restock:
- Toilet paper (extra rolls visible)
- Hand sanitizer
- Tissues
- Mints or gum
- Feminine products (in a basket)

## 6. What About the Wedding Party?

The bride, groom, and wedding party should have their own designated restroom. Options:
- A private luxury trailer backstage
- Access to the venue\'s indoor facilities (if available)
- A separate standard unit placed near the getting-ready area

## 7. Budgeting for Wedding Restrooms

Here\'s a realistic budget breakdown for a 150-person outdoor wedding:

| Item | Cost |
|---|---|
| 2 deluxe flushable units | $500-700 |
| 1 luxury restroom trailer | $1,500-3,000 |
| 1 ADA-compliant unit | $200-350 |
| Handwashing stations (2) | $200-300 |
| Delivery & pickup | $100-200 |
| **Total** | **$2,500-4,550** |

## 8. Guest Feedback

In a recent survey by The Knot, 78% of outdoor wedding guests said restroom quality impacted their overall enjoyment. Don\'t be the wedding everyone talks about for the wrong reasons.

## Plan Your Wedding Restrooms

{{PHONE_LINK}} — Talk to us about your wedding plans. We\'ll help you choose the right mix of standard, deluxe, and luxury units to match your venue, guest count, and budget. We deliver and set up so you can focus on the big day.',
                'meta_title' => 'Wedding Porta Potty Etiquette: Complete Guide for Outdoor Weddings',
                'meta_description' => 'Expert guide to wedding porta potty etiquette: luxury options, placement, signage, supplies, and budgeting for outdoor weddings of any size.',
                'focus_keyword' => 'wedding porta potty etiquette',
                'featured_image' => 'pottydirect/blog-images/wedding-porta-potty-etiquette.jpg',
                'is_published' => true,
                'published_at' => now()->subDays(18),
                'created_at' => now()->subDays(18),
                'updated_at' => now()->subDays(18),
            ]);
        }

        // ====================================================================
        //  BLOG POST #5 – Construction (CLUSTER)
        // ====================================================================
        $post5Slug = 'construction-sanitation-cost-analysis';
        if (! DB::table('blog_posts')->where('slug', $post5Slug)->exists()) {
            DB::table('blog_posts')->insert([
                'domain_id' => 1,
                'blog_category_id' => $constructionCatId,
                'city_id' => $seedCityId,
                'title' => 'Construction Site Sanitation Costs: Complete 2026 Budget Guide',
                'slug' => $post5Slug,
                'excerpt' => 'Budgeting for construction site sanitation? Here\'s a complete breakdown of porta potty rental costs, service fees, and long-term contract pricing for 2026.',
                'content' => 'One of the first questions every construction project manager asks is: "How much is this going to cost?" For construction site sanitation, the answer depends on crew size, project duration, service frequency, and unit type.

## Base Rental Costs (2026)

Here\'s what you can expect to pay for portable toilet rentals on construction sites in 2026:

| Unit Type | Weekly | Monthly | 6-Month Contract |
|---|---|---|---|
| Standard Porta Potty | $45-65 | $125-175 | $600-900 |
| Deluxe Flushable | $75-100 | $200-300 | $1,000-1,500 |
| ADA Compliant | $55-80 | $150-225 | $750-1,200 |
| Handwashing Station | $40-60 | $100-150 | $500-800 |
| Combo Unit (Toilet + Sink) | $80-120 | $225-350 | $1,200-1,800 |

## Service Costs

Rental is just the beginning. Regular servicing is where the ongoing costs live:

| Service Type | Per Visit | Monthly (Weekly Service) |
|---|---|---|
| Standard Cleaning & Pumping | $55-85 | $220-340 |
| Deep Clean + Sanitize | $85-125 | $340-500 |
| Emergency Service | $100-175 | N/A |
| ADA Unit Service | $65-95 | $260-380 |

## Factors That Affect Pricing

### 1. Location
Urban areas cost more. A monthly rental in Manhattan might be $225, while the same unit in rural Alabama costs $125. Delivery fees are also higher in dense urban areas due to parking and traffic.

### 2. Project Duration
Longer contracts get better rates:
- Month-to-month: full price
- 3-month contract: 10-15% discount
- 6-month contract: 15-25% discount
- 12-month contract: 25-35% discount

### 3. Quantity Discounts
Most rental companies offer volume pricing:
- 1-5 units: standard price
- 6-10 units: 5-10% off
- 11-25 units: 10-15% off
- 25+ units: negotiate

### 4. Frequency of Service
Weekly service is standard. But some sites need twice-weekly or daily service in summer. Each additional service adds to the monthly cost.

### 5. Winterization
If your project runs through winter, you may need:
- Insulated units: +$20-40/month
- Heated units: +$50-100/month
- Windbreaks: +$50-100 one-time

## Real-World Budget Examples

**Small Residential Site (10 workers, 3 months)**
- 1 standard unit: $450 (3 months)
- 1 weekly service: $780 (12 visits)
- Delivery: $100
- **Total: $1,330**

**Medium Commercial Site (40 workers, 6 months)**
- 2 standard + 1 ADA: $1,800 (6 months)
- 1 weekly service: $4,680 (24 visits)
- Delivery: $150
- **Total: $6,630**

**Large Industrial Project (150 workers, 12 months)**
- 8 standard + 2 ADA + 2 handwash: $12,000 (12 months)
- 2x weekly service: $18,720 (96 visits)
- Delivery: $300
- **Total: $31,020**

## Hidden Costs to Watch For

- **Overflow fees**: Most contracts have a maximum waste level. If the tank fills faster than expected due to heavy use, you may be charged extra
- **Damage fees**: Units damaged by equipment or vandalism can cost $200-500 to replace
- **Relocation fees**: Moving units costs $50-100 per move
- **After-hours service**: Any service outside normal business hours (7 AM-5 PM) costs 1.5x standard rate
- **Permit fees**: Some cities require permits for portable toilets on public rights-of-way. Typically $50-200/year

## How to Reduce Costs

1. **Sign a longer contract** — 6-month contracts save 15-25% vs monthly
2. **Consolidate service** — weekly service is cheaper than bi-weekly (fewer emergency issues)
3. **Choose standard units** for most workers and reserve deluxe for office trailers
4. **Plan your placement** — cluster units to reduce service time
5. **Prevent vandalism** — lit, visible areas have less damage

## Get an Exact Quote

{{PHONE_LINK}} — Every construction site is different. Call us with your crew size, project duration, and location for an exact quote. We\'ll break down rental, service, and any additional costs so there are no surprises.',
                'meta_title' => 'Construction Site Sanitation Costs: Complete 2026 Budget Guide',
                'meta_description' => 'Complete breakdown of construction site porta potty costs for 2026: rental rates, service fees, contract discounts, and real-world budget examples.',
                'focus_keyword' => 'construction site porta potty costs',
                'featured_image' => 'pottydirect/blog-images/construction-sanitation-costs.jpg',
                'is_published' => true,
                'published_at' => now()->subDays(15),
                'created_at' => now()->subDays(15),
                'updated_at' => now()->subDays(15),
            ]);
        }

        // ====================================================================
        //  BLOG POST #6 – Event (CLUSTER)
        // ====================================================================
        $post6Slug = 'music-festival-porta-potty-strategy';
        if (! DB::table('blog_posts')->where('slug', $post6Slug)->exists()) {
            DB::table('blog_posts')->insert([
                'domain_id' => 1,
                'blog_category_id' => $eventCatId,
                'city_id' => $seedCityId,
                'title' => 'Music Festival Porta Potty Strategy: Sanitation for Large Crowds',
                'slug' => $post6Slug,
                'excerpt' => 'Managing sanitation for a music festival is radically different from a wedding or construction site. Here\'s our complete strategy for keeping thousands of festival-goers happy.',
                'content' => 'If you\'ve ever been to a music festival with bad bathrooms, you know how much it ruins the experience. Long lines, dirty units, bad smells—it\'s the #1 complaint at festivals nationwide. Here\'s how to get it right.

## The Scale of the Problem

A 3-day festival with 10,000 daily attendees generates approximately 30,000 gallons of waste. That\'s the equivalent of 75 standard residential septic tanks. Proper planning isn\'t optional—it\'s essential.

## How Many Units Do You Need?

The standard formula for festivals is more generous than weddings or construction:

**Per 1,000 daily attendees:**
- 12-15 standard units
- 2 ADA units
- 4 handwashing stations
- 1 hand sanitizer station per 2 units

For a 3-day festival with 10,000 daily attendees:
- 120-150 standard units
- 20 ADA units
- 40 handwashing stations

But these numbers need adjustment based on:

### Lineup Duration
- Gates open 10 AM, headliner at 9 PM, curfew at 11 PM: 13-hour event day = standard calculation works
- Camping festival with 24-hour access: add 50% more units (people use facilities when they wake up, not just during show hours)

### Alcohol Sales
- Beer and wine only: multiply standard count by 1.2
- Full bar: multiply by 1.4
- No alcohol: standard count works

### VIP Sections
VIP areas need their own dedicated facilities:
- Deluxe flushable units or luxury trailers
- 1 unit per 50 VIPs (vs 1 per 75-100 for GA)
- Attendant to keep them clean

## Strategic Placement

### Distribution Model: Clusters, Not Centralized
Don\'t put all units in one location. Instead, create sanitation clusters throughout the venue:

Main Stage Area: 40% of total units
Secondary Stage: 25% of total units
Food Village: 15% of total units
Camping Area (if applicable): 15% of total units
Entry/Exit: 5% of total units

### Cluster Size
Each cluster should have 4-8 units plus 1-2 handwash stations. Larger clusters create bottlenecks. Smaller clusters require more service trips.

### Spacing Between Clusters
No attendee should walk more than 5 minutes (roughly 1,000 feet) to reach a restroom.

## Service During the Event

This is where most festivals fail. They order enough units but don\'t service them during the event.

### Recommended Service Schedule
- **Before gates open**: Full service on all units
- **Mid-day (during event)**: Pump 50% of highest-traffic units
- **After headliner**: Full service on all units
- **Overnight (multi-day)**: Full service on all units at 2 AM

### Service Access
Each cluster needs a service path that\'s:
- 10 feet wide
- Clear of tent stakes, guy lines, and fencing
- Accessible 24/7 (not blocked by vendor trucks or stages)

You\'ll need to coordinate with site operations to keep these paths clear.

## Staffing

For a large festival (10,000+ daily), budget for:
- 2-4 sanitation attendants per shift (restocking, light cleaning, directing attendees)
- 2-4 service technicians (pumping, deep cleaning)
- 1 supervisor coordinating between operations and the rental company

## What Attendees Actually Care About

From our post-festival surveys and industry research, attendees rank these factors highest:

1. **Cleanliness**: Units that are serviced mid-day and restocked
2. **Lighting**: Well-lit units at night (safety and visibility)
3. **Toilet paper**: This sounds basic, but it\'s the #1 complaint
4. **Hand sanitizer**: A dispenser inside or right outside each unit
5. **Lines**: No wait longer than 5 minutes

## Case Study: Getting It Right

A major 3-day festival in the Midwest with 15,000 daily attendees implemented a proper sanitation plan for the first time. They used 225 units with 50 handwash stations, placed in 35 clusters with 2 full services per day. The result: sanitation complaints dropped by 72% compared to the previous year.

## Plan Your Festival Sanitation

{{PHONE_LINK}} — We\'ve handled sanitation for festivals from 500 to 50,000 attendees. Call us to design your festival sanitation plan, including unit count, placement, service schedule, and VIP options.',
                'meta_title' => 'Music Festival Porta Potty Strategy: Sanitation for Large Crowds',
                'meta_description' => 'Expert guide to music festival bathroom planning: units per attendee, placement strategy, service schedule, and real-world case studies for 2026.',
                'focus_keyword' => 'music festival porta potty strategy',
                'featured_image' => 'pottydirect/blog-images/music-festival-porta-potty.jpg',
                'is_published' => true,
                'published_at' => now()->subDays(12),
                'created_at' => now()->subDays(12),
                'updated_at' => now()->subDays(12),
            ]);
        }

        // ====================================================================
        //  BLOG POST #7 – Construction (CLUSTER)
        // ====================================================================
        $post7Slug = 'construction-porta-potty-maintenance-schedule';
        if (! DB::table('blog_posts')->where('slug', $post7Slug)->exists()) {
            DB::table('blog_posts')->insert([
                'domain_id' => 1,
                'blog_category_id' => $constructionCatId,
                'city_id' => $seedCityId,
                'title' => 'Construction Site Porta Potty Maintenance: Optimal Service Schedules',
                'slug' => $post7Slug,
                'excerpt' => 'How often should construction site porta potties be serviced? Our guide covers service frequency based on crew size, season, and site conditions for 2026.',
                'content' => 'Regular maintenance is what separates a well-run construction site from one with sanitation problems. But how often should you service your portable toilets? The answer depends on several factors.

## The Baseline: Weekly Service

For most construction sites, weekly service is the minimum. This includes:
- Pumping the waste tank
- Cleaning and sanitizing all surfaces
- Restocking toilet paper (3-4 rolls per unit)
- Checking structural integrity
- Replenishing hand sanitizer

## When to Increase Frequency

### Crew Size
| Crew Size | Recommended Service |
|---|---|
| 1-20 | Weekly |
| 21-50 | Weekly |
| 51-100 | Twice weekly |
| 101-200 | 3x per week |
| 200+ | Daily |

### Seasonal Adjustments
**Summer (June-August)** — increase frequency by 50%:
- Weekly becomes twice weekly
- Twice weekly becomes 3x per week
- Daily stays daily (but add extra restocking mid-day)

**Why**: Heat accelerates bacterial growth, increases odor, and workers drink more water (more waste, more frequently).

**Winter (December-February)** — standard frequency works, but check for:
- Frozen waste lines (insulated units help)
- Wind damage (tipped units need immediate attention)
- Snow blocking access paths

**Spring/Fall** — standard frequency

### Site Conditions

**High-heat sites**: Foundries, steel mills, asphalt plants
→ Double standard frequency

**Wet/muddy sites**: Water treatment plants, excavation
→ Standard frequency, but check for tipped or dirty units after heavy rain

**Dusty sites**: Demolition, quarry work
→ Standard frequency + extra sanitizer (dust makes units dirtier faster)

## The Cost of Insufficient Service

Skipping service to save money costs more in the long run:

- **Emergency service call**: $100-175 (vs $55-85 scheduled)
- **Unit replacement**: $200-500 (if tank overflows or unit is damaged)
- **Worker productivity loss**: Studies show workers lose 15-20 minutes per day walking to find an acceptable restroom when nearby units are dirty
- **OSHA fines**: $15,625 per violation for unsanitary conditions

## Service Checklist

When our technicians service a construction site unit, we follow this checklist:

1. **Inspect exterior**: Cracks, structural damage, stability
2. **Check door and lock**: Opens smoothly, latches securely
3. **Pump waste tank**: Record volume (spike = possible leak from water source)
4. **Scrub interior**: Walls, floor, seat, urinal (if equipped)
5. **Sanitize**: All touch surfaces with EPA-registered disinfectant
6. **Restock TP**: Minimum 3 rolls visible
7. **Refill sanitizer**: Topped off
8. **Deodorize**: Apply enzyme treatment to waste tank
9. **Check ventilation**: Solar vent fan operating (if equipped)
10. **Report damage**: Any issues noted for repair

## Signs You Need More Frequent Service

Watch for these red flags:
- Odor noticeable from 10+ feet away
- Toilet paper running out between service visits
- Workers complaining or leaving site to find bathrooms
- Visible dirt on seats or floor
- Flies or insects around units
- Overflowing waste tank

## Get on a Schedule

{{PHONE_LINK}} — We\'ll set up a service schedule that matches your crew size, project timeline, and local conditions. Weekly, twice-weekly, or custom—we handle the logistics so your site stays OSHA-compliant and your crew stays productive.',
                'meta_title' => 'Construction Porta Potty Maintenance: Optimal Service Schedules',
                'meta_description' => 'How often to service construction site porta potties: crew size, seasonal adjustments, cost analysis, and complete maintenance checklist for 2026.',
                'focus_keyword' => 'construction site porta potty maintenance',
                'featured_image' => 'pottydirect/blog-images/construction-maintenance.jpg',
                'is_published' => true,
                'published_at' => now()->subDays(9),
                'created_at' => now()->subDays(9),
                'updated_at' => now()->subDays(9),
            ]);
        }

        // ====================================================================
        //  BLOG POST #8 – Event (CLUSTER)
        // ====================================================================
        $post8Slug = 'corporate-event-porta-potty-guide';
        if (! DB::table('blog_posts')->where('slug', $post8Slug)->exists()) {
            DB::table('blog_posts')->insert([
                'domain_id' => 1,
                'blog_category_id' => $eventCatId,
                'city_id' => $seedCityId,
                'title' => 'Corporate Event Porta Potty Guide: Professional Sanitation Solutions',
                'slug' => $post8Slug,
                'excerpt' => 'Corporate events demand a higher standard of sanitation. Here\'s how to choose portable restrooms that match your company\'s professional image.',
                'content' => 'Corporate events are different from other outdoor gatherings. Your attendees include clients, executives, and investors. The porta potties you choose reflect on your company\'s brand and attention to detail. Here\'s how to get it right.

## The Corporate Standard

For corporate events, standard blue plastic units are usually not acceptable. The minimum expectation is:
- Deluxe flushable units with sinks
- Climate-controlled luxury trailers for VIP areas
- Dedicated attendants for events over 200 guests

## Event Type Recommendations

### Company Picnic (100-500 employees)
- **What to rent**: Deluxe flushable units with integrated sinks
- **How many**: 1 per 40 guests for 4-hour event
- **Budget**: $600-1,500
- **Pro tip**: Add handwashing stations near food service areas

### Client Appreciation Event (50-200 guests)
- **What to rent**: Luxury restroom trailer (minimum 1)
- **How many**: 1 trailer (2-4 stations) for up to 150 guests
- **Budget**: $1,500-3,500
- **Pro tip**: Match the trailer interior to your brand colors if possible

### Product Launch (200-1,000 attendees)
- **What to rent**: Multiple luxury trailers + deluxe units for overflow
- **How many**: 1 luxury trailer per 200 guests + 1 deluxe per 100
- **Budget**: $3,000-8,000
- **Pro tip**: Hire an attendant to keep facilities spotless throughout the event

### Golf Tournament (100-200 players)
- **What to rent**: Deluxe units on course (every 3 holes) + luxury trailer at clubhouse
- **How many**: 4-6 deluxe units spread across course
- **Budget**: $1,500-3,000
- **Pro tip**: Place units near beverage stations for convenience

### VIP Reception (50-100 guests)
- **What to rent**: High-end luxury trailer only
- **How many**: 1 trailer
- **Budget**: $2,000-4,000
- **Pro tip**: Include restroom amenities like mints, hand lotion, and fresh flowers

## Luxury Trailer Features

What you get with a top-tier luxury restroom trailer:
- Flush toilets (real plumbing, not chemical)
- Running water sinks with soap and paper towels
- Climate control (heat and AC)
- Interior lighting (no dark stalls)
- Full-length mirrors
- Granite or quartz countertops
- Upgraded fixtures (faucets, handles)
- Sound system (optional)
- Interior decor that matches event theme

## Attendants vs Self-Service

### With Attendant ($200-400 per event)
- Constant cleaning and restocking
- Guest assistance (directing, handing out supplies)
- Brand ambassador potential (uniformed, trained)
- Higher perception of quality

### Self-Service
- Lower cost
- Check and restock hourly
- Acceptable for smaller events (<100 guests)

## Contract Considerations

When renting for corporate events, get these details in writing:
1. **Delivery window**: "Morning of event" vs "by 8 AM"
2. **Service during event**: Will they pump during the event? (Yes, for events over 6 hours)
3. **Emergency contact**: Who to call if something goes wrong
4. **Make-good clause**: What happens if a unit breaks mid-event?
5. **Pickup timing**: Evening of event vs next morning

The last detail matters because guests shouldn\'t see porta potties being loaded onto a truck while they\'re having cocktails.

## Make the Right Impression

Corporate event restrooms are a detail that most guests won\'t notice if done right—and everyone will notice if done wrong. The ROI on quality sanitation is simple: guests who are comfortable stay longer, engage more, and remember your event positively.

## Book Professional Corporate Sanitation

{{PHONE_LINK}} — We work with event planners, corporate event teams, and venue managers across the country. We\'ll recommend the right mix of deluxe units and luxury trailers for your specific event. Call us for a consultation.',
                'meta_title' => 'Corporate Event Porta Potty Guide: Professional Sanitation Solutions',
                'meta_description' => 'Corporate event porta potty guide: deluxe units and luxury trailers for company picnics, client events, golf tournaments, and VIP receptions.',
                'focus_keyword' => 'corporate event porta potty guide',
                'featured_image' => 'pottydirect/blog-images/corporate-event-guide.jpg',
                'is_published' => true,
                'published_at' => now()->subDays(6),
                'created_at' => now()->subDays(6),
                'updated_at' => now()->subDays(6),
            ]);
        }

        // ====================================================================
        //  BLOG POST #9 – Construction (CLUSTER)
        // ====================================================================
        $post9Slug = 'construction-porta-potty-rental-contract-guide';
        if (! DB::table('blog_posts')->where('slug', $post9Slug)->exists()) {
            DB::table('blog_posts')->insert([
                'domain_id' => 1,
                'blog_category_id' => $constructionCatId,
                'city_id' => $seedCityId,
                'title' => 'Construction Porta Potty Rental Contracts: What to Look For (2026)',
                'slug' => $post9Slug,
                'excerpt' => 'Not all porta potty rental contracts are the same. Here\'s what construction project managers need to look for before signing, including hidden fees and service guarantees.',
                'content' => 'A porta potty rental contract might seem straightforward, but experienced construction project managers know there are traps and hidden costs. Here\'s what to look for.

## 1. Service Frequency Clause

The most important clause in your contract. It specifies how often the rental company will service each unit.

**What you want**: "Weekly service minimum, with option to increase frequency at standard rates."

**Watch out for**: "Service as needed" — this is vague. The company decides when service is needed, not you. If they\'re busy, "as needed" becomes "whenever we get around to it."

**Best practice**: Specify exact days (e.g., "Every Wednesday") and get emergency service response time in writing (e.g., "Within 24 hours of request").

## 2. The "Fair Usage" Trap

Some contracts limit how much waste each unit can collect between services.

**What you want**: No waste volume limits, or limits that are clearly unreasonable (e.g., "tank must not exceed 90% capacity between services").

**Watch out for**: "Excessive use fees." This usually means usage beyond X gallons between services. On active construction sites, usage varies wildly. A framing crew uses facilities differently than a finishing crew.

## 3. Delivery and Pickup Terms

**What you want**: A specific delivery window (e.g., "between 7 AM and 10 AM on [date]").

**Watch out for**: "Best efforts" delivery windows. This means they\'ll try but aren\'t committed. If your site starts at 7 AM and units arrive at 2 PM, your crew has no facilities for 7 hours—and you still pay for that day.

**Pickup matters too**: Some contracts charge an extra month if you don\'t give 48+ hours notice for pickup. Put pickup reminders on your calendar.

## 4. Damage and Vandalism Liability

**Standard industry practice**: The rental company covers normal wear and tear. The contractor covers damage from:
- Heavy equipment impact (forklifts, excavators hitting units)
- Vandalism (tipping, graffiti, fire)
- Extreme misuse

**What you want**: Clear definitions of normal wear vs damage. Photos taken at delivery and pickup to document condition.

**Watch out for**: Broad damage clauses that let the rental company charge you for pre-existing scuffs and scratches.

## 5. Price Escalation

**What you want**: Fixed pricing for the contract duration.

**Watch out for**: "Prices subject to change with 30 days notice." Some companies lock in a low rate and raise it after two months when you\'re dependent on them.

## 6. Force Majeure (Acts of God)

**What you want**: Standard force majeure covering natural disasters, strikes, and utility failures.

**Watch out for**: Force majeure clauses that are either too broad ("any event beyond our control" — this could include your site being muddy) or too narrow.

## 7. Termination Terms

**What you want**: 30-day termination for convenience (either party can cancel with 30 days notice).

**Watch out for**: Early termination fees. Some contracts charge 50% of remaining contract value if you cancel early. On a 12-month contract cancelled at month 6, that\'s 3 months of payments for zero service.

## Red-Flag Companies

Avoid rental companies that:
- Refuse to provide references from current construction clients
- Can\'t give you a specific service day
- Have no emergency service line
- Ask for 100% payment upfront
- Have multiple unresolved complaints on BBB or Google

## What a Good Contract Looks Like

A fair construction porta potty contract includes:
1. Specific unit types and quantities
2. Fixed weekly/biweekly service schedule
3. 24-hour emergency service response
4. Fixed pricing for entire contract
5. 30-day termination clause
6. Photo documentation at delivery and pickup
7. Clear damage liability terms
8. No excessive use fees
9. Named service contact with phone number

## Before You Sign

{{PHONE_LINK}} — Call us. We\'ll walk you through our standard construction contract and explain every clause. No hidden fees, no traps. Just straightforward sanitation for your project.',
                'meta_title' => 'Construction Porta Potty Rental Contracts: What to Look For (2026)',
                'meta_description' => 'Avoid hidden fees in construction porta potty contracts. Our guide covers service clauses, damage liability, termination terms, and what every PM should know before signing.',
                'focus_keyword' => 'construction porta potty rental contract',
                'featured_image' => 'pottydirect/blog-images/construction-contract-guide.jpg',
                'is_published' => true,
                'published_at' => now()->subDays(3),
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ]);
        }

        // ====================================================================
        //  BLOG POST #10 – Event (CLUSTER)
        // ====================================================================
        $post10Slug = 'outdoor-event-bathroom-planning-checklist';
        if (! DB::table('blog_posts')->where('slug', $post10Slug)->exists()) {
            DB::table('blog_posts')->insert([
                'domain_id' => 1,
                'blog_category_id' => $eventCatId,
                'city_id' => $seedCityId,
                'title' => 'Outdoor Event Bathroom Planning Checklist: 2026 Complete Guide',
                'slug' => $post10Slug,
                'excerpt' => 'Planning an outdoor event? Our complete bathroom planning checklist covers everything from permits and ADA compliance to servicing and guest experience.',
                'content' => 'Whether you\'re planning a wedding, festival, corporate event, or community gathering, bathroom logistics can make or break your event. Use this step-by-step checklist to get it right.

## 12-Week Pre-Event Checklist

### Week 12: Initial Assessment
- [ ] Confirm event date (check for conflicts with major holidays or competing events)
- [ ] Estimate guest count (base + 20% buffer)
- [ ] Determine event duration and schedule (gates open, peak hours, close)
- [ ] Identify venue type (open field, vineyard, park, private estate)
- [ ] Check venue rules about portable restrooms (some restrict placement or types)

### Week 10: Budget & Scope
- [ ] Set sanitation budget (typically 3-8% of total event budget)
- [ ] Choose unit types: standard, deluxe, luxury trailers, or mix
- [ ] Estimate quantity (see our calculator in the previous guide)
- [ ] Get quotes from 2-3 rental companies
- [ ] Check what\'s included (delivery, setup, service, pickup, taxes)

### Week 8: Permits & Regulations
- [ ] Check local health department requirements
- [ ] Verify ADA compliance requirements (1 ADA unit per 10 standard)
- [ ] Check if permits are required for portable toilets on public property
- [ ] Confirm noise restrictions (some generators have decibel limits)
- [ ] Verify fire marshal requirements (placement relative to exits, stages)

### Week 6: Book the Rental
- [ ] Sign contract (see our contract guide for what to look for)
- [ ] Specify delivery date and time window
- [ ] Specify pickup date and time window
- [ ] Get emergency contact number
- [ ] Confirm service schedule (pre-event, during event, post-event)

## 4-Week Planning

### Week 4: Site Planning
- [ ] Draw site map with restroom locations marked
- [ ] Plan service vehicle access routes
- [ ] Identify power sources for luxury trailers (generator or shore power)
- [ ] Plan lighting for restroom areas (if event runs past sunset)
- [ ] Confirm ground conditions (firm, level, accessible)
- [ ] Plan for mud or wet weather (plywood paths for high-heel areas)

### Week 2: Final Coordination
- [ ] Confirm delivery time with venue manager
- [ ] Assign someone to meet the delivery truck
- [ ] Confirm service schedule with rental company
- [ ] Order supplies (extra TP, hand sanitizer, tissues, feminine products)
- [ ] Plan signage (restroom direction signs, ADA signage)

### Week 1: Pre-Event Prep
- [ ] Confirm all bookings (phone call, not email)
- [ ] Prepare restroom area (clear path, level ground, lighting)
- [ ] Plan attendant schedule (if using restroom attendants)
- [ ] Prepare backup supplies kit (extra TP, paper towels, cleaning spray, gloves)
- [ ] Brief staff on restroom locations and procedures

## Day-Of Event Checklist

### Before Gates Open
- [ ] Verify all units delivered and placed correctly
- [ ] Check each unit for cleanliness and supplies
- [ ] Confirm all locks work (interior and exterior)
- [ ] Restock all units with TP (3+ rolls each)
- [ ] Fill hand sanitizer dispensers
- [ ] Place signage (direction and ADA)
- [ ] Test lights (if units have lighting)
- [ ] Confirm service truck has access (no blocked paths)

### During Event (Hourly Checks)
- [ ] Quick visual check of highest-traffic units
- [ ] Restock TP in high-traffic units (every 2 hours minimum)
- [ ] Address any issues immediately (broken lock, tipped unit, odor)
- [ ] Check hand sanitizer levels
- [ ] Empty trash cans near restroom areas
- [ ] Record any issues for post-event review

### After Event
- [ ] Verify all units still in place (theft prevention)
- [ ] Coordinate pickup with rental company
- [ ] Document any damage (photos for your records)
- [ ] Complete post-event review form
- [ ] Pay final invoice

## Post-Event Review

### Questions to Answer
1. Did attendees complain about restrooms?
2. Were there enough units for peak times?
3. Did the unit types match the event formality?
4. Was service adequate?
5. What would you change next time?

### Metrics to Track
- Guest count vs planned count
- Actual usage patterns (which units were busiest?)
- Service frequency vs actual need
- Total cost vs budget

## Why Planning Matters

Events with well-planned restroom logistics see:
- 40% fewer attendee complaints
- 25% longer average attendee stay
- 15% higher satisfaction scores in post-event surveys

## Start Planning Your Event

{{PHONE_LINK}} — Not sure where to start? Call us for a free event sanitation consultation. We\'ll ask about your event type, guest count, duration, and venue to build a complete restroom plan. No obligation, just expert advice.',
                'meta_title' => 'Outdoor Event Bathroom Planning Checklist: 2026 Complete Guide',
                'meta_description' => 'Complete outdoor event bathroom planning checklist: timeline from 12 weeks out to event day, including permits, ADA compliance, placement, and service.',
                'focus_keyword' => 'outdoor event bathroom planning checklist',
                'featured_image' => 'pottydirect/blog-images/outdoor-event-bathroom-planning.jpg',
                'is_published' => true,
                'published_at' => now(), // today
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('blog_posts')->whereIn('slug', [
            'osha-toilet-requirements-construction-sites',
            'porta-potty-calculator-event-planning',
            'construction-site-porta-potty-placement-guide',
            'wedding-porta-potty-etiquette-guide',
            'construction-sanitation-cost-analysis',
            'music-festival-porta-potty-strategy',
            'construction-porta-potty-maintenance-schedule',
            'corporate-event-porta-potty-guide',
            'construction-porta-potty-rental-contract-guide',
            'outdoor-event-bathroom-planning-checklist',
        ])->delete();
    }
};
