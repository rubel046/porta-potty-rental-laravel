## Goal
- Fully analyze and optimize PottyDirect website for SEO dominance, higher Google rankings, and increased inbound phone calls in the USA portable toilet rental industry.

## Constraints & Preferences
- Phone-only lead generation (no lead forms, no booking widgets)
- Prioritize low-competition, high-commercial-intent keywords first
- Focus exclusively on PottyDirect, no generic SEO advice
- Actionable, step-by-step output with tables, examples, and competitor gap analysis
- 90-day plan with quick wins prioritized

## Progress
### Done
- **Executive SEO Audit** completed: identified thin content on 5 landing pages, duplicate city pages, no GBP management, no link building, no online conversion beyond phone, AI blog lacks E-E-A-T
- **Competitor Analysis** completed: analyzed United Site Services, PortableToiletsChamp, FusionSite, FindPortaPotty.com, PortaPottyRentalGuide.com. Found gaps in neighborhood-level pages, video content, real reviews, interactive tools, seasonal content, Spanish language
- **Keyword Research** completed: 3-tier priority list with volumes and CPC
- **5 thin landing pages expanded** to 1000+ words each with FAQ, Why Choose, Related Resources, Expert Tips
- **4 new SEO landing pages** created (types-guide, cleaning-process, sports-event, municipal): 2000+ words each, FAQPage/HowTo schema
- **Schema @id conflicts fixed**: removed `#business` from 4 pages, scoped ServicePage to `#service-{id}`
- **Homepage internal links overhauled**: 10/12 occasion cards link to dedicated landing pages, Related Services row added
- **Related Resources sections** added to ALL 11 landing pages with 6 internal links each
- **Instant Price Calculator** built: Alpine.js widget on city service pages
- **GBP management dashboard** built: CRUD, OAuth, toggle, sync, admin views, encrypted token storage
- **10 blog posts** seeded (5 construction, 5 event planning) staggered over 28 days
- **City Page Quality Scoring** admin tool built: 10-metric A-F grading with search and expandable details
- **GBP API Integration** completed: `GoogleBusinessProfileService` with OAuth token refresh, post creation, review fetch/reply — `GoogleBusinessProfileService.php`, `GmbPost.php` model, `gmb_posts` migration, `gmb:post-blog` and `gmb:sync-reviews` commands, OAuth connect/callback/exchange-token flow, 3 OAuth routes, daily schedules at 9 AM and 10 AM, admin views with stats and post history. All 11 GMB routes registered
- **Top 10 city pages enhanced**: `config/city_context.php` with hand-curated local data (neighborhoods, highways, venues, industries, weather, permits, construction) for Houston, Dallas, LA, NYC, Miami, Chicago, Phoenix, Atlanta, Denver, Seattle. `ContentGeneratorService::getCityContext()` injects `{city_context}` into AI prompts. `city:regenerate-top` command created. Both rental + service prompt variants updated to require 4+ specific local references
- **Wikipedia city context enrichment** for 30,000+ cities: `WikipediaService.php` fetches summaries + Economy/Climate/Geography/Transportation sections (free, no API key). `city:enrich-context --limit=100` command batch-populates City model fields. `getCityContext()` falls back to DB fields (`city_description`, `climate_info`, `local_events`, `construction_info`) when config has no data. Daily schedule at 2 AM. 31,875 cities detected missing context
- **Neighborhood pages migration** created and ran: `neighborhoods` + `neighborhood_service_pages` tables
- **Neighborhood models** created: `Neighborhood.php`, `NeighborhoodServicePage.php`
- **Neighborhood seed command** created: `neighborhoods:seed` — fetches real neighborhood lists from Wikipedia for top cities
- **Neighborhood route** registered: `GET /neighborhoods/{slug}` before state/catch-all routes
- **Neighborhood controller method** added: `PageController::neighborhoodPage()` with view, schema, breadcrumbs, related pages
- **Neighborhood Blade view** created: `resources/views/domains/pottydirect/neighborhood.blade.php` with breadcrumbs, hero CTA, content, sidebar, related services
- **Neighborhood admin CRUD** built: `NeighborhoodController` (index, show, generate, bulkGenerate, toggle); `admin/neighborhoods/` routes registered; admin nav link added
- **Sitemap update**: `addNeighborhoodPages()` method integrated into `SitemapController::index()`; cache invalidation includes neighborhood key
- **Neighborhood content generation job** created: `GenerateNeighborhoodContentJob` — generates per-type service pages for each neighborhood (400-800 words of AI content per type)
- **Neighborhood generate command** created: `neighborhoods:generate-content --limit=20 --force` — sync command that dispatches jobs and busts sitemap cache
- **Schedules registered**: `neighborhoods:seed --limit=50` at 3 AM, `neighborhoods:generate-content --limit=20` at 4 AM daily
- **Header text fixed**: "We open at 8AM — call for emergency service" (removed "leave a message or")
- **Services page expanded**: 16 main service types (was 12) + 13 add-ons (was 8), added via competitor gap analysis (Portable Urinal Stations, Hand Wash Trailers, Temporary Fencing & Barriers, High-Rise Construction Toilets, Baby Changing Stations, Generator Rentals, Restroom Signage, Privacy Screens, Deodorizing Service)
- **Keyword seeder overhauled**: removed 6 low-volume keywords, fixed duplicate geo-template, added 15+ high-value keywords (cost-based, service-specific, geo-templates for new services)
- **Blog cluster migration fixed**: added domain fallback insert so `migrate:fresh --seed` succeeds regardless of seeding order
- **56 migrations all ran clean** via `php artisan migrate:fresh --seed`
- **Neighborhood seed fixes**: removed `is_active` filter (0 active cities), added `redirects=1` to Wikipedia API calls (neighborhood list pages are redirects), added `LENGTH(zip_codes)` ordering proxy for population (all 31,875 cities seeded with population=0), added 1.1s rate-limit delay between Wikipedia API calls to avoid 429s
- **Neighborhood seed data**: 60 neighborhoods seeded across Chicago, NYC, and Los Angeles (20 each, limited to 20 per city)
- **Neighborhood content generated**: `neighborhoods:generate-content --limit=20` completed — 20 neighborhood service pages generated with AI content, sitemap cache busted

