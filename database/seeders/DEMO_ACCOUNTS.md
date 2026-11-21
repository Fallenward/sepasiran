# Demo accounts

Password for **all** accounts: `password123`

Login accepts **email or phone**.

| Role | Email | Phone |
|------|-------|-------|
| Admin | admin@sepasiran.test | 09120000001 |
| Customer | customer@sepasiran.test | 09121110001 |
| Coach | coach@sepasiran.test | 09122220001 |
| Clinic | clinic@sepasiran.test | 09123330001 |
| Breeder | breeder@sepasiran.test | 09124440001 |

Extras: `coach2@sepasiran.test`, `clinic2@sepasiran.test`

## Seed commands

```bash
# Booking-ready demo (accounts + services + availability + open session)
php artisan db:seed --class=BookingDemoSeeder

# Or full reset
php artisan migrate:fresh --seed
```

`BookingDemoSeeder` also runs `DemoScenarioSeeder`, then adds:

- Coach/clinic/breeder **provider services** (active + one inactive coach service)
- **Availability** Mon–Sun 09:00–18:00 with 13:00–14:00 break
- Customer wallet **10,000,000** + owned dogs
- Published open session for the coach

## How to test locked coach booking

1. Login as **customer** (`customer@sepasiran.test` / `password123`)
2. Open: `/booking?type=coach&provider={COACH_ID}`  
   (printed by the seeder, or look up coach id in DB / profile)
3. Pick dog → city → **service** → date/slot → pay with wallet
4. Logout, login as **coach**, open `/account/bookings?tab=inbox` → Accept / Reject

## Other useful URLs

- Customer: `/user/dashboard`, `/account/dogs`, `/booking/open`, `/wallet`
- Coach: `/coach/dashboard`, `/account/services`, `/account/availability`, `/open-sessions/mine`
- Clinic / Breeder: role dashboards + `/account/services`
