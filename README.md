# Sepasiran

Sepasiran is an Iranian dog-care marketplace: find dogs, book coaches and clinics, shop pet supplies, take online courses, and buy pet insurance — with wallets, bookings, support chat, and role dashboards for customers and providers.

## What you can do

- **Dog supply** — browse and buy dogs; breeders manage listings and ownership after purchase
- **Booking** — book a coach, clinic, or breeder visit (pick a provider or post an open request)
- **Coaches, clinics & breeders** — public directories and profiles by city
- **Online courses** — multi-lesson courses with wallet checkout; coaches publish and manage courses
- **Pet insurance** — timed plans for pets; purchased licenses show in the customer dashboard
- **Store (shop)** — see below
- **Blog & news** — owner guides and site news (`/blog`, `/news`, article pages)
- **Shelter & support** — shelter requests plus live support tickets / contact forms
- **Accounts** — customer hub (dogs, bookings, courses, insurance, wallet); coach / clinic / breeder hubs; admin panel

## Store (shop) — what it actually does

The public shop is the **pet supplies catalog** at `/stores` (menu: فروشگاه → محصولات).

| Capability | Behavior today |
|------------|----------------|
| Browse | Active products only, newest first, paginated |
| Search | Title, description, and SKU (`?q=`) |
| Filter | Category filter on the listing |
| Product page | `/products/{id}` — description, list/discount price, stock, SKU, category, related items |
| Pricing | Shows discount price when set and lower than list price |
| Stock | “موجود / ناموجود” from `stock` + `is_active` |
| Purchase CTA | If `buy_link` is set → external “خرید آنلاین”; otherwise links back to the store listing (no in-app cart checkout on the storefront yet) |
| Admin | Admins manage products under the admin catalog; carts exist as an admin resource, not a full customer checkout flow |

So the shop is a **catalog + product detail + optional external buy link**, not a full cart/wallet checkout like courses or dog orders.

## Roles

| Role | Typical use |
|------|-------------|
| Customer | Own dogs, book services, buy courses/insurance, browse shop, wallet |
| Coach | Profile, courses, availability, reservation inbox |
| Clinic | Profile, reservations, insurance-related flows |
| Breeder | Dog listings, reservations |
| Admin | Users/providers, catalog, content, support, finance |

## Stack

- Laravel 12 (PHP 8.2+), Blade, Vite
- MySQL (Laragon common locally)
- Role/permission tables; Shetabit Multipay for gateway payments; in-app wallet for many purchases
- Jalali helpers, Iran cities, Kavenegar SMS where configured

## Local run

```bash
composer install
cp .env.example .env   # set DB_* and APP_URL
php artisan key:generate
php artisan migrate
php artisan db:seed    # optional demo data
npm install
npm run build          # or: npm run dev
php artisan serve      # http://127.0.0.1:8000
```

Demo accounts (after seed): see `database/seeders/DEMO_ACCOUNTS.md`  
Password for demo users: `password123` (e.g. `admin@sepasiran.test`, `customer@sepasiran.test`).

## Main public routes

| Path | Purpose |
|------|---------|
| `/` | Home (services block `#services`) |
| `/dog-supply`, `/dog/{id}` | Dog marketplace |
| `/booking` | Service booking |
| `/coaches`, `/clinics`, `/breeders` | Provider directories |
| `/course` | Online courses |
| `/clinic-insurance` | Insurance listing & purchase |
| `/stores`, `/products/{id}` | Shop catalog & product detail |
| `/blog`, `/news`, `/blogs/{slug}` | Blog & news |
| `/shelter`, `/support`, `/contact`, `/about` | Shelter, support, contact, about |
| `/user/dashboard` | Customer hub (auth) |
| `/admin/...` | Admin panel (admin auth) |

## License

Proprietary application code unless otherwise noted. Laravel framework itself remains MIT.