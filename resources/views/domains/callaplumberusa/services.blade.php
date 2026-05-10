@extends(\App\Models\Domain::getLayoutPathStatic())

@section('title')
    {{ $domain?->business_name ?? 'Plumbing Pro' }} | Plumbing Services | Emergency, Drain Cleaning & More
@endsection
@section('meta_description', 'Professional plumbing services nationwide. Emergency plumbing, drain cleaning, pipe repair, water heater services, sewer line repair & more. Licensed plumbers available 24/7. Call for a free quote!')
@section('canonical', route('services'))

@push('schema')
@php
$domain = \App\Models\Domain::current();
$url = url('/');
$phone = domain_phone_raw();
$businessName = $domain?->business_name ?? 'Plumbing Pro';

$serviceDefinitions = [
    'emergency' => [
        'icon' => 'phone',
        'description' => '24/7 emergency plumbing for burst pipes, gas leaks, sewage backups, and more. Our team responds fast to minimize damage to your property.',
        'features' => ['24/7 availability — we never close', 'Fast response times, typically under 1 hour', 'Fully licensed and insured technicians', 'Upfront pricing with no hidden fees', 'Emergency dispatch 365 days a year'],
        'best_for' => ['Burst Pipes', 'Gas Leaks', 'Sewage Backups', 'Overflowing Toilets', 'Water Damage'],
    ],
    'drain-cleaning' => [
        'icon' => 'lightning',
        'description' => 'Professional drain cleaning using hydro-jetting, motorized snaking, and video camera inspection to clear tough clogs and keep drains flowing freely.',
        'features' => ['Video camera drain inspection', 'Hydro-jetting for grease and debris', 'Motorized drain snaking', 'Pipe descaling and restoration', 'Preventative maintenance plans'],
        'best_for' => ['Slow Drains', 'Recurring Clogs', 'Bad Odors', 'Grease Buildup', 'Kitchen Drains'],
    ],
    'hydro-jetting' => [
        'icon' => 'lightning',
        'description' => 'High-pressure water jetting to clear stubborn blockages, grease buildup, and scale from pipes and drains. The most powerful cleaning method available for tough plumbing clogs.',
        'features' => ['4,000+ PSI water jetting', 'Grease and sludge removal', 'Tree root removal from pipes', 'Pipe scale and mineral deposit cleaning', 'No harsh chemicals needed'],
        'best_for' => ['Stubborn Clogs', 'Grease Buildup', 'Tree Root Intrusion', 'Frequent Blockages', 'Commercial Drains'],
    ],
    'pipe-repair' => [
        'icon' => 'wrench',
        'description' => 'Expert pipe repair and replacement for all piping materials. We fix leaks, corrosion, and damaged sections with minimal disruption to your property.',
        'features' => ['Accurate leak detection technology', 'Pipe patching and section replacement', 'Trenchless pipe repair options', 'Slab leak detection and repair', 'Frozen pipe thawing and repair'],
        'best_for' => ['Leaking Pipes', 'Corroded Pipes', 'Frozen Pipes', 'Slab Leaks', 'Water Damage'],
    ],
    'leak-detection' => [
        'icon' => 'wrench',
        'description' => 'Advanced leak detection using electronic listening devices, thermal imaging, and video inspection to find hidden water leaks without unnecessary demolition.',
        'features' => ['Electronic leak detection equipment', 'Thermal imaging cameras', 'Video pipe inspection', 'Non-invasive detection methods', 'Water meter analysis'],
        'best_for' => ['Hidden Water Leaks', 'High Water Bills', 'Wet Spots on Walls or Ceilings', 'Foundation Leaks', 'Mystery Puddles'],
    ],
    'slab-leak' => [
        'icon' => 'wrench',
        'description' => 'Specialized slab leak detection and repair for pipes running under concrete foundations. We locate and fix slab leaks with minimal disruption using advanced technology.',
        'features' => ['Electronic slab leak detection', 'Thermal imaging for slab leaks', 'Trenchless slab leak repair', 'Concrete cutting and restoration', 'Foundation-safe repair methods'],
        'best_for' => ['Hot Spots on Floor', 'Running Water Sounds', 'High Water Bills', 'Foundation Cracks', 'Wet Carpet or Flooring'],
    ],
    'water-heater' => [
        'icon' => 'fire',
        'description' => 'Installation, repair, and maintenance of tank and tankless water heaters. We service all brands and fuel types including gas, electric, and hybrid models.',
        'features' => ['New water heater installation', 'Tankless water heater services', 'Repair and troubleshooting', 'Annual maintenance and flushing', 'All fuel types — gas, electric, hybrid'],
        'best_for' => ['No Hot Water', 'Strange Noises', 'Water Heater Leaks', 'Old Unit Replacement', 'Energy Upgrades'],
    ],
    'tankless-water-heater' => [
        'icon' => 'fire',
        'description' => 'Expert installation, repair, and maintenance of tankless / on-demand water heaters. Get endless hot water with improved energy efficiency and a smaller footprint.',
        'features' => ['Tankless water heater installation', 'Descaling and flush maintenance', 'Error code diagnostics and repair', 'Gas and electric tankless service', 'Venting and gas line upgrades'],
        'best_for' => ['Endless Hot Water', 'Energy Savings', 'Limited Space', 'Old Tank Replacement', 'High Hot Water Demand'],
    ],
    'sewer-line' => [
        'icon' => 'map',
        'description' => 'Complete sewer line services from inspection to repair and replacement. We use trenchless technology to minimize digging and disruption to your landscape.',
        'features' => ['Video camera sewer inspection', 'Trenchless pipe lining and bursting', 'Sewer cleaning and root removal', 'Line locating and mapping', 'Full sewer line replacement'],
        'best_for' => ['Sewer Backups', 'Tree Root Intrusion', 'Broken Sewer Pipes', 'Sewer Odors', 'Slow Drains Throughout House'],
    ],
    'trenchless-sewer' => [
        'icon' => 'map',
        'description' => 'Trenchless sewer repair using pipe lining and pipe bursting methods. Repair or replace damaged sewer lines without digging up your yard, driveway, or landscaping.',
        'features' => ['Cured-in-place pipe (CIPP) lining', 'Pipe bursting replacement', 'No-dig repair technology', 'Same-day service available', '50-year warranty on lining'],
        'best_for' => ['Damaged Sewer Lines', 'Bellied Pipes', 'Cracked or Collapsed Pipes', 'Landscape Preservation', 'Driveway Crossings'],
    ],
    'sewer-inspection' => [
        'icon' => 'map',
        'description' => 'Video camera sewer inspection to diagnose blockages, cracks, and tree root intrusion. Get a clear picture of what is happening inside your sewer line before deciding on repairs.',
        'features' => ['HD video camera inspection', 'Line scope with location tracking', 'Full video report with photos', 'Pipe condition assessment', 'Pre-purchase inspection for home buyers'],
        'best_for' => ['Home Inspections', 'Recurring Sewer Issues', 'Pre-Purchase Evaluation', 'Pipe Condition Assessment', 'Root Intrusion Detection'],
    ],
    'toilet-repair' => [
        'icon' => 'toilet',
        'description' => 'Professional toilet repair, replacement, and installation. We fix leaks, clogs, running toilets, and install new high-efficiency fixtures.',
        'features' => ['Toilet leak and seal repair', 'Clog removal with professional tools', 'Flush mechanism and fill valve repair', 'New toilet installation', 'High-efficiency and comfort height options'],
        'best_for' => ['Running Toilets', 'Constant Clogs', 'Toilet Leaks', 'Bathroom Renovation', 'Water Savings'],
    ],
    'faucet-repair' => [
        'icon' => 'wrench',
        'description' => 'Repair and installation of faucets, sinks, shower heads, and plumbing fixtures. Restore your fixtures to peak performance and efficiency.',
        'features' => ['Dripping faucet repair', 'Shower head and valve replacement', 'Kitchen and bathroom sink installation', 'Cartridge and stem replacement', 'Water pressure optimization'],
        'best_for' => ['Dripping Faucets', 'Low Water Pressure', 'Leaky Shower Heads', 'Kitchen Upgrades', 'Bathroom Remodels'],
    ],
    'fixture-installation' => [
        'icon' => 'wrench',
        'description' => 'Professional installation of sinks, faucets, toilets, shower heads, bathtubs, and all plumbing fixtures. Upgrade your home with new, modern fixtures installed by licensed plumbers.',
        'features' => ['Full fixture installation service', 'Sink and faucet installation', 'Bathtub and shower installation', 'Toilet and bidet installation', 'Water-efficient fixture upgrades'],
        'best_for' => ['Bathroom Remodels', 'Kitchen Upgrades', 'New Fixtures', 'Home Renovations', 'Water Efficiency'],
    ],
    'garbage-disposal' => [
        'icon' => 'wrench',
        'description' => 'Repair and installation of garbage disposals. We fix jams, leaks, and replace old units with new high-performance models. Service all major brands.',
        'features' => ['Garbage disposal repair', 'New disposal installation', 'Jam removal and reset', 'Leak repair and seal replacement', 'All major brands serviced'],
        'best_for' => ['Jam or Lockup', 'Leaking Disposal', 'Strange Noises', 'Old Disposal Replacement', 'Kitchen Upgrade'],
    ],
    'gas-line' => [
        'icon' => 'fire',
        'description' => 'Licensed gas line installation, repair, and inspection for residential and commercial properties. Our certified technicians prioritize safety above all.',
        'features' => ['Gas leak detection and repair', 'New gas line installation', 'Gas appliance hookup and conversion', 'Line pressure testing and inspection', 'Permit and code compliance'],
        'best_for' => ['Gas Appliance Installation', 'Gas Leak Concerns', 'Outdoor Kitchens', 'New Construction', 'Line Upgrades'],
    ],
    'water-main' => [
        'icon' => 'wrench',
        'description' => 'Water main repair and replacement services. We fix leaks, breaks, and corrosion in the main water line that supplies your entire property.',
        'features' => ['Water main leak detection', 'Sectional repair and replacement', 'Full water main replacement', 'Trenchless water main options', 'Municipal permit handling'],
        'best_for' => ['Low Water Pressure', 'Water Main Leaks', 'Discolored Water', 'Property-Wide Water Issues', 'Old Galvanized Pipes'],
    ],
    'water-line' => [
        'icon' => 'wrench',
        'description' => 'Water line installation and repair for residential and commercial properties. We install new water supply lines and repair damaged ones with minimal disruption.',
        'features' => ['New water line installation', 'Water line leak repair', 'Trenchless water line replacement', 'Frozen water line thawing', 'Pressure regulation and testing'],
        'best_for' => ['New Construction', 'Line Leaks', 'Low Pressure', 'Line Freeze', 'Property Additions'],
    ],
    'sump-pump' => [
        'icon' => 'shield-check',
        'description' => 'Installation, repair, and maintenance of sump pumps and battery backup systems to protect your basement from flooding.',
        'features' => ['Sump pump installation', 'Battery backup system setup', 'Pump repair and replacement', 'Pit cleaning and maintenance', 'Flood prevention consultation'],
        'best_for' => ['Basement Flooding', 'Old Pump Replacement', 'Frequent Pump Cycling', 'No Backup Power', 'New Construction Basements'],
    ],
    'backflow-testing' => [
        'icon' => 'shield-check',
        'description' => 'Backflow prevention device testing, installation, and repair to protect your drinking water from contamination. Certified backflow testing for code compliance.',
        'features' => ['State-certified backflow testing', 'Backflow preventer installation', 'Repair and rebuilding services', 'Annual testing and reporting', 'Code compliance documentation'],
        'best_for' => ['Code Compliance', 'Annual Testing Requirements', 'Commercial Properties', 'Irrigation Systems', 'New Backflow Installation'],
    ],
    'water-filtration' => [
        'icon' => 'check-circle',
        'description' => 'Whole-house water filtration, water softeners, and reverse osmosis systems for cleaner, better-tasting water throughout your home.',
        'features' => ['Whole-house filtration systems', 'Under-sink reverse osmosis', 'Water softener installation', 'Filter replacement and maintenance', 'Water quality testing'],
        'best_for' => ['Hard Water', 'Bad Taste or Odor', 'Sediment in Water', 'Chlorine Sensitivity', 'Better Drinking Water'],
    ],
    'water-softener' => [
        'icon' => 'check-circle',
        'description' => 'Water softener installation, repair, and maintenance. Remove hard water minerals to protect your pipes, appliances, and fixtures from scale buildup.',
        'features' => ['Water softener installation', 'Softener repair and troubleshooting', 'Salt delivery and refill service', 'Dual-tank and high-capacity systems', 'Salt-free water conditioning options'],
        'best_for' => ['Hard Water Stains', 'Dry Skin and Hair', 'Scale Buildup on Fixtures', 'Appliance Longevity', 'Spotty Dishes'],
    ],
    'bathroom-remodel' => [
        'icon' => 'wrench',
        'description' => 'Full bathroom plumbing and remodeling services. From simple fixture upgrades to complete bathroom renovations, our licensed plumbers handle all the plumbing work.',
        'features' => ['Bathroom plumbing rough-in', 'Shower and tub installation', 'Vanity and sink plumbing', 'Toilet relocation and installation', 'Drain and vent piping'],
        'best_for' => ['Bathroom Renovations', 'Fixture Upgrades', 'Walk-in Shower Installation', 'Accessibility Modifications', 'Adding a Bathroom'],
    ],
    'kitchen-plumbing' => [
        'icon' => 'wrench',
        'description' => 'Complete kitchen plumbing services including sink installation, faucet repair, garbage disposal, dishwasher hookup, and ice maker water lines.',
        'features' => ['Kitchen sink installation', 'Faucet replacement and repair', 'Dishwasher installation and hookup', 'Garbage disposal services', 'Ice maker water line installation'],
        'best_for' => ['Kitchen Remodels', 'New Sink Installation', 'Dishwasher Installation', 'Multiple Fixture Setup', 'Leak Repairs'],
    ],
    'commercial-plumbing' => [
        'icon' => 'wrench',
        'description' => 'Commercial plumbing services for businesses, restaurants, office buildings, and industrial facilities. We handle complex commercial systems with minimal downtime.',
        'features' => ['Commercial pipe installation', 'Grease trap cleaning and repair', 'Restroom fixture installation', 'Backflow prevention compliance', 'Preventative maintenance programs'],
        'best_for' => ['Restaurants', 'Office Buildings', 'Industrial Facilities', 'Apartment Complexes', 'Retail Spaces'],
    ],
    'new-construction' => [
        'icon' => 'wrench',
        'description' => 'New construction plumbing for residential and commercial projects. We handle rough-in plumbing, fixture installation, and final connections for new builds and additions.',
        'features' => ['Underground rough-in plumbing', 'Above-ground rough-in', 'Trim-out and fixture installation', 'Gas line installation', 'Water heater installation'],
        'best_for' => ['New Home Construction', 'Home Additions', 'Commercial Builds', 'Multi-Unit Projects', 'Custom Homes'],
    ],
    'septic' => [
        'icon' => 'map',
        'description' => 'Septic tank services including installation, pumping, repair, and inspection. We maintain and repair all types of septic systems for residential and commercial properties.',
        'features' => ['Septic tank installation', 'Septic system repair', 'Drain field diagnosis and repair', 'Septic tank pumping', 'System inspection for home buyers'],
        'best_for' => ['Septic System Failure', 'Regular Pumping', 'Drain Field Issues', 'Home Purchase Inspection', 'Odor Problems'],
    ],
    'well-pump' => [
        'icon' => 'shield-check',
        'description' => 'Well pump installation, repair, and replacement for residential water wells. We service submersible, jet, and deep-well pumps to keep your water flowing.',
        'features' => ['Well pump repair and replacement', 'Submersible pump service', 'Well pressure tank installation', 'Well system diagnostics', 'Water quality testing'],
        'best_for' => ['No Water from Well', 'Low Water Pressure', 'Well Pump Cycling', 'Bad Tasting Well Water', 'Old Well System'],
    ],
    'pipe-thawing' => [
        'icon' => 'fire',
        'description' => 'Professional frozen pipe thawing and repair services. We safely thaw frozen pipes using professional equipment to prevent bursting and water damage.',
        'features' => ['Electronic pipe thawing equipment', 'Frozen pipe detection and location', 'Pipe insulation and protection', 'Emergency burst pipe repair', 'Winterization consultation'],
        'best_for' => ['Frozen Pipes', 'No Water Flow in Cold Weather', 'Exposed Pipe Protection', 'Winter Emergency', 'Unheated Property Protection'],
    ],
    'radiant-heating' => [
        'icon' => 'fire',
        'description' => 'Radiant heating system installation, repair, and maintenance. Enjoy efficient, comfortable heat through in-floor hydronic heating systems for bathrooms, basements, and entire homes.',
        'features' => ['Hydronic radiant floor installation', 'Boiler and manifold setup', 'Zone control system installation', 'System troubleshooting and repair', 'Retrofit and new construction'],
        'best_for' => ['In-Floor Heating', 'Bathroom Floor Heat', 'Basement Heating', 'Energy Efficient Heating', 'New Home Construction'],
    ],
];

