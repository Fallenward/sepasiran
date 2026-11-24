# Sepasiran

Sepasiran is an Iranian dog-care marketplace: buy and list dogs, book coaches and clinics, shop supplies, take online courses, and buy pet insurance — with wallets, bookings, and role dashboards for customers and providers.

## What you can do

- **Dog supply** — browse and buy dogs; sellers/breeders manage listings
- **Booking** — book a coach, clinic, breeder visit, or insurance flow for your dog (direct provider or open request)
- **Coaches & clinics** — find trainers and veterinary clinics by city
- **Online courses** — enroll in multi-lesson courses (wallet checkout); coaches publish courses
- **Pet insurance** — buy timed insurance plans; licenses appear in the customer dashboard
- **Store & products** — pet supplies and related products
- **Blog & news** — articles for dog owners
- **Shelter** — shelter-related listings
- **Accounts** — customer dashboard (dogs, bookings, enrollments, insurance licenses, wallet); coach / clinic / breeder dashboards; admin panel

## Roles

| Role | Typical use |
|------|-------------|
| Customer | Own dogs, book services, buy courses/insurance/products, wallet |
| Coach | Profile, courses, availability, reservations |
| Clinic | Profile, reservations, insurance-related flows |
| Breeder | Dog listings, reservations |
| Admin | Catalog, users, settings, finance |

## Stack

- Laravel 12 (PHP 8.2+), Blade, Vite
- MySQL (local Laragon common), Spatie permissions
- Shetabit Multipay for gateway payments; in-app wallet for many purchases
- Jalali / Iran cities helpers, Kavenegar SMS where configured

## Local run

```bash
composer install
cp .env.example .env   # then set DB_* (MySQL) and APP_URL
php artisan key:generate
php artisan migrate
php artisan db:seed    # optional demo data (includes CourseDemoSeeder)
npm install
npm run dev            # Vite → http://127.0.0.1:5173
php artisan serve      # app → http://127.0.0.1:8000
```

Demo course seed only:

```bash
php artisan db:seed --class=CourseDemoSeeder
```

## Main public routes

| Path | Purpose |
|------|---------|
| / | Home (includes services section #services) |
| /services | Redirects to /#services |
| `/dog-supply`, `/dog/{id}` | Dog marketplace |
| `/booking` | Service booking |
| `/coaches`, `/clinics`, `/breeders` | Provider directories |
| `/course` | Online courses |
| `/clinic-insurance` | Insurance listing & purchase |
| `/stores`, `/products/{id}` | Store |
| `/blog`, `/news` | Content |
| `/user/dashboard` | Customer hub (auth) |

## License

Proprietary application code unless otherwise noted. Laravel framework itself remains MIT.