# Laravel quality uplift — Sepasiran

## CACHE_STORE assumptions

| Store | Production suitability | Tags (`Cache::tags`) | Notes |
|-------|------------------------|----------------------|-------|
| `database` (`.env.example` default) | OK | **No** | App uses key-based `Cache::remember` / `Cache::forget` via `App\Support\AppCache`. Requires `cache` table (migration present). |
| `file` | OK for single node | **No** | Same key-forget path as database. |
| `array` | Tests only (`phpunit.xml`) | **No** | In-memory; fine for PHPUnit. |
| `redis` / `memcached` | Preferred at scale | **Yes** | `AppCache` automatically uses tags (`settings`, `menus`, `home`) when `CACHE_STORE` is redis/memcached. |

**Do not** call `Cache::tags()` directly unless `AppCache::supportsTags()` is true.

Cached payloads today:

- `app.settings.first` — shared to all views as `$settings`
- `app.menus.roots` — shared as `$navMenus`
- `app.home.payload` — home sliders/services/blogs/stats

Invalidation: Setting/Menu write controllers call `AppCache::forgetSettings()` / `forgetMenus()`; Slider/Service/Blog writes call `forgetHome()`.

OTP codes also use the default cache store (`otp:code:{phone}`), TTL 5 minutes.

## Rate limiting

Config: `config/rate_limiting.php` (env overrides `RATE_LIMIT_*`).

| Limiter | Middleware | Applied to |
|---------|------------|------------|
| `login` | `throttle:login` | `POST /login`, `POST new-login`, password reset store |
| `otp` | `throttle:otp` | `POST /otp/send`, `POST /otp/verify`, forgot-password email |
| `payment` | `throttle:payment` | cart show, wallet, order-summary, summary |
| `admin-mutations` | `throttle.mutations:admin-mutations` | Admin group POST/PUT/PATCH/DELETE only |

Login also keeps FormRequest `RateLimiter` hits in `LoginRequest` (aligned with config max attempts).

## Pagination

- Fixed unbounded `DogController@index` (`dd` + `get()` → `paginate(20)`).
- Wallet lists (admin/clinic/coach) now `paginate(20)` with aggregate sums on separate queries.
- Store products listing uses `paginate(12)`.
- User nearby clinics uses `paginate(12)`.
- Admin `users` Blade routes corrected to `admin.users.*`.

## Helpers rename

- Canonical: `App\Helpers\GeneralHelpers`
- Deprecated alias: `App\Helpers\GenralHelpers` (extends GeneralHelpers)
- Composer `autoload.files` entry removed; PSR-4 loads both classes

## Tests

`phpunit.xml` uses SQLite `:memory:` + `CACHE_STORE=array`. Base `TestCase` calls `withoutVite()`.

```bash
cd C:\Users\pSaeed\sepasiran
php artisan test
```

## Change summary (files)

**Added**

- `config/rate_limiting.php`
- `app/Support/AppCache.php`
- `app/Helpers/GeneralHelpers.php`
- `app/Http/Controllers/Auth/OtpController.php`
- `app/Http/Middleware/ThrottleMutations.php`
- `tests/Feature/AuthThrottleTest.php`
- `tests/Feature/CacheInvalidationTest.php`
- `tests/Feature/PaginationAndListingsTest.php`

**Updated (high signal)**

- `app/Providers/AppServiceProvider.php` — rate limiters + cached view shares
- `bootstrap/app.php` — `throttle.mutations` alias
- `routes/web.php`, `routes/auth.php` — throttles + OTP routes
- `app/Http/Controllers/Frontend/HomeController.php` — cached home payload
- Setting/Menu/Slider/Service + Admin Blog controllers — cache invalidation
- Wallet controllers, `StoreController`, `UserDashboardController`, `DogController` — pagination
- `app/Helpers/GenralHelpers.php` — deprecated subclass
- `composer.json` — drop files autoload for typo helper
- `phpunit.xml`, `tests/TestCase.php`, `tests/Feature/ExampleTest.php`
- `database/factories/UserFactory.php`, `app/Models/User.php` — factory/`family`/HasFactory
- `resources/views/user/*` — `admin.users.*` routes
- Blade/seeders — `GeneralHelpers` references
- `structure.md` — map updates

## Remaining gaps

- OTP send does not call Kavenegar yet (logs + cache only); wire SMS when credentials are ready.
- Many secondary `->get()` calls remain (dashboard widgets, comments, filter lookups) — paginate/cache when those screens grow.
- Store/blog slider blogs still use `take(9)` (intentional for Swiper).
- Full Vite `public/build` still required for non-test HTTP; tests use `withoutVite()`.
- Payment gateway (`shetabit/payment`) checkout endpoints are thin — extend `throttle:payment` when real pay routes land.