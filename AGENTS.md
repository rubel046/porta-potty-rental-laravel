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
- **All blog posts now target a city**: Pillar posts (previously no city) now pick a random linked city and use city context in their AI prompt. Admin forms auto-select or require a city. Seed migration updated. Every blog post always has `city_id` set.
- **Executive SEO Audit** completed: identified thin content on 5 landing pages

## Critical Context (continued)
- **All blog posts (pillar + cluster) now target a city**: `GenerateDailyBlogPost::generatePillarPost()` picks a random linked city. Pillar AI prompt updated to include city context while keeping national scope. Admin blog create/generate forms auto-select first linked city if none chosen. `BlogPostController::getDomainCities()` replaces `City::active()` to use `domain_cities.status` pivot — avoids dependency on `cities.is_active` column (which may not be set).
- **City queries for blog posts use `domain_cities` pivot**: `getDomainCities()` fetches cities via `whereHas('domainCities', status: true)` instead of `City::active()`. This matches how `GenerateDailyBlogPost::getRandomCity()` works., duplicate city pages, no GBP management, no link building, no online conversion beyond phone, AI blog lacks E-E-A-T
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
- **GBP API Integration** completed: `GoogleBusinessProfileService` with OAuth token refresh, post creation, review fetch/reply — `GoogleBusinessProfileService.php`, `GmbPost.php` model, `gmb_posts` migration, `gmb:post-blog` and `gmb:sync-reviews` commands, OAuth connect/callback/exchange-token flow, 3 OAuth routes, daily schedules at 9 AM and 10 AM, admin views with stats and post history. All 11 GMB routes registered.
- **Top 10 city pages enhanced**: `config/city_context.php` with hand-curated local data (neighborhoods, highways, venues, industries, weather, permits, construction) for Houston, Dallas, LA, NYC, Miami, Chicago, Phoenix, Atlanta, Denver, Seattle. `ContentGeneratorService::getCityContext()` injects `{city_context}` into AI prompts. `city:regenerate-top` command created. Both rental + service prompt variants updated to require 4+ specific local references.
- **Wikipedia city context enrichment** for 30,000+ cities: `WikipediaService.php` fetches summaries + Economy/Climate/Geography/Transportation sections (free, no API key). `city:enrich-context --limit=100` command batch-populates City model fields. `getCityContext()` falls back to DB fields (`city_description`, `climate_info`, `local_events`, `construction_info`) when config has no data. Daily schedule at 2 AM. 31,875 cities detected missing context.
- **Neighborhood pages** created: full system with migrations, models, seed command (`neighborhoods:seed`), content generation (`neighborhoods:generate-content`), route (`/neighborhoods/{slug}`), Blade view, sitemap integration, admin CRUD, daily schedules at 3 AM (seed) and 4 AM (content gen). 60 neighborhoods seeded across Chicago, NYC, LA.
- **Header text fixed**: "We open at 8AM — call for emergency service" (removed "leave a message or")
- **Services page expanded**: 16 main service types (was 12) + 13 add-ons (was 8), added via competitor gap analysis.
- **Keyword seeder overhauled**: removed 6 low-volume keywords, fixed duplicate geo-template, added 15+ high-value keywords.
- **Blog cluster migration fixed**: added domain fallback insert so `migrate:fresh --seed` succeeds regardless of seeding order.
- **Blog featured images fixed**: double slash in URL, added `featuredImageUrl` accessor with file-existence check, generated 10 placeholder JPGs, updated 16 Blade templates.
- **Blog published_at filter removed**: `published()` scope no longer filters by `published_at <= now()` — `is_published = true` is the sole gate. Fixed 404s on all 10 seed posts.
- **Blog auto-publish enabled**: removed `BLOG_AUTO_PUBLISH` env var. Both pillar and cluster posts now publish immediately (`is_published = true`, `published_at = now()`).
- **Blog-index layout fixed**: restored missing `<div>` wrapper that broke entire page rendering.
- **Admin blog edit form**: added featured_image input with image preview.
- **Sitemap-cities.xml 500 error fixed**: `addImage()` in v8.1.0 expects `string $url`, not `Image` object. Fixed argument order, removed unused `Image` import.
- **Sitemap timeouts (cities + full) fixed**: added eager loading (`->with('city.state')`) to eliminate N+1 queries. Changed from `chunk()` to `lazy()->take(50000/30000)` with URL cap to stream results.
- **GMB scheduler errors fixed**: `gmb:sync-reviews` and `gmb:post-blog` return `SUCCESS` (not `FAILURE`) when GMB not configured.
- **Instant Price Calculator fixed**: moved `@json($priceRanges)` from `x-data` attribute into separate `<script>` tag (embedded `"` broke HTML boundaries). Expanded dropdown from 5 to 15 unit types.
- **Page quality scores — persistent DB storage**: `page_quality_scores` migration + model + `scoreAndPersist()` method on `PageQualityService`. `scoreAllForDomain()` now writes batch upserts instead of returning in-memory array.
- **`quality:score-all` command**: batch-scoring Artisan command with `--domain`, `--force` flags. Progress bar, error handling, skips already-scored unless forced.
- **`ServicePage::qualityScore()` relationship**: HasOne to `PageQualityScore` for `whereDoesntHave('qualityScore')` query.
- **Quality scores controller + view**: rewritten to query `page_quality_scores` DB table (instant load) instead of live-scoring. Uses `with('servicePage.city.state')` eager loading. Paginated 25 per page.
- **Daily schedule added**: `quality:score-all` at 2:30 AM (after city enrichment).
- **Bug fix**: `PageQualityService::score()` referenced non-existent `$page->seo_description` — changed to `$page->meta_description`.