### In Progress
- Neighborhood content quality needs manual review (some parsed entries are not real neighborhoods, e.g. "Community areas in Chicago", "Interactive Chicago Neighborhood Map")
- Seed remaining top cities (Houston, Phoenix, Philadelphia, San Antonio, San Diego, Dallas, San Jose) for richer neighborhood coverage
- Run `neighborhoods:generate-content` again after more neighborhoods are seeded

## Key Decisions
- **Phone-only conversion**: All CTAs remain phone-call focused with `tel:` links and `data-tracking-label` attributes
- **Schema @id deduplication**: Layout serves as single source of truth for `#business` entity; individual pages removed conflicting `@id`
- **Instant Price Calculator uses Alpine.js**: No extra dependencies, already loaded site-wide
- **Wikipedia for city context**: Free API, no key required, works for any US city. 200ms rate limiting between calls
- **Two-tier city context**: Top 10 use hand-curated config data (`config/city_context.php`); remaining 30k+ use Wikipedia-populated DB fields
- **Neighborhoods as separate system**: Own table (`neighborhoods`), own service pages table (`neighborhood_service_pages`), own route prefix (`/neighborhoods/{slug}`). Avoids complexity of modifying the existing city/service_pages system
- **Neighborhood URL structure**: `/neighborhoods/{slug}` prefix — clean, SEO-friendly, no routing conflicts with city catch-all
- **Neighborhood route placed before city catch-all**: Prevents `/neighborhoods/...` from being caught by `/{slug}` wildcard
- **Neighborhood content generation uses sync dispatch**: `GenerateNeighborhoodContentJob` dispatched via `dispatchSync()` in CLI command for predictable batch processing; uses `ShouldQueue` for future async support