$allServiceTypes = $domain?->getServiceTypes() ?? [];
$serviceTypes = [];
foreach ($allServiceTypes as $key) {
    $def = $serviceDefinitions[$key] ?? [
        'icon' => 'wrench',
        'description' => 'Professional ' . ($domain?->getServiceTypeLabel($key) ?? str_replace('-', ' ', $key)) . ' services for residential and commercial customers.',
        'features' => ['Licensed and insured technicians', 'Upfront pricing with no hidden fees', 'Same-day service availability', 'Quality workmanship guaranteed', 'Customer satisfaction focused'],
        'best_for' => ['Residential Properties', 'Commercial Properties', 'Emergency Service', 'Scheduled Maintenance', 'New Installations'],
    ];
    $label = $domain?->getServiceTypeLabel($key) ?? ucfirst(str_replace('-', ' ', $key));
    $shortKey = str_replace(['-repair', '-installation', '-testing', '-cleaning', '-heater'], '', $key);
    $serviceTypes[] = [
        'key' => $key,
        'name' => $label,
        'short_name' => str_replace(['Installation & ', 'Repair & ', 'Detection & ', 'Prevention & '], '', $label),
        'icon' => $def['icon'],
        'description' => $def['description'],
        'features' => $def['features'],
        'best_for' => $def['best_for'],
    ];
}

$serviceSchema = [
    "@context" => "https://schema.org",
    "@type" => "Plumber",
    "@id" => $url . "#business",
    "name" => $businessName,
    "description" => "Professional plumbing services nationwide. Emergency plumbing, drain cleaning, pipe repair, water heater services, sewer line repair, and more.",
    "url" => $url,
    "telephone" => $phone,
    "priceRange" => "$$",
    "areaServed" => [
        "@type" => "Country",
        "name" => "United States"
    ],
    "openingHoursSpecification" => [
        [
            "@type" => "OpeningHoursSpecification",
            "dayOfWeek" => ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"],
            "opens" => "00:00",
            "closes" => "23:59"
        ]
    ],
    "hasOfferCatalog" => [
        "@type" => "OfferCatalog",
        "name" => "Plumbing Services",
        "itemListElement" => collect($serviceTypes)->map(fn($st) => [
            "@type" => "Offer",
            "itemOffered" => ["@type" => "Service", "name" => $st['name']]
        ])->toArray(),
    ],
    "aggregateRating" => ($reviewCount ?? 0) > 0 ? [
        "@type" => "AggregateRating",
        "ratingValue" => (string) ($reviewRating ?? 4.9),
        "reviewCount" => (string) ($reviewCount ?? 0),
        "bestRating" => "5"
    ] : null
];
$serviceSchema = array_filter($serviceSchema);

$faqSchema = [
    "@context" => "https://schema.org",
    "@type" => "FAQPage",
    "mainEntity" => [
        [
            "@type" => "Question",
            "name" => "What plumbing services do you offer?",
            "acceptedAnswer" => [
                "@type" => "Answer",
                "text" => "We offer a complete range of professional plumbing services including " . implode(', ', array_map(fn($st) => $st['short_name'], array_slice($serviceTypes, 0, 20))) . ", and more. From emergency repairs to new construction installations, we handle all your plumbing needs."
            ]
        ],
        [
            "@type" => "Question",
            "name" => "How quickly can you respond to a plumbing emergency?",
            "acceptedAnswer" => [
                "@type" => "Answer",
                "text" => "We provide 24/7 emergency plumbing services with fast response times, typically under one hour. Our team is on call 365 days a year, including nights, weekends, and holidays for burst pipes, sewer backups, and gas leaks."
            ]
        ],
        [
            "@type" => "Question",
            "name" => "Are your plumbers licensed and insured?",
            "acceptedAnswer" => [
                "@type" => "Answer",
                "text" => "Yes, all of our plumbers are fully licensed, bonded, and insured with $2 million in liability coverage. We background-check every technician and prioritize safety and quality workmanship on every job."
            ]
        ],
        [
            "@type" => "Question",
            "name" => "Do you offer upfront pricing before starting work?",
            "acceptedAnswer" => [
                "@type" => "Answer",
                "text" => "Yes, we provide upfront, transparent pricing before any work begins. There are no hidden fees or surprise charges. You will know the exact cost before we start, and the price we quote is the price you pay."
            ]
        ],
        [
            "@type" => "Question",
            "name" => "Do you offer same-day plumbing service?",
            "acceptedAnswer" => [
                "@type" => "Answer",
                "text" => "Yes! We offer same-day service for most plumbing repairs and installations. Call before noon and we'll have a plumber at your door today. Emergency services are available 24/7 with immediate dispatch."
            ]
        ],
        [
            "@type" => "Question",
            "name" => "What areas do you serve?",
            "acceptedAnswer" => [
                "@type" => "Answer",
                "text" => "We provide plumbing services across the United States, serving hundreds of cities in all 50 states. Check our locations page to find a plumber in your city, or call us and we'll connect you with a local professional."
            ]
        ]
    ]
];
$serviceListGraph = array_map(fn($st) => [
    "@type" => "Service",
    "name" => $st['name'],
    "description" => $st['description'],
    "provider" => ["@id" => $url . "#business"],
    "areaServed" => ["@type" => "Country", "name" => "United States"],
], $serviceTypes);
$serviceListSchema = ["@context" => "https://schema.org", "@graph" => $serviceListGraph];
@endphp
<script type="application/ld+json">{!! json_encode($serviceSchema, JSON_UNESCAPED_SLASHES) !!}</script>
<script type="application/ld+json">{!! json_encode($faqSchema, JSON_UNESCAPED_SLASHES) !!}</script>
<script type="application/ld+json">{!! json_encode($serviceListSchema, JSON_UNESCAPED_SLASHES) !!}</script>
@endpush