- **Keyword research table created**: `keywords` migration + `Keyword` model + `KeywordSeeder` with 150+ keywords seeded from CONTENT_STRATEGY.md data (volume, competition, CPC, tier, mapped to service_type)
- **Active keyword targeting in AI prompts**: `{target_keywords}` variable injected into city service page prompts with explicit instructions for AI to weave low-competition, high-volume keywords into H1, H2s, meta tags, and body content
- **Blog keyword targeting**: Blog posts now receive `$targetFocusKeyword` from keyword research table (mapped via `mapCategoryToServiceType()`) instead of AI self-selecting focus keywords. Pillar and cluster prompts updated to target specific keywords.
- **Geo-placeholders resolved**: City-specific keyword variants automatically generated by replacing `[city]`, `[state]`, `[county]` in keyword templates at generation time
- **10 most important services prioritized**: `service_types` reordered in `DomainConfigSeeder.php` with top 10 (construction, wedding, event, emergency, standard, deluxe, ada, luxury, party, residential) first; `getTopServiceTypes()` returns first 10

### In Progress
- Neighborhood content quality needs manual review (some parsed entries are not real neighborhoods, e.g. "Community areas in Chicago", "Interactive Chicago Neighborhood Map")
- Seed remaining top cities (Houston, Phoenix, Philadelphia, San Antonio, San Diego, Dallas, San Jose) for richer neighborhood coverage
- Run `neighborhoods:generate-content` again after more neighborhoods are seeded

### Next
- Run `php artisan quality:score-all` on production (post-`git pull` + `php artisan migrate`)
- Verify `/admin/cities/quality-scores` loads instantly from DB with 690k+ records

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
- **Quality scores in separate table**: `page_quality_scores` avoids ALTER on 690k-row `service_pages`; keeps bulky `details` JSON out of normal queries; scoring refreshed independently via `updateOrCreate`

## Relevant Files
- `config/city_context.php`: Hand-curated local data for top 10 cities (neighborhoods, highways, venues, industries, weather, permits, construction)
- `config/services.php`: Added `gmb` config block (client_id, client_secret, scopes, redirect_uri)
- `routes/web.php`: 3 GMB OAuth routes + `/neighborhoods/{slug}` route (before state/catch-all) + admin neighborhood routes
- `routes/console.php`: Daily schedules for GMB post (9 AM), GMB review sync (10 AM), city enrichment (2 AM, 100/day), neighborhood seed (3 AM), neighborhood content gen (4 AM), quality scoring (2:30 AM)
- `app/Services/GoogleBusinessProfileService.php`: Full GBP API client — OAuth, post creation, review fetch/reply, auto-reply, blog posting
- `app/Services/WikipediaService.php`: Fetches city summaries + sections (Economy, Climate, Geography, Transportation, Sports, Tourism) from free Wikipedia API
- `app/Services/ContentGeneratorService.php`: `getCityContext()` added — checks curated config first, falls back to DB fields; prompts require 4+ local references; both rental and service variants updated
- `app/Services/PageQualityService.php`: `score()` core metric + `scoreAndPersist()` batch upsert into `page_quality_scores` table
- `app/Http/Controllers/Admin/GmbAccountController.php`: OAuth connect/callback/exchange-token, real `sync()` using `GoogleBusinessProfileService`
- `app/Http/Controllers/Admin/NeighborhoodController.php`: Full CRUD (index, show, generate, bulkGenerate, toggle)
- `app/Http/Controllers/Admin/CityController.php`: `qualityScores()` reads from `page_quality_scores` DB table with eager loading
- `app/Http/Controllers/PageController.php`: Added `neighborhoodPage()` method + Neighborhood/NeighborhoodServicePage imports
- `app/Http/Controllers/SitemapController.php`: `cities()` — eager load + lazy/take(50000); `addServicePages()` — lazy/take(30000); `addImage()` fixed for v8.1.0 API
- `app/Models/GmbAccount.php`: Model with `is_active`, `auto_post`, `auto_reply_reviews`, encrypted token accessors, `total_posts_count`, `unread_reviews_count`
- `app/Models/GmbPost.php`: Tracks each GBP post (type, external_id, blog_post_id, status, response_data)
- `app/Models/Neighborhood.php`: `belongsTo` City, `hasMany` NeighborhoodServicePage, `getFullNameAttribute()`, `getUrlAttribute()`
- `app/Models/NeighborhoodServicePage.php`: `belongsTo` Neighborhood + Domain, published/type scopes, slug-based URL
- `app/Models/ServicePage.php`: `qualityScore()` HasOne relationship to `PageQualityScore`
- `app/Models/PageQualityScore.php`: score, grade, word_count, faq_count, testimonial_count, details (JSON), scored_at
- `app/Jobs/GenerateNeighborhoodContentJob.php`: Generates per-type AI service pages for each neighborhood (400-800 words, meta tags, proper slug)
- `app/Console/Commands/SeedNeighborhoods.php`: `neighborhoods:seed --state= --city= --limit=50 --dry-run` — fetches real neighborhood names from Wikipedia via parse API
- `app/Console/Commands/GenerateNeighborhoodContent.php`: `neighborhoods:generate-content --limit=20 --force --type= --domain=` — sync dispatching with progress bar and sitemap cache busting
- `app/Console/Commands/QualityScoreAll.php`: `quality:score-all --domain= --force` — batch scoring with progress bar
- `database/migrations/2026_05_16_193645_create_page_quality_scores_table.php`: quality scores table with unique `service_page_id`
- `database/migrations/2026_05_18_000001_create_neighborhoods_table.php`: neighborhoods + neighborhood_service_pages tables
- `resources/views/admin/cities/quality-scores.blade.php`: Paginated QS view, reads from DB with expandable detail rows
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
- Quality scores stored in `page_quality_scores` table with unique `service_page_id` constraint — `updateOrCreate` ensures one score per page
- `quality:score-all` uses `chunk(100)` + `whereDoesntHave('qualityScore')` to skip already-scored pages; use `--force` to re-score
- **Keyword research table** (`keywords`): Stores volume, competition, CPC, tier (1-3), and service_type mapping. Seeded from CONTENT_STRATEGY.md and domain secondary keywords with geo-placeholders.
- **Active keyword injection**: `{target_keywords}` replaces lowest-competition, highest-volume keywords per service type. Geo-placeholders (`[city]`, `[state]`, `[county]`) resolved at generation time via `resolveGeoPlaceholders()`.
- **Blog keyword targeting**: Category slug → service type mapping in `mapCategoryToServiceType()`. Blog posts receive research-driven `focus_keyword` instead of AI self-selection.
- **Top 10 service types**: `getTopServiceTypes(int $limit = 10)` returns first N from reordered `service_types` array. Per-city generation capped to 10 pages.