## Relevant Files
- `config/city_context.php`: Hand-curated local data for top 10 cities (neighborhoods, highways, venues, industries, weather, permits, construction)
- `config/services.php`: Added `gmb` config block (client_id, client_secret, scopes, redirect_uri)
- `routes/web.php`: 3 GMB OAuth routes + `/neighborhoods/{slug}` route (before state/catch-all) + admin neighborhood routes
- `routes/console.php`: Daily schedules for GMB post (9 AM), GMB review sync (10 AM), city enrichment (2 AM, 100/day), neighborhood seed (3 AM), neighborhood content gen (4 AM)
- `app/Services/GoogleBusinessProfileService.php`: Full GBP API client — OAuth, post creation, review fetch/reply, auto-reply, blog posting
- `app/Services/WikipediaService.php`: Fetches city summaries + sections (Economy, Climate, Geography, Transportation, Sports, Tourism) from free Wikipedia API
- `app/Services/ContentGeneratorService.php`: `getCityContext()` added — checks curated config first, falls back to DB fields; prompts require 4+ local references; both rental and service variants updated
- `app/Http/Controllers/Admin/GmbAccountController.php`: OAuth connect/callback/exchange-token, real `sync()` using `GoogleBusinessProfileService`
- `app/Http/Controllers/Admin/NeighborhoodController.php`: Full CRUD (index, show, generate, bulkGenerate, toggle)
- `app/Http/Controllers/PageController.php`: Added `neighborhoodPage()` method + Neighborhood/NeighborhoodServicePage imports
- `app/Http/Controllers/SitemapController.php`: Added `addNeighborhoodPages()` method; neighborhood cache invalidation
- `app/Models/GmbAccount.php`: Model with `is_active`, `auto_post`, `auto_reply_reviews`, encrypted token accessors, `total_posts_count`, `unread_reviews_count`
- `app/Models/GmbPost.php`: Tracks each GBP post (type, external_id, blog_post_id, status, response_data)
- `app/Models/Neighborhood.php`: `belongsTo` City, `hasMany` NeighborhoodServicePage, `getFullNameAttribute()`, `getUrlAttribute()`
- `app/Models/NeighborhoodServicePage.php`: `belongsTo` Neighborhood + Domain, published/type scopes, slug-based URL
- `app/Jobs/GenerateNeighborhoodContentJob.php`: Generates per-type AI service pages for each neighborhood (400-800 words, meta tags, proper slug)
- `app/Console/Commands/SeedNeighborhoods.php`: `neighborhoods:seed --state= --city= --limit=50 --dry-run` — fetches real neighborhood names from Wikipedia via parse API
- `app/Console/Commands/GenerateNeighborhoodContent.php`: `neighborhoods:generate-content --limit=20 --force --type= --domain=` — sync dispatching with progress bar and sitemap cache busting
- `database/migrations/2026_05_18_000001_create_neighborhoods_table.php`: neighborhoods + neighborhood_service_pages tables
- `resources/views/admin/neighborhoods/index.blade.php`: Paginated table with status badges, generate buttons, bulk generate action
- `resources/views/admin/neighborhoods/show.blade.php`: Detail view with info sidebar, service pages table, generate/activate actions
- `resources/views/domains/pottydirect/neighborhood.blade.php`: Full neighborhood page with breadcrumbs, hero CTA, content, related services sidebar, schema.org Neighborhood markup
- `resources/views/admin/layout.blade.php`: Neighborhoods nav link added

## Critical Context
- Site uses Laravel with multi-tenant domain architecture (DomainViewHelper resolves views per domain)
- Alpine.js is available site-wide for interactive widgets
- Phone numbers use `domain_phone_raw()` and `domain_phone_display()` helpers
- Service pricing from `config/service_pricing.php`
- All CTAs use `data-tracking-label` attributes for call tracking
- City service pages are AI-generated and stored in `service_pages` table linked to cities and domains
- Routes file has careful ordering: static pages → neighborhood routes → state pages → city catch-all
- `{city_context}` is injected into AI prompts for both rental and service domain variants; AI required to use 4+ specific local references
- Wikipedia enrichment runs 100 cities/day at 2 AM — ~11 months to cover all 31,875
- `neighborhoods:seed --limit=50` processes top 50 cities by population; extend `--limit` for broader coverage
- Neighborhood slug format: `{neighborhood-slug}-{city-slug}` (e.g. `brooklyn-new-york-ny`)
- Neighborhood content generation uses AI job that accepts `$types` array for batch per-type page creation
- `getServiceTypes()` may return empty array — fallback to `['general']` in controllers/commands
- Neighborhood routes use `name('admin.neighborhoods.*')` prefix (resolves to `admin.neighborhoods.index`, etc.)