@section('content')

    {{-- Trust Banner --}}
    <div class="bg-slate-900 text-white py-3">
        <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row items-center justify-center gap-3 md:gap-5 text-center md:text-left text-xs sm:text-sm">
            @if(($reviewCount ?? 0) > 0)
                <div class="flex items-center gap-2">
                    <x-icon name="star" class="w-4 h-4 text-blue-400" />
                    <span class="font-semibold">{{ number_format($reviewRating ?? 4.9, 1) }}/5 ({{ $reviewCount }}+ Reviews)</span>
                </div>
                <span class="hidden md:inline text-slate-600" aria-hidden="true">·</span>
            @endif
            <span class="inline-flex items-center gap-1.5"><x-icon name="shield-check" class="w-4 h-4 text-blue-400" />Licensed &amp; Insured</span>
            <span class="hidden md:inline text-slate-600" aria-hidden="true">·</span>
            <span class="inline-flex items-center gap-1.5"><x-icon name="clock" class="w-4 h-4 text-blue-400" />24/7 Emergency Service</span>
            <span class="hidden md:inline text-slate-600" aria-hidden="true">·</span>
            <span class="inline-flex items-center gap-1.5"><x-icon name="currency-dollar" class="w-4 h-4 text-blue-400" />Upfront Pricing</span>
        </div>
    </div>

    {{-- Hero --}}
    <section class="relative py-16 md:py-20 overflow-hidden bg-slate-900">
        <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900"></div>

        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 text-center">
            <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-extrabold text-white mb-5 tracking-tight text-balance">
                Plumbing Services — Emergency, Drain Cleaning, Water Heater & More
            </h1>
            <p class="text-lg sm:text-xl text-slate-300 max-w-2xl mx-auto mb-8">
                From <strong class="text-white">emergency plumbing repairs</strong> and <strong class="text-white">drain cleaning</strong> to <strong class="text-white">water heater installation, leak detection, and sewer line repair</strong> — professional plumbing solutions for every need, 24/7.
            </p>
            <div class="flex flex-wrap justify-center gap-3 text-sm text-slate-300">
                <span class="inline-flex items-center gap-1.5 bg-white/10 backdrop-blur px-4 py-2 rounded-full"><x-icon name="clock" class="w-4 h-4 text-blue-400" />24/7 Emergency Service</span>
                <span class="inline-flex items-center gap-1.5 bg-white/10 backdrop-blur px-4 py-2 rounded-full"><x-icon name="wrench" class="w-4 h-4 text-blue-400" />Licensed Plumbers</span>
                <span class="inline-flex items-center gap-1.5 bg-white/10 backdrop-blur px-4 py-2 rounded-full"><x-icon name="shield-check" class="w-4 h-4 text-blue-400" />Upfront Pricing</span>
            </div>
        </div>
    </section>

    {{-- Quick Navigation --}}
    <section class="py-5 px-4 bg-slate-50 border-b border-slate-200">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-wrap justify-center gap-x-4 gap-y-2 text-xs sm:text-sm">
                @foreach($serviceTypes as $i => $st)
                    <a href="#{{ $st['key'] }}" class="text-slate-600 hover:text-orange-600 font-medium transition whitespace-nowrap">{{ $st['short_name'] }}</a>
                    @if($i < count($serviceTypes) - 1)
                        <span class="text-slate-300 hidden sm:inline" aria-hidden="true">·</span>
                    @endif
                @endforeach
            </div>
        </div>
    </section>

    {{-- Introduction Content --}}
    <section class="py-12 px-4">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-2xl md:text-3xl font-bold text-slate-800 mb-4">Complete {{ $domain?->primary_service ?? 'Plumbing Services' }}</h2>
            <p class="text-lg text-slate-600 leading-relaxed mb-6">
                Whether you need <strong>emergency plumbing repair</strong>, <strong>drain cleaning</strong>,
                <strong>water heater installation</strong>, or <strong>sewer line repair</strong>, we offer the widest range of
                professional plumbing services across the USA. Our team includes emergency plumbers, drain specialists, pipe repair experts,
                and gas line technicians.
            </p>
            <p class="text-slate-600 leading-relaxed">
                Every service includes <strong>upfront pricing</strong>, <strong>licensed technicians</strong>, and <strong>satisfaction guaranteed</strong>.
                Call now to speak with a plumbing professional who will help you choose the right solution for your home or business.
            </p>
        </div>
    </section>

    {{-- Properties We Serve --}}
    <section class="py-12 md:py-16 px-4 bg-gradient-to-r from-blue-600 to-blue-700">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Properties We Serve</h2>
            <p class="text-lg text-blue-100 mb-8">Professional plumbing solutions for every type of property</p>

            <div class="bg-white/10 backdrop-blur rounded-2xl p-8">
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-left">
                    @php
                        $properties = [
                            'Residential Homes',
                            'Commercial Buildings',
                            'Construction Sites',
                            'Emergency Services',
                            'Bathroom Remodels',
                            'Water Heater Installations',
                            'New Construction',
                            'Multi-Family Properties',
                            'Industrial Facilities',
                            'Restaurants & Kitchens',
                            'Office Buildings',
                            'Apartment Complexes',
                        ];
                    @endphp

                    @foreach($properties as $property)
                        <div class="flex items-center gap-2 text-white font-medium">
                            <x-icon name="check" class="w-4 h-4 text-orange-400 flex-shrink-0" />
                            <span>{{ $property }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <p class="text-blue-200 mt-6">Plus many more! Call us to discuss your specific plumbing needs.</p>
        </div>
    </section>

    {{-- Service Types --}}
    <section class="py-12 md:py-16 px-4">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-slate-800 mb-4">Our {{ $domain?->primary_service ?? 'Plumbing Services' }}</h2>
                <p class="text-lg text-slate-600">Choose from our complete range of professional plumbing solutions</p>
            </div>

            <div class="grid md:grid-cols-2 gap-8">
                @foreach($serviceTypes as $type)
                    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden
                                hover:shadow-xl hover:border-blue-300 transition-all duration-300 group" id="{{ $type['key'] }}">
                        {{-- Header --}}
                        <div class="bg-gradient-to-r from-slate-800 to-slate-900 p-6 text-white">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-4 mb-3">
                                        <div class="w-14 h-14 rounded-xl bg-blue-500/15 text-blue-300 flex items-center justify-center group-hover:bg-blue-500 group-hover:text-white transition-colors duration-300">
                                            <x-icon name="{{ $type['icon'] }}" class="w-7 h-7" />
                                        </div>
                                        <div>
                                            <h2 class="text-xl font-bold">{{ $type['name'] }}</h2>
                                        </div>
                                    </div>
                                    <p class="text-slate-300 text-sm leading-relaxed">
                                        {{ $type['description'] }}
                                    </p>
                                </div>
                                <div class="flex-shrink-0">
                                    <a href="tel:{{ domain_phone_raw() }}"
                                       data-tracking-label="services-card-inline"
                                       class="inline-flex items-center gap-2 bg-orange-500 hover:bg-orange-400 text-white font-semibold py-2 px-4 rounded-lg transition-all whitespace-nowrap min-h-[44px]">
                                        <x-icon name="phone" class="w-4 h-4" />
                                        Get Quote
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- Content --}}
                        <div class="p-6">
                            {{-- Features --}}
                            <h3 class="text-sm font-semibold text-slate-400 uppercase tracking-wider mb-4">What's Included</h3>
                            <ul class="space-y-3 mb-6">
                                @foreach($type['features'] as $feature)
                                    <li class="flex items-center gap-3 text-slate-600">
                                        <span class="w-5 h-5 bg-orange-100 rounded-full flex items-center justify-center flex-shrink-0">
                                            <span class="text-orange-600 text-xs">✓</span>
                                        </span>
                                        {{ $feature }}
                                    </li>
                                @endforeach
                            </ul>

                            {{-- Best For --}}
                            <h3 class="text-sm font-semibold text-slate-400 uppercase tracking-wider mb-3">Best For</h3>
                            <div class="flex flex-wrap gap-2 mb-6">
                                @foreach($type['best_for'] as $use)
                                    <span class="bg-slate-100 text-slate-600 px-3 py-1 rounded-full text-sm">
                                        {{ $use }}
                                    </span>
                                @endforeach
                            </div>

                            {{-- CTA --}}
                            <a href="tel:{{ domain_phone_raw() }}"
                               data-tracking-label="services-card-cta"
                               class="flex items-center justify-center gap-2 w-full bg-orange-500 hover:bg-orange-400 text-white font-bold py-3 px-6 rounded-full transition-all shadow-lg shadow-orange-500/25 hover:scale-[1.02] min-h-[44px]">
                                <x-icon name="phone" class="w-4 h-4" />
                                Get Quote for {{ $type['short_name'] }}
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Service Categories Summary --}}
    <section class="py-12 md:py-16 px-4 bg-slate-50">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-10">
                <h2 class="text-3xl font-bold text-slate-800 mb-4">All Plumbing Services</h2>
                <p class="text-slate-500">Browse our complete list of {{ count($serviceTypes) }} professional plumbing services</p>
            </div>

            <div class="flex flex-wrap justify-center gap-2 sm:gap-3">
                @foreach($serviceTypes as $st)
                    <a href="#{{ $st['key'] }}"
                       class="bg-white hover:bg-blue-50 border border-slate-200 hover:border-blue-300 px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg sm:rounded-xl text-xs sm:text-sm font-medium text-slate-700 hover:text-blue-700 transition-all shadow-sm hover:shadow-md min-h-[44px] inline-flex items-center gap-1.5">
                        {{ $st['short_name'] }}
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- How to Choose --}}
    <section class="py-12 md:py-16 px-4">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-10">
                <h2 class="text-3xl font-bold text-slate-800 mb-4">How to Choose the Right Plumbing Service</h2>
                <p class="text-slate-500">Not sure which plumbing service is right for your needs? Here's a quick guide</p>
            </div>

            <div class="space-y-4">
                <div class="bg-white border border-slate-200 rounded-xl p-6 flex items-start gap-5 hover:shadow-md transition">
                    <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center flex-shrink-0">
                        <x-icon name="phone" class="w-7 h-7" />
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 mb-2">Need immediate help for a plumbing emergency?</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">
                            <strong>Emergency plumbing services</strong> are available 24/7 for burst pipes, gas leaks, sewage backups, and any situation requiring immediate attention. Our team responds within the hour.
                        </p>
                    </div>
                </div>

                <div class="bg-white border border-slate-200 rounded-xl p-6 flex items-start gap-5 hover:shadow-md transition">
                    <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center flex-shrink-0">
                        <x-icon name="lightning" class="w-7 h-7" />
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 mb-2">Dealing with slow drains or recurring clogs?</h3>
                            <p class="text-slate-500 text-sm leading-relaxed">
                                <strong>Drain cleaning services</strong> use video inspection and hydro-jetting to thoroughly clear blockages caused by grease, hair, soap scum, and tree roots. Perfect for kitchen, bathroom, and main line drains.
                            </p>
                    </div>
                </div>

                <div class="bg-white border border-slate-200 rounded-xl p-6 flex items-start gap-5 hover:shadow-md transition">
                    <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center flex-shrink-0">
                        <x-icon name="wrench" class="w-7 h-7" />
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 mb-2">Leaking pipes or water damage concerns?</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">
                            <strong>Pipe repair and replacement</strong> services address leaks, corrosion, slab leaks, and frozen pipes. We use advanced leak detection to find hidden issues without unnecessary demolition.
                        </p>
                    </div>
                </div>

                <div class="bg-white border border-slate-200 rounded-xl p-6 flex items-start gap-5 hover:shadow-md transition">
                    <div class="w-14 h-14 bg-orange-50 text-orange-600 rounded-xl flex items-center justify-center flex-shrink-0">
                        <x-icon name="fire" class="w-7 h-7" />
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 mb-2">No hot water or strange water heater noises?</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">
                            <strong>Water heater services</strong> include repair, maintenance, and installation of both tank and tankless models. We diagnose and fix issues quickly so you can get back to hot showers.
                        </p>
                    </div>
                </div>

                <div class="bg-white border border-slate-200 rounded-xl p-6 flex items-start gap-5 hover:shadow-md transition">
                    <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center flex-shrink-0">
                        <x-icon name="map" class="w-7 h-7" />
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 mb-2">Sewer backups or foul odors in your yard?</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">
                            <strong>Sewer line services</strong> use camera inspection and trenchless repair to diagnose and fix issues with minimal digging. We handle tree root intrusion, broken pipes, and full replacements.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- SEO Content Section --}}
    <section class="py-12 md:py-16 px-4 bg-slate-50">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-2xl p-8 md:p-12 shadow-sm border border-slate-200">
                <h2 class="text-2xl font-bold text-slate-800 mb-6">Why Choose Our {{ $domain?->primary_service ?? 'Plumbing Services' }}?</h2>
                
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="flex gap-3">
                        <x-icon name="clock" class="w-6 h-6 text-blue-500 flex-shrink-0 mt-0.5" />
                        <div>
                            <h3 class="font-bold text-slate-800 mb-1">24/7 Emergency Response</h3>
                            <p class="text-slate-600 text-sm">Available around the clock for plumbing emergencies. We respond fast to minimize water damage and restore your peace of mind.</p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <x-icon name="shield-check" class="w-6 h-6 text-blue-500 flex-shrink-0 mt-0.5" />
                        <div>
                            <h3 class="font-bold text-slate-800 mb-1">Licensed &amp; Insured</h3>
                            <p class="text-slate-600 text-sm">All plumbers are fully licensed, bonded, and insured for your protection and peace of mind.</p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <x-icon name="currency-dollar" class="w-6 h-6 text-blue-500 flex-shrink-0 mt-0.5" />
                        <div>
                            <h3 class="font-bold text-slate-800 mb-1">Upfront Pricing</h3>
                            <p class="text-slate-600 text-sm">Transparent, upfront pricing with no hidden fees. You will know the cost before any work begins.</p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <x-icon name="wrench" class="w-6 h-6 text-blue-500 flex-shrink-0 mt-0.5" />
                        <div>
                            <h3 class="font-bold text-slate-800 mb-1">Experienced Technicians</h3>
                            <p class="text-slate-600 text-sm">Years of experience across all types of plumbing systems — residential, commercial, and industrial.</p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <x-icon name="check-circle" class="w-6 h-6 text-blue-500 flex-shrink-0 mt-0.5" />
                        <div>
                            <h3 class="font-bold text-slate-800 mb-1">Satisfaction Guaranteed</h3>
                            <p class="text-slate-600 text-sm">We stand behind every job with a satisfaction guarantee. If you are not happy, we will make it right.</p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <x-icon name="map-pin" class="w-6 h-6 text-blue-500 flex-shrink-0 mt-0.5" />
                        <div>
                            <h3 class="font-bold text-slate-800 mb-1">Nationwide Service</h3>
                            <p class="text-slate-600 text-sm">Serving all 50 states with local plumbers and fast response times.</p>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-slate-200">
                    <p class="text-slate-600 leading-relaxed">
                        As one of the leading plumbing service providers in the USA, we understand that every home and business has unique plumbing needs.
                        Whether you need <strong>emergency plumbing repair</strong>, <strong>drain cleaning</strong>,
                        <strong>water heater installation</strong>, or <strong>sewer line repair</strong>, our team is ready to help you find the perfect solution.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Get Custom Quote --}}
    <section class="py-14 md:py-20 px-4 bg-white border-t border-slate-100">
        <div class="max-w-3xl mx-auto text-center">
            <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 mb-3">Get your custom quote</h2>
            <p class="text-slate-600 mb-7">Every plumbing job is unique. Call for pricing based on your specific needs.</p>
            <a href="tel:{{ domain_phone_raw() }}"
               data-tracking-label="services-custom-quote"
               class="inline-flex items-center gap-3 bg-orange-500 hover:bg-orange-400 text-white font-bold text-lg py-4 px-8 rounded-full shadow-xl shadow-orange-500/30 hover:scale-105 transition-all min-h-[44px]">
                <x-icon name="phone" class="w-5 h-5" />
                {{ domain_phone_display() }}
            </a>
            <p class="text-slate-500 text-sm mt-4">No obligation · Upfront pricing · 24/7 service</p>
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-16 md:py-20 px-4 bg-slate-900 text-white text-center relative overflow-hidden">
        <div class="relative max-w-3xl mx-auto">
            <h2 class="text-3xl md:text-4xl font-bold mb-4 text-balance">Burst pipe? Clogged drain? No hot water? Call Us</h2>
            <p class="text-lg text-slate-400 mb-3 max-w-xl mx-auto">
                Call for a <strong class="text-white">free quote</strong> — we'll help you get your plumbing back in working order today. Same-day service available.
            </p>
            <p class="text-slate-400 mb-8 text-sm">
                Serving homes, businesses, and properties across the USA. 24/7 emergency service.
            </p>
            <a href="tel:{{ domain_phone_raw() }}"
               data-tracking-label="services-final"
               class="inline-flex items-center gap-3 bg-orange-500 hover:bg-orange-400
                      text-white text-2xl md:text-3xl font-bold py-5 px-10
                      rounded-full shadow-2xl shadow-orange-500/40 ring-4 ring-orange-400/30
                      transition-all hover:scale-105 min-h-[44px]">
                <x-icon name="phone" class="w-7 h-7 md:w-8 md:h-8" />
                {{ domain_phone_display() }}
            </a>
            <p class="mt-6 text-slate-400 text-sm">No obligation · Upfront pricing · 24/7 emergency service · Free estimates</p>
        </div>
    </section>

    {{-- Quick Links --}}
    <section class="py-8 px-4 bg-slate-50">
        <div class="max-w-4xl mx-auto flex flex-wrap justify-center gap-6 text-sm">
            <a href="{{ route('home') }}" class="text-orange-600 hover:text-orange-700 font-medium">← Back to Home</a>
            <span class="text-slate-300">|</span>
            <a href="{{ route('pricing') }}" class="text-orange-600 hover:text-orange-700 font-medium">View Pricing</a>
            <span class="text-slate-300">|</span>
            <a href="{{ route('locations') }}" class="text-orange-600 hover:text-orange-700 font-medium">Find Your City</a>
            <span class="text-slate-300">|</span>
            <a href="{{ route('blog.index') }}" class="text-orange-600 hover:text-orange-700 font-medium">Blog</a>
        </div>
    </section>

{{-- Mobile Sticky CTA --}}
<div class="fixed bottom-4 left-4 right-4 md:hidden z-50">
    <a href="tel:{{ domain_phone_raw() }}"
       class="flex items-center justify-center gap-3 bg-gradient-to-r from-orange-500 to-orange-600 text-white font-bold text-lg py-4 px-6 rounded-2xl shadow-2xl shadow-orange-500/40 ring-4 ring-orange-400/30 transition-all hover:scale-[1.02] active:scale-[0.98]">
        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
        </svg>
        <span>Call Now — Free Quote</span>
    </a>
</div>
<div class="h-20 md:hidden"></div>

@endsection
