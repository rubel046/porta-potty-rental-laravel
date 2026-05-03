# AGENTS.md

Guidance for AI agents working in this Laravel porta potty rental lead generation app.

## Tech Stack (verified)

- **PHP 8.3+**, **Laravel 13**, **MySQL** (dev/prod), **Redis** (cache/session/queue)
- **Frontend:** Tailwind CSS v3, Alpine.js, Vite, Blade
- **Code Style:** Laravel Pint (`./vendor/bin/pint`)
- **Key packages:** Spatie (media, sitemap, sluggable, schema-org), Artesos SEOTools, Maatwebsite Excel, SignalWire SDK, Laravel Breeze

## Commands

```bash
composer install && npm install      # Full setup
composer run setup                   # Full setup + key:generate + migrate + build
composer run dev                     # artisan serve + queue:listen + pail + vite (concurrently)
npm run dev                          # Vite dev server only
npm run build                        # Production build → public/build/

./vendor/bin/pint                    # Auto-fix style
./vendor/bin/pint --test             # Check only (CI mode)
```

No tests exist in this repository.

## Architecture

### Multi-Domain (Multi-Tenant)

The app serves multiple domains (e.g., pottydirect, waterdamage) from one codebase. `Domain` model owns branding, keywords, and service types. Cities/states are attached via pivot tables (`domain_cities`, `domain_states`) with a `status` column controlling visibility per domain.

- `app/Models/Domain.php` — central to understanding any page rendering
- `APP_DOMAIN` env var selects the active domain in `bootstrap/app.php`
- Controllers filter queries by `currentDomain()` helper from `app/helpers.php`

### Route Order (critical)

Routes in `routes/web.php` must stay in this order:
1. Public pages (`/`, `/locations`, `/services`, `/pricing`, state pages)
2. `/blog/*` routes
3. Sitemap routes (`/sitemap.xml`, `/robots.txt`)
4. Lead form POST `/lead`
5. SignalWire webhooks (`/webhook/signalwire/*`) — CSRF exempted in `bootstrap/app.php`
6. `/admin/*` auth-protected routes (Laravel Breeze)
7. `/admin/logs/*` — must stay before catch-all
8. `/{slug}` catch-all for city service pages — **MUST be last**

### Key Models

- `City` → `ServicePage`, `PhoneNumber`, `CallLog`, `Faq`, `Testimonial` (all scoped to domain via pivot)
- `State` → `City`
- `ServicePage` → belongs to `City`, has many `CallLog`
- `CallLog` → belongs to `City`, `ServicePage`, `PhoneNumber`, `Buyer`, `Domain`
- `Domain` ↔ `City` and `Domain` ↔ `State` via `BelongsToMany` with pivot `status`

### AI Services (registered as singletons in `AppServiceProvider`)

Content generation uses a multi-provider setup:
- `MultiAiService` — orchestrates providers, handles failover
- `AnthropicService`, `OpenAIService`, `GeminiService`, `GroqService` — individual providers
- `ContentGeneratorService` — generates SEO content, FAQs, testimonials
- `ImageService` — generates/optimizes images
- `GoogleIndexingService` — submits pages to Google Search Console

Configure API keys via `admin/api-keys` or `AiApiKey` model. `BLOG_AUTO_PUBLISH=false` by default (saves as draft).

### SignalWire Webhooks

Four endpoints handle the IVR flow:
- `POST /webhook/signalwire/incoming` — initial call
- `POST /webhook/signalwire/gather` — IVR key press
- `POST /webhook/signalwire/whisper` — pre-connect to buyer
- `POST /webhook/signalwire/status` — call completed

## Environment

Key `.env` variables (MySQL + Redis are defaults, not SQLite):
```
DB_CONNECTION=mysql
CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
SIGNALWIRE_PROJECT_ID= SIGNALWIRE_API_TOKEN= SIGNALWIRE_SPACE_URL=
APP_DOMAIN=pottydirect
```

For local dev without Redis, override in `.env`:
```
CACHE_STORE=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

`app/helpers.php` is autoloaded (see `composer.json`). Cache keys use patterns like `featured_cities`, `page_{slug}`, `faqs_{city_id}_{service_type}`.

## Conventions (Laravel standard, noted only where this repo differs)

- Route model binding: `getRouteKeyName()` returns `'slug'` in models
- Bengali comments exist in some files for domain-specific logic — preserve when editing those files
- Controllers use constructor property promotion for service injection
- Models define `$fillable`, `$casts` (with `decimal:7` for coordinates, `boolean`, `array` for JSON columns)
- `->where('slug', '[a-z0-9\-]+')` pattern used for slug routes to avoid catching assets

## Before Committing

1. `./vendor/bin/pint` — fix style
2. Verify manually or add tests (none exist yet)
