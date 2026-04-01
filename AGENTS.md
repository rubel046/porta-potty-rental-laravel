# AGENTS.md

This document provides guidance for AI agents operating in this repository.

## Project Overview

This is a **Laravel 13** porta potty rental lead generation platform. Key features:
- Public SEO pages for city/state landing pages with auto-generated content
- Call tracking via SignalWire VoIP with IVR
- Admin dashboard for managing cities, buyers, calls, invoices
- Blog system for SEO content
- Content generation service for city/service pages
- Excel export via Maatwebsite Excel

## Tech Stack

- **Backend:** PHP 8.3+, Laravel 13
- **Frontend:** Tailwind CSS v4, Vite, Blade templates
- **Database:** SQLite (dev), MySQL-compatible
- **Testing:** PHPUnit 12
- **Code Style:** Laravel Pint
- **Packages:** Spatie (media, sitemap, sluggable, schema-org), Artesos SEOTools, Maatwebsite Excel, SignalWire

---

## 1. Build / Lint / Test Commands

### Installation
```bash
composer install
npm install
```

### Frontend Build
```bash
npm run dev      # Vite dev server with hot reload
npm run build    # Production build (outputs to public/build/)
```

### Full Setup
```bash
composer run setup   # composer install + key:generate + migrate + npm install + build
```

### Dev Server (all processes)
```bash
composer run dev    # Runs concurrently: artisan serve, queue:listen, pail (logs), vite
```

### Linting / Code Style
```bash
./vendor/bin/pint                          # Auto-fix all style issues
./vendor/bin/pint --test                  # Check without fixing (CI mode)
./vendor/bin/pint app/Http/Controllers/    # Lint specific path
```

### Testing
```bash
composer run test                          # Clear config + run tests (runs full suite)
php artisan test                           # Run all tests
php artisan test --filter=ExampleTest      # Run single test file
php artisan test --filter=test_homepage     # Run single test by method name
./vendor/bin/phpunit                       # Run PHPUnit directly
./vendor/bin/phpunit --filter=testBasic    # Single test with phpunit
./vendor/bin/phpunit tests/Unit/           # Unit tests only
./vendor/bin/phpunit tests/Feature/        # Feature tests only
```

---

## 2. Code Style Guidelines

### General
- **Indent:** 4 spaces (enforced by `.editorconfig`)
- **Line endings:** LF
- **Charset:** UTF-8
- **Trailing whitespace:** Trimmed
- **Final newline:** Required
- **Always run `./vendor/bin/pint` before committing PHP code**

### PHP Conventions

#### File Structure
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class ModelName extends Model
{
    protected $fillable = [];
    protected $casts = [];
    protected $hidden = [];

    // Constants
    const TYPE_FOO = 'foo';

    // Relationships
    public function relation(): BelongsTo
    {
        return $this->belongsTo(RelatedModel::class);
    }

    // Scopes
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    // Accessors
    public function getFullNameAttribute(): string
    {
        return "{$this->first} {$this->last}";
    }

    // Methods
    public function doSomething(): void
    {
        // ...
    }
}
```

#### Naming Conventions
- **Classes:** `PascalCase` (e.g., `CityController`, `SignalWireService`)
- **Methods/functions:** `camelCase` (e.g., `generateContent`, `findBestBuyer`)
- **Variables:** `camelCase` (e.g., `$cityName`, `$servicePage`)
- **Constants:** `SCREAMING_SNAKE_CASE` (e.g., `TYPE_GENERAL`, `DB_CONNECTION`)
- **Database columns/attributes:** `snake_case` (e.g., `is_active`, `city_id`)
- **Routes:** `kebab-case` for URL slugs, `dot.notation` for route names
- **Route names:** `resource.action` or `group.action` (e.g., `admin.cities.store`, `sw.incoming`)

#### Controller Conventions
- Use constructor property promotion for injected dependencies
- Group imports alphabetically
- Return `Response` type hints for webhook methods
- Use `$request->input()` for accessing request data with defaults

```php
class SignalWireWebhookController extends Controller
{
    public function __construct(
        protected SignalWireService $signalWire
    ) {}

    public function incoming(Request $request): Response
    {
        return response($xml, 200)->header('Content-Type', 'text/xml');
    }
}
```

#### Model Conventions
- Define `$fillable`, `$casts`, `$hidden`, `$with` arrays explicitly
- Use type-casted `$casts` (e.g., `'is_active' => 'boolean'`, `'decimal:7'`)
- Always define relationships with return type hints
- Use query scopes with `scope` prefix (`scopeActive`, `scopePublished`)
- Use accessors with `getAttributeNameAttribute` pattern
- Use constants for type/service-type values (e.g., `const TYPE_GENERAL = 'general'`)

#### Service Conventions
- Place business logic in `app/Services/`
- Services should be injected via constructor or resolved from the container
- Keep methods focused — one responsibility per method
- Return type hints on all public methods

#### Error Handling
- Use `try/catch` in service methods and wrap API calls
- Always log errors: `Log::error("Context: {$message}", ['context' => $data])`
- Return `null` or empty arrays from services on failure (don't throw unless critical)
- Use named parameters for webhook/IVR methods with many arguments

#### Imports / Use Statements
- Group by: native PHP, Laravel framework, packages, app classes
- Alphabetical within groups
- Blank line between groups
```php
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Spatie\SchemaOrg\Schema;
```

#### Comments
- **Do NOT add comments unless clarifying non-obvious logic**
- Use Bengali comments sparingly for project-specific domain logic (existing pattern)
- Document complex regex, business rules, or non-obvious calculations

#### Blade Templates
- Use `{{ $variable }}` for safe output
- Use `{!! $html !!}` only for pre-sanitized content
- Keep logic out of templates; use view composers or controller data
- Use Laravel's `@json()` for embedding data in JS

#### Route Conventions
- Public routes first, webhooks second, admin routes third
- Catch-all slug routes MUST be last
- Use explicit controller/action array syntax (not invokable)
- Route model binding: define `getRouteKeyName()` returning `'slug'` in models

#### Database / Migrations
- Use `$table->string()`, `$table->text()`, `$table->boolean()`, etc.
- Use `$table->json()` for array-like data (nearby_cities, zip_codes)
- For MySQL migrations, always specify length on string columns
- Foreign keys with `onDelete`/`onUpdate` cascade when appropriate
- Run `php artisan migrate` after pulling new migrations

#### Testing
- Test class: `tests/Unit/ExampleTest.php` or `tests/Feature/ExampleTest.php`
- Method names: `test_description_of_behavior()`
- Use `RefreshDatabase` trait for tests that need the database
- Feature tests use `$this->get()`, `$this->post()`, etc.
- Test file names should match class names

---

## 3. Application Architecture

### Directory Structure
```
app/
  Http/Controllers/
    Admin/           # Resource controllers (Dashboard, City, Buyer, etc.)
    PageController   # Public page controllers
    SignalWireWebhookController  # Webhook handlers
  Models/           # Eloquent models with relationships & scopes
  Services/         # Business logic (ContentGenerator, SignalWire)
  Providers/        # Service providers

database/
  migrations/        # Ordered chronologically
  factories/        # Model factories for testing
  seeders/          # Database seeders

tests/
  Feature/          # Integration/HTTP tests
  Unit/             # Unit tests for services/classes
```

### Route Groups
- `/` — Public pages (home, locations, state pages)
- `/blog` — Blog listing/show
- `/webhook/signalwire/*` — Webhook endpoints (CSRF disabled)
- `/admin/*` — Auth-protected admin panel
- `/{slug}` — Catch-all for city service pages (MUST be last)

### Key Models & Relationships
- `City` → has many `ServicePage`, `PhoneNumber`, `CallLog`, `Faq`, `Testimonial`, `BlogPost`
- `State` → has many `City`
- `ServicePage` → belongs to `City`, has many `CallLog`
- `PhoneNumber` → belongs to `City`, has many `CallLog`
- `Buyer` → has many `CallLog`
- `CallLog` → belongs to `City`, `ServicePage`, `PhoneNumber`, `Buyer`

### Important Services
- `ContentGeneratorService` — Generates SEO content, FAQs, testimonials for cities
- `SignalWireService` — SignalWire API integration, IVR response generation, call routing

### Cache Keys
- `featured_cities` — Homepage featured cities (TTL: 3600s)
- `active_states` — State listings (TTL: 3600s)
- `page_{slug}` — Service page data (TTL: 1800s)
- `faqs_{city_id}_{service_type}` — FAQ data (TTL: 3600s)
- `nearby_{city_id}` — Nearby city pages (TTL: 3600s)

---

## 4. Environment & Configuration

### Key ENV Variables
```
APP_ENV=local|production
APP_DEBUG=true|false
APP_URL=http://localhost
DB_CONNECTION=sqlite|mysql
SIGNALWIRE_PROJECT_ID=
SIGNALWIRE_API_TOKEN=
SIGNALWIRE_SPACE_URL=
```

### Database
- Dev uses SQLite at `database/database.sqlite`
- Tests use `:memory:` SQLite (configured in `phpunit.xml`)
- No schema.php file (migrations only)

### Testing Setup
- `DB_CONNECTION=sqlite`, `DB_DATABASE=:memory:`
- `CACHE_STORE=array`, `SESSION_DRIVER=array`
- `QUEUE_CONNECTION=sync`
- Telescope, Pulse, Nightwatch disabled in tests

---

## 5. Workflow Guidelines

### Before Committing
1. Run `./vendor/bin/pint` to fix style
2. Run tests: `composer run test`
3. Check for any obvious issues

### Creating New Features
1. Create migration for new database changes
2. Create/update Model with relationships, scopes, accessors
3. Create Service class for business logic
4. Create Controller with proper method signatures
5. Add routes in `routes/web.php`
6. Create Blade view if needed
7. Add test coverage
8. Run Pint and tests

### Adding New Models
- Follow existing model patterns: `$fillable`, `$casts`, relationships, scopes
- Add to database seeder if needed
- Register routes in `routes/web.php` if it has admin CRUD
