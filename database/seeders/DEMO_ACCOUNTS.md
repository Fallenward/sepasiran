# Demo accounts (DemoScenarioSeeder)

Password for all accounts: `password123`

| Role | Email | Phone |
|------|-------|-------|
| Admin | admin@sepasiran.test | 09120000001 |
| Customer | customer@sepasiran.test | 09121110001 |
| Coach | coach@sepasiran.test | 09122220001 |
| Clinic | clinic@sepasiran.test | 09123330001 |
| Breeder | breeder@sepasiran.test | 09124440001 |

Extras: `coach2@sepasiran.test`, `clinic2@sepasiran.test`

## What gets seeded

- Role attachments (providers also get `user`)
- Owned dogs for customer + breeder (ready/pending/waiting/sold)
- Coach courses
- Bookings across COACH / CLINIC / BREEDER with multiple statuses
- 6 months of wallet transactions (for finance charts)
- Read + unread notifications

## Commands

```bash
php artisan db:seed --class=DemoScenarioSeeder
# or full:
php artisan migrate:fresh --seed
```

Then sign in and open:

- `/user/dashboard`, `/coach/dashboard`, `/clinic/dashboard`, `/breeder/dashboard`
- `/wallet`, `/account/profile`, `/account/notifications`
- `/booking`, `/breeder/dogs-listing`, `/breeder/dashboard-create`

## Role dashboard QA routes

| Role | Dashboard | Special sections |
|------|-----------|------------------|
| Customer | `/user/dashboard` | `/account/bookings`, `/account/dogs`, `/account/dogs/create` |
| Coach | `/coach/dashboard` | `/coach/reservations`, `/coach/listing` |
| Clinic | `/clinic/dashboard` | `/clinic/reservations` |
| Breeder | `/breeder/dashboard` | `/breeder/reservations`, `/breeder/dogs-listing`, `/breeder/dashboard-create` |
