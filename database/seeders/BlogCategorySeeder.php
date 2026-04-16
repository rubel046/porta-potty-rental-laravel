<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use App\Models\Domain;
use Illuminate\Database\Seeder;

class BlogCategorySeeder extends Seeder
{
    public function run(): void
    {
        $domains = Domain::get();

        if ($domains->isEmpty()) {
            $this->seedDefaultCategories(null);

            return;
        }

        foreach ($domains as $domain) {
            $this->seedDefaultCategories($domain->id);
        }
    }

    private function seedDefaultCategories(?int $domainId): void
    {
        $categories = [
            // === PRICING & COSTS (30) ===
            ['name' => 'Pricing & Costs', 'slug' => 'pricing-costs', 'description' => 'Porta potty rental pricing guides and cost breakdowns', 'icon' => '💰'],
            ['name' => 'Budget-Friendly Rentals', 'slug' => 'budget-friendly-rentals', 'description' => 'Affordable porta potty options and money-saving tips', 'icon' => '💵'],
            ['name' => 'Daily Rental Rates', 'slug' => 'daily-rental-rates', 'description' => 'Daily rental rate information', 'icon' => '🗓️'],
            ['name' => 'Weekly Rental Rates', 'slug' => 'weekly-rental-rates', 'description' => 'Weekly rental rate information', 'icon' => '📅'],
            ['name' => 'Monthly Rental Rates', 'slug' => 'monthly-rental-rates', 'description' => 'Monthly rental options and pricing', 'icon' => '📆'],
            ['name' => 'Cost Comparison', 'slug' => 'cost-comparison', 'description' => 'Compare prices between different providers', 'icon' => '📊'],
            ['name' => 'Bulk Order Discounts', 'slug' => 'bulk-order-discounts', 'description' => 'Discounts for large orders', 'icon' => '📦'],
            ['name' => 'Long-Term Rental Savings', 'slug' => 'long-term-savings', 'description' => 'Save money with long-term rentals', 'icon' => '💳'],
            ['name' => 'Hidden Fees Explained', 'slug' => 'hidden-fees', 'description' => 'Understanding all potential fees', 'icon' => '🔍'],
            ['name' => 'Delivery Costs', 'slug' => 'delivery-costs', 'description' => 'Delivery fee structures', 'icon' => '🚚'],
            ['name' => 'Pickup Fees', 'slug' => 'pickup-fees', 'description' => 'Pickup and removal fees', 'icon' => '↩️'],
            ['name' => 'Cleaning Fees', 'slug' => 'cleaning-fees', 'description' => 'Cleaning fee policies', 'icon' => '🧹'],
            ['name' => 'Overage Charges', 'slug' => 'overage-charges', 'description' => 'Overtime and extra use fees', 'icon' => '⏰'],
            ['name' => 'Damage Waiver Costs', 'slug' => 'damage-waiver', 'description' => 'Protection plan pricing', 'icon' => '🛡️'],
            ['name' => 'Security Deposit', 'slug' => 'security-deposit', 'description' => 'Deposit requirements', 'icon' => '🔒'],
            ['name' => 'Cancellation Policy', 'slug' => 'cancellation-policy', 'description' => 'Cancellation terms and fees', 'icon' => '❌'],
            ['name' => 'Rescheduling Options', 'slug' => 'rescheduling', 'description' => 'How to reschedule your rental', 'icon' => '🔄'],
            ['name' => 'Military Discounts', 'slug' => 'military-discounts', 'description' => 'Discounts for military personnel', 'icon' => '🎖️'],
            ['name' => 'Senior Discounts', 'slug' => 'senior-discounts', 'description' => 'Discounts for seniors', 'icon' => '👴'],
            ['name' => 'Non-Profit Discounts', 'slug' => 'nonprofit-discounts', 'description' => 'Discounts for non-profits', 'icon' => '❤️'],
            ['name' => 'First-Time Renter Discounts', 'slug' => 'first-time-discounts', 'description' => 'Discounts for new customers', 'icon' => '🌟'],
            ['name' => 'Early Bird Discounts', 'slug' => 'early-bird-discounts', 'description' => 'Book early and save', 'icon' => '🐦'],
            ['name' => 'Weekday vs Weekend Pricing', 'slug' => 'weekday-weekend', 'description' => 'Price differences by day', 'icon' => '📆'],
            ['name' => 'Seasonal Pricing', 'slug' => 'seasonal-pricing', 'description' => 'How seasons affect prices', 'icon' => '☀️'],
            ['name' => 'Holiday Pricing', 'slug' => 'holiday-pricing', 'description' => 'Holiday rate adjustments', 'icon' => '🎄'],
            ['name' => 'Group Rates', 'slug' => 'group-rates', 'description' => 'Group booking discounts', 'icon' => '👥'],
            ['name' => 'Referral Discounts', 'slug' => 'referral-discounts', 'description' => 'Refer a friend savings', 'icon' => '👫'],
            ['name' => 'Loyalty Programs', 'slug' => 'loyalty-programs', 'description' => 'Repeat customer benefits', 'icon' => '🏆'],
            ['name' => 'Price Matching', 'slug' => 'price-matching', 'description' => 'We match competitor prices', 'icon' => '🤝'],
            ['name' => 'Free Quote Requests', 'slug' => 'free-quotes', 'description' => 'Get your free quote', 'icon' => '📝'],

            // === EVENT PLANNING (40) ===
            ['name' => 'Event Planning', 'slug' => 'event-planning', 'description' => 'Tips for planning portable restrooms at events', 'icon' => '🎉'],
            ['name' => 'Guest Count Calculator', 'slug' => 'guest-count-calculator', 'description' => 'How many units for your guest count', 'icon' => '➗'],
            ['name' => 'Music Festivals', 'slug' => 'music-festivals', 'description' => 'Porta potty for music festivals', 'icon' => '🎵'],
            ['name' => 'Concerts', 'slug' => 'concerts', 'description' => 'Concert restroom planning', 'icon' => '🎸'],
            ['name' => 'Sporting Events', 'slug' => 'sporting-events', 'description' => 'Portable toilets for sports events', 'icon' => '⚽'],
            ['name' => 'Marathons', 'slug' => 'marathons', 'description' => 'Race event sanitation', 'icon' => '🏃'],
            ['name' => 'Triathlons', 'slug' => 'triathlons', 'description' => 'Triathlon sanitation', 'icon' => '🏊'],
            ['name' => 'Tailgate Parties', 'slug' => 'tailgate-parties', 'description' => 'Tailgating event restrooms', 'icon' => '🏈'],
            ['name' => 'Food Festivals', 'slug' => 'food-festivals', 'description' => 'Sanitation for food events', 'icon' => '🍔'],
            ['name' => 'Wine & Beer Festivals', 'slug' => 'wine-beer-festivals', 'description' => 'Festival sanitation', 'icon' => '🍺'],
            ['name' => 'Farmers Markets', 'slug' => 'farmers-markets', 'description' => 'Market sanitation', 'icon' => '🥬'],
            ['name' => 'Craft Fairs', 'slug' => 'craft-fairs', 'description' => 'Craft fair restrooms', 'icon' => '🎨'],
            ['name' => 'Carnivals', 'slug' => 'carnivals', 'description' => 'Carnival sanitation', 'icon' => '🎡'],
            ['name' => 'State Fairs', 'slug' => 'state-fairs', 'description' => 'State fair restrooms', 'icon' => '🎪'],
            ['name' => 'Community Events', 'slug' => 'community-events', 'description' => 'Community gathering solutions', 'icon' => '🏘️'],
            ['name' => 'Fundraisers', 'slug' => 'fundraisers', 'description' => 'Charity event restrooms', 'icon' => '❤️'],
            ['name' => 'Charity Runs', 'slug' => 'charity-runs', 'description' => 'Charity run sanitation', 'icon' => '🏃‍♀️'],
            ['name' => 'Walkathons', 'slug' => 'walkathons', 'description' => 'Walkathon sanitation', 'icon' => '🚶'],
            ['name' => 'Outdoor Movies', 'slug' => 'outdoor-movies', 'description' => 'Movie night restrooms', 'icon' => '🎬'],
            ['name' => 'Drive-In Movies', 'slug' => 'drive-in-movies', 'description' => 'Drive-in movie facilities', 'icon' => '🚗'],
            ['name' => 'Car Shows', 'slug' => 'car-shows', 'description' => 'Auto show sanitation', 'icon' => '🚙'],
            ['name' => 'Air Shows', 'slug' => 'air-shows', 'description' => 'Air show facilities', 'icon' => '✈️'],
            ['name' => 'Trade Shows', 'slug' => 'trade-shows', 'description' => 'Trade show restrooms', 'icon' => '🏪'],
            ['name' => 'Expos', 'slug' => 'expos', 'description' => 'Expo sanitation', 'icon' => '🎤'],
            ['name' => 'Corporate Events', 'slug' => 'corporate-events', 'description' => 'Corporate gathering facilities', 'icon' => '🏢'],
            ['name' => 'Company Picnics', 'slug' => 'company-picnics', 'description' => 'Company picnic sanitation', 'icon' => '🍔'],
            ['name' => 'Holiday Parties', 'slug' => 'holiday-parties', 'description' => 'Holiday party restrooms', 'icon' => '🎄'],
            ['name' => 'New Year Events', 'slug' => 'new-year-events', 'description' => 'New Year celebration facilities', 'icon' => '🎆'],
            ['name' => 'Fourth of July', 'slug' => 'fourth-july', 'description' => 'Independence day sanitation', 'icon' => '🇺🇸'],
            ['name' => 'Thanksgiving Events', 'slug' => 'thanksgiving', 'description' => 'Thanksgiving gathering facilities', 'icon' => '🦃'],
            ['name' => 'Easter Events', 'slug' => 'easter-events', 'description' => 'Easter celebration sanitation', 'icon' => '🐰'],
            ['name' => 'Halloween Parties', 'slug' => 'halloween', 'description' => 'Halloween event facilities', 'icon' => '🎃'],
            ['name' => 'Birthday Parties', 'slug' => 'birthday-parties', 'description' => 'Birthday party sanitation', 'icon' => '🎂'],
            ['name' => 'Graduation Parties', 'slug' => 'graduation-parties', 'description' => 'Graduation event facilities', 'icon' => '🎓'],
            ['name' => 'Baby Showers', 'slug' => 'baby-showers', 'description' => 'Baby shower sanitation', 'icon' => '👶'],
            ['name' => 'Retirement Parties', 'slug' => 'retirement-parties', 'description' => 'Retirement celebration facilities', 'icon' => '🏖️'],
            ['name' => 'Family Reunions', 'slug' => 'family-reunions', 'description' => 'Family reunion sanitation', 'icon' => '👨‍👩‍👧‍👦'],
            ['name' => 'Class Reunions', 'slug' => 'class-reunions', 'description' => 'Class reunion facilities', 'icon' => '🏫'],

            // === CONSTRUCTION & INDUSTRIAL (35) ===
            ['name' => 'Construction Sites', 'slug' => 'construction-sites', 'description' => 'Construction site sanitation', 'icon' => '🏗️'],
            ['name' => 'OSHA Requirements', 'slug' => 'osha-requirements', 'description' => 'OSHA sanitation requirements', 'icon' => '📋'],
            ['name' => 'Building Construction', 'slug' => 'building-construction', 'description' => 'Building project restrooms', 'icon' => '🔨'],
            ['name' => 'Home Building', 'slug' => 'home-building', 'description' => 'New home construction', 'icon' => '🏠'],
            ['name' => 'Commercial Building', 'slug' => 'commercial-building', 'description' => 'Commercial construction', 'icon' => '🏢'],
            ['name' => 'Road Construction', 'slug' => 'road-construction', 'description' => 'Road work sanitation', 'icon' => '🛣️'],
            ['name' => 'Highway Work', 'slug' => 'highway-work', 'description' => 'Highway project restrooms', 'icon' => '🛣️'],
            ['name' => 'Bridge Construction', 'slug' => 'bridge-construction', 'description' => 'Bridge project facilities', 'icon' => '🌉'],
            ['name' => 'Utility Installation', 'slug' => 'utility-installation', 'description' => 'Utility work sanitation', 'icon' => '⚡'],
            ['name' => 'Plumbing Work', 'slug' => 'plumbing-work', 'description' => 'Plumbing project facilities', 'icon' => '🔧'],
            ['name' => 'Electrical Work', 'slug' => 'electrical-work', 'description' => 'Electrical project restrooms', 'icon' => '💡'],
            ['name' => 'HVAC Installation', 'slug' => 'hvac-installation', 'description' => 'HVAC project sanitation', 'icon' => '❄️'],
            ['name' => 'Roofing Projects', 'slug' => 'roofing-projects', 'description' => 'Roofing job facilities', 'icon' => '🏠'],
            ['name' => 'Demolition', 'slug' => 'demolition', 'description' => 'Demolition site restrooms', 'icon' => '💣'],
            ['name' => 'Excavation', 'slug' => 'excavation', 'description' => 'Excavation work sanitation', 'icon' => '挖'],
            ['name' => 'Landscaping', 'slug' => 'landscaping', 'description' => 'Landscaping project facilities', 'icon' => '🌳'],
            ['name' => 'Pool Construction', 'slug' => 'pool-construction', 'description' => 'Pool building sanitation', 'icon' => '🏊'],
            ['name' => 'Deck Building', 'slug' => 'deck-building', 'description' => 'Deck construction facilities', 'icon' => '🪵'],
            ['name' => 'Fence Installation', 'slug' => 'fence-installation', 'description' => 'Fence project sanitation', 'icon' => '🚧'],
            ['name' => 'Foundation Work', 'slug' => 'foundation-work', 'description' => 'Foundation project facilities', 'icon' => '🏗️'],
            ['name' => 'Masonry Projects', 'slug' => 'masonry-projects', 'description' => 'Masonry work sanitation', 'icon' => '🧱'],
            ['name' => 'Painting Projects', 'slug' => 'painting-projects', 'description' => 'Painting project facilities', 'icon' => '🎨'],
            ['name' => 'Remodeling', 'slug' => 'remodeling', 'description' => 'Home remodeling sanitation', 'icon' => '🔨'],
            ['name' => 'Renovation', 'slug' => 'renovation', 'description' => 'Home renovation facilities', 'icon' => '🏚️'],
            ['name' => 'Industrial Sites', 'slug' => 'industrial-sites', 'description' => 'Industrial project sanitation', 'icon' => '🏭'],
            ['name' => 'Warehouse Projects', 'slug' => 'warehouse-projects', 'description' => 'Warehouse construction', 'icon' => '📦'],
            ['name' => 'Factory Work', 'slug' => 'factory-work', 'description' => 'Factory project facilities', 'icon' => '🏭'],
            ['name' => 'Oil & Gas Fields', 'slug' => 'oil-gas-fields', 'description' => 'Oil field sanitation', 'icon' => '🛢️'],
            ['name' => 'Mining Operations', 'slug' => 'mining-operations', 'description' => 'Mining project facilities', 'icon' => '⛏️'],
            ['name' => 'Solar Farms', 'slug' => 'solar-farms', 'description' => 'Solar farm sanitation', 'icon' => '☀️'],
            ['name' => 'Wind Farms', 'slug' => 'wind-farms', 'description' => 'Wind farm facilities', 'icon' => '🌬️'],
            ['name' => 'Pipeline Projects', 'slug' => 'pipeline-projects', 'description' => 'Pipeline construction', 'icon' => '🛢️'],
            ['name' => 'Railroad Work', 'slug' => 'railroad-work', 'description' => 'Railroad project facilities', 'icon' => '🚂'],
            ['name' => 'Airport Projects', 'slug' => 'airport-projects', 'description' => 'Airport construction', 'icon' => '✈️'],

            // === WEDDINGS & LUXURY (30) ===
            ['name' => 'Weddings', 'slug' => 'weddings', 'description' => 'Wedding restroom planning', 'icon' => '💒'],
            ['name' => 'Luxury Restrooms', 'slug' => 'luxury-restrooms', 'description' => 'Premium restroom solutions', 'icon' => '✨'],
            ['name' => 'Wedding Trailers', 'slug' => 'wedding-trailers', 'description' => 'Elegant wedding trailers', 'icon' => '💕'],
            ['name' => 'VIP Events', 'slug' => 'vip-events', 'description' => 'VIP event facilities', 'icon' => '⭐'],
            ['name' => 'Destination Weddings', 'slug' => 'destination-weddings', 'description' => 'Destination wedding restrooms', 'icon' => '🏝️'],
            ['name' => 'Beach Weddings', 'slug' => 'beach-weddings', 'description' => 'Beach wedding facilities', 'icon' => '🏖️'],
            ['name' => 'Garden Weddings', 'slug' => 'garden-weddings', 'description' => 'Garden wedding sanitation', 'icon' => '🌸'],
            ['name' => 'Barn Weddings', 'slug' => 'barn-weddings', 'description' => 'Barn wedding facilities', 'icon' => '🏚️'],
            ['name' => 'Vineyard Weddings', 'slug' => 'vineyard-weddings', 'description' => 'Vineyard wedding sanit', 'icon' => '🍇'],
            ['name' => 'Estate Weddings', 'slug' => 'estate-weddings', 'description' => 'Estate wedding facilities', 'icon' => '🏰'],
            ['name' => 'Ranch Weddings', 'slug' => 'ranch-weddings', 'description' => 'Ranch wedding sanitation', 'icon' => '🤠'],
            ['name' => 'Mountain Weddings', 'slug' => 'mountain-weddings', 'description' => 'Mountain wedding facilities', 'icon' => '⛰️'],
            ['name' => 'Lake Weddings', 'slug' => 'lake-weddings', 'description' => 'Lake wedding sanitation', 'icon' => '🌊'],
            ['name' => 'Church Weddings', 'slug' => 'church-weddings', 'description' => 'Outdoor church weddings', 'icon' => '⛪'],
            ['name' => 'Intimate Weddings', 'slug' => 'intimate-weddings', 'description' => 'Small wedding facilities', 'icon' => '💑'],
            ['name' => 'Elopements', 'slug' => 'elopements', 'description' => 'Elopement sanitation', 'icon' => '💍'],
            ['name' => 'Cocktail Parties', 'slug' => 'cocktail-parties', 'description' => 'Cocktail party facilities', 'icon' => '🍸'],
            ['name' => 'Gala Events', 'slug' => 'gala-events', 'description' => 'Gala event sanitation', 'icon' => '🎩'],
            ['name' => 'Black Tie Events', 'slug' => 'black-tie-events', 'description' => 'Formal event facilities', 'icon' => '👔'],
            ['name' => 'Award Ceremonies', 'slug' => 'award-ceremonies', 'description' => 'Award ceremony sanitation', 'icon' => '🏅'],
            ['name' => 'Movie Premieres', 'slug' => 'movie-premieres', 'description' => 'Premiere event facilities', 'icon' => '🎬'],
            ['name' => 'Product Launches', 'slug' => 'product-launches', 'description' => 'Product launch sanitation', 'icon' => '📣'],
            ['name' => 'VIP Lounge', 'slug' => 'vip-lounge', 'description' => 'VIP lounge facilities', 'icon' => '💺'],
            ['name' => 'Red Carpet Events', 'slug' => 'red-carpet', 'description' => 'Red carpet sanitation', 'icon' => '🟥'],
            ['name' => 'Celebrity Events', 'slug' => 'celebrity-events', 'description' => 'Celebrity event facilities', 'icon' => '🌟'],
            ['name' => 'Political Events', 'slug' => 'political-events', 'description' => 'Political rally sanitation', 'icon' => '🗳️'],
            ['name' => 'Press Conferences', 'slug' => 'press-conferences', 'description' => 'Press conference facilities', 'icon' => '📰'],
            ['name' => 'Private Parties', 'slug' => 'private-parties', 'description' => 'Exclusive party sanitation', 'icon' => '🔒'],
            ['name' => 'After Parties', 'slug' => 'after-parties', 'description' => 'After party facilities', 'icon' => '🎊'],
            ['name' => 'Engagement Parties', 'slug' => 'engagement-parties', 'description' => 'Engagement party sanitation', 'icon' => '💍'],

            // === EMERGENCY SERVICES (20) ===
            ['name' => 'Emergency Services', 'slug' => 'emergency-services', 'description' => 'Emergency rental information', 'icon' => '🚨'],
            ['name' => 'Same-Day Delivery', 'slug' => 'same-day-delivery', 'description' => 'Same-day delivery options', 'icon' => '⚡'],
            ['name' => '24/7 Availability', 'slug' => '24-7-availability', 'description' => 'Round-the-clock service', 'icon' => '🕐'],
            ['name' => 'Disaster Relief', 'slug' => 'disaster-relief', 'description' => 'Disaster relief sanitation', 'icon' => '🌪️'],
            ['name' => 'Flood Response', 'slug' => 'flood-response', 'description' => 'Post-flood sanitation', 'icon' => '🌊'],
            ['name' => 'Hurricane Response', 'slug' => 'hurricane-response', 'description' => 'Hurricane relief facilities', 'icon' => '🌀'],
            ['name' => 'Tornado Response', 'slug' => 'tornado-response', 'description' => 'Tornado relief sanitation', 'icon' => '🌪️'],
            ['name' => 'Fire Recovery', 'slug' => 'fire-recovery', 'description' => 'Post-fire sanitation', 'icon' => '🔥'],
            ['name' => 'Storm Damage', 'slug' => 'storm-damage', 'description' => 'Storm cleanup facilities', 'icon' => '⛈️'],
            ['name' => 'Earthquake Response', 'slug' => 'earthquake-response', 'description' => 'Earthquake relief', 'icon' => '🌋'],
            ['name' => 'Utility Outages', 'slug' => 'utility-outages', 'description' => 'Power outage facilities', 'icon' => '⚡'],
            ['name' => 'Water Main Breaks', 'slug' => 'water-main-breaks', 'description' => 'Water main break response', 'icon' => '💧'],
            ['name' => 'Sewer Backups', 'slug' => 'sewer-backups', 'description' => 'Sewer backup facilities', 'icon' => '🚿'],
            ['name' => 'Healthcare Facilities', 'slug' => 'healthcare-facilities', 'description' => 'Medical facility sanitation', 'icon' => '🏥'],
            ['name' => 'Emergency Shelters', 'slug' => 'emergency-shelters', 'description' => 'Shelter sanitation', 'icon' => '🏠'],
            ['name' => 'First Responders', 'slug' => 'first-responders', 'description' => 'Responder facilities', 'icon' => '🚑'],
            ['name' => 'Search & Rescue', 'slug' => 'search-rescue', 'description' => 'Rescue operation sanitation', 'icon' => '🔍'],
            ['name' => 'Military Operations', 'slug' => 'military-operations', 'description' => 'Military field facilities', 'icon' => '🎖️'],
            ['name' => 'Disaster Cleanup', 'slug' => 'disaster-cleanup', 'description' => 'Cleanup operation sanitation', 'icon' => '🧹'],
            ['name' => 'Emergency Backup', 'slug' => 'emergency-backup', 'description' => 'Backup sanitation needs', 'icon' => '🔙'],

            // === RESIDENTIAL (25) ===
            ['name' => 'Residential Rentals', 'slug' => 'residential-rentals', 'description' => 'Homeowner rental options', 'icon' => '🏠'],
            ['name' => 'Home Renovations', 'slug' => 'home-renovations', 'description' => 'Home renovation sanitation', 'icon' => '🔧'],
            ['name' => 'Kitchen Remodels', 'slug' => 'kitchen-remodels', 'description' => 'Kitchen remodel facilities', 'icon' => '🍳'],
            ['name' => 'Bathroom Remodels', 'slug' => 'bathroom-remodels', 'description' => 'Bathroom remodel sanitation', 'icon' => '🚿'],
            ['name' => 'ADU Construction', 'slug' => 'adu-construction', 'description' => 'Guest house building', 'icon' => '🏡'],
            ['name' => 'Room Additions', 'slug' => 'room-additions', 'description' => 'Addition project facilities', 'icon' => '➕'],
            ['name' => 'Garage Builds', 'slug' => 'garage-builds', 'description' => 'Garage construction', 'icon' => '🚗'],
            ['name' => 'Shed Construction', 'slug' => 'shed-construction', 'description' => 'Shed building sanitation', 'icon' => '🏗️'],
            ['name' => 'Pool Installation', 'slug' => 'pool-installation', 'description' => 'Pool building facilities', 'icon' => '🏊'],
            ['name' => 'Deck Building', 'slug' => 'deck-building', 'description' => 'Deck construction', 'icon' => '🪵'],
            ['name' => 'Fence Building', 'slug' => 'fence-building', 'description' => 'Fence installation', 'icon' => '🚧'],
            ['name' => 'Landscape Design', 'slug' => 'landscape-design', 'description' => 'Landscape project facilities', 'icon' => '🌳'],
            ['name' => 'Foreclosure Cleanup', 'slug' => 'foreclosure-cleanup', 'description' => 'Property cleanup', 'icon' => '🏚️'],
            ['name' => 'Estate Sales', 'slug' => 'estate-sales', 'description' => 'Estate sale facilities', 'icon' => '🏛️'],
            ['name' => 'Moving Day', 'slug' => 'moving-day', 'description' => 'Moving day sanitation', 'icon' => '📦'],
            ['name' => 'Deep Cleaning', 'slug' => 'deep-cleaning', 'description' => 'Deep cleaning facilities', 'icon' => '🧹'],
            ['name' => 'Hoarding Cleanup', 'slug' => 'hoarding-cleanup', 'description' => 'Hoarding cleanup sanitation', 'icon' => '📦'],
            ['name' => 'Animal Hoarding', 'slug' => 'animal-hoarding', 'description' => 'Animal facility cleanup', 'icon' => '🐾'],
            ['name' => 'Crime Scene Cleanup', 'slug' => 'crime-scene-cleanup', 'description' => 'Biohazard cleanup', 'icon' => '🧪'],
            ['name' => 'Meth Lab Cleanup', 'slug' => 'meth-lab-cleanup', 'description' => 'Meth lab decontamination', 'icon' => '☣️'],
            ['name' => 'Teardowns', 'slug' => 'teardowns', 'description' => 'Home teardown facilities', 'icon' => '🔨'],
            ['name' => 'Interior Demo', 'slug' => 'interior-demo', 'description' => 'Interior demolition', 'icon' => '💥'],
            ['name' => 'Asbestos Abatement', 'slug' => 'asbestos-abatement', 'description' => 'Abatement project sanitation', 'icon' => '⚠️'],
            ['name' => 'Lead Paint Removal', 'slug' => 'lead-paint-removal', 'description' => 'Lead paint abatement', 'icon' => '⚠️'],
            ['name' => 'Mold Remediation', 'slug' => 'mold-remediation', 'description' => 'Mold removal facilities', 'icon' => '🍄'],

            // === UNIT TYPES (20) ===
            ['name' => 'Standard Units', 'slug' => 'standard-units', 'description' => 'Basic portable toilet rentals', 'icon' => '🚻'],
            ['name' => 'Deluxe Flushable', 'slug' => 'deluxe-flushable', 'description' => 'Units with flush tanks', 'icon' => '🚿'],
            ['name' => 'ADA Accessible', 'slug' => 'ada-accessible', 'description' => 'Handicap accessible units', 'icon' => '♿'],
            ['name' => 'Wheelchair Accessible', 'slug' => 'wheelchair-accessible', 'description' => 'Wheelchair-friendly options', 'icon' => '♿'],
            ['name' => 'High-Rise Units', 'slug' => 'high-rise-units', 'description' => 'Jobsite specific units', 'icon' => '🏢'],
            ['name' => 'Lift-Friendly', 'slug' => 'lift-friendly', 'description' => 'Truck-mounted options', 'icon' => '🚛'],
            ['name' => 'Luxury Trailers', 'slug' => 'luxury-trailers', 'description' => 'Premium restroom trailers', 'icon' => '🚛'],
            ['name' => 'Restroom Trailers', 'slug' => 'restroom-trailers', 'description' => 'Mobile restroom trailers', 'icon' => '🚛'],
            ['name' => 'Shower Trailers', 'slug' => 'shower-trailers', 'description' => 'Portable shower units', 'icon' => '🚿'],
            ['name' => 'Combo Units', 'slug' => 'combo-units', 'description' => 'Restroom and shower combos', 'icon' => '🚿'],
            ['name' => 'Handwashing Stations', 'slug' => 'handwashing-stations', 'description' => 'Hand wash stations', 'icon' => '🧼'],
            ['name' => 'Sink Units', 'slug' => 'sink-units', 'description' => 'Portable sink rentals', 'icon' => '🚰'],
            ['name' => 'Grease Traps', 'slug' => 'grease-traps', 'description' => 'Grease trap services', 'icon' => '🛢️'],
            ['name' => 'Holding Tanks', 'slug' => 'holding-tanks', 'description' => 'Waste holding tanks', 'icon' => '🛢️'],
            ['name' => 'Portable Saunas', 'slug' => 'portable-saunas', 'description' => 'Mobile sauna rentals', 'icon' => '♨️'],
            ['name' => 'Cooling Units', 'slug' => 'cooling-units', 'description' => 'Portable AC units', 'icon' => '❄️'],
            ['name' => 'Heating Units', 'slug' => 'heating-units', 'description' => 'Portable heater rentals', 'icon' => '🔥'],
            ['name' => 'Climate Controlled', 'slug' => 'climate-controlled', 'description' => 'Temperature control options', 'icon' => '🌡️'],
            ['name' => 'Lighting Units', 'slug' => 'lighting-units', 'description' => 'Event lighting systems', 'icon' => '💡'],
            ['name' => 'Generator Rentals', 'slug' => 'generator-rentals', 'description' => 'Power generator options', 'icon' => '⚡'],
        ];

        foreach ($categories as $i => $category) {
            $baseSlug = $category['slug'];
            $slug = $baseSlug;
            $counter = 0;

            while (BlogCategory::where('slug', $slug)->where(function ($q) use ($domainId) {
                $q->where('domain_id', $domainId);
                if ($domainId === null) {
                    $q->orWhereNull('domain_id');
                }
            })->exists()) {
                $slug = $baseSlug.'-'.$counter;
                $counter++;
            }

            BlogCategory::create(
                array_merge($category, ['slug' => $slug, 'sort_order' => $i, 'domain_id' => $domainId])
            );
        }
    }
}
