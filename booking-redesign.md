# Booking redesign — API & domain contract

Branch: `cursor/booking-redesign-71c0`  
Audience: Vue/Blade frontend (agent bc-3401b1a6) + admin.

## Correct step flow

Maps design preview + product domain (not the old dead wizard pages alone):

| Step | UI label | Data captured |
|------|----------|---------------|
| 1 | انتخاب خدمت | `service_type` (`coach` \| `clinic` \| `breeder` \| `insurance`). For `coach`: optional `training_type`, `breed_id`. |
| 2 | ارائه‌دهنده | `provider_id` (user with matching role; insurance → clinic providers). Optional `city_id` filter. |
| 3 | زمان | Jalali date in UI → send Gregorian `date` (`Y-m-d`) + `slot` (`H:i`). Slots come from API (availability filtered). |
| 4 | اطلاعات تماس | `contact_name`, `contact_phone`, optional `notes`. |
| 5 | تأیید | Sticky summary; `POST` create. Success shows calm screen with **FaNum `booking_code`**. |

Legacy Blade pages (`/select-trainer-type`, `/select-breed`, `/select-time`, `/summary`, `/order-summary`) redirect into `/booking` so one canonical flow remains.

## Auth rules

| Action | Auth |
|--------|------|
| `GET` meta / breeds / providers / slots | Public |
| `POST /booking/api` (create) | **Required** (`auth` middleware). Guests get `401` JSON or redirect to login with `?redirect=/booking`. |
| `GET` success by code | Owner of booking, or `is_admin` / admin role. Public reveal by code alone is allowed only for the creator session; API checks ownership when logged in. |

Admin CRUD remains under `/admin/bookings` (`auth` + `is_admin`).

## JSON endpoints

Base prefix: `/booking/api` (web middleware, CSRF for POST, `Accept: application/json`).

### `GET /booking/api/meta`

Service catalog + training types + default slot grid template.

```json
{
  "service_types": [
    { "key": "coach", "label": "مربی", "default_price": 1200000 },
    { "key": "clinic", "label": "کلینیک", "default_price": 850000 },
    { "key": "breeder", "label": "پرورش‌دهنده", "default_price": 500000 },
    { "key": "insurance", "label": "بیمه", "default_price": 1500000 }
  ],
  "training_types": [
    { "key": "beginner", "label": "آموزش مقدماتی" },
    { "key": "advanced", "label": "تربیت پیشرفته" },
    { "key": "behavioral", "label": "اصلاح رفتار" },
    { "key": "care", "label": "آموزش نگهداری" },
    { "key": "other", "label": "سایر" }
  ],
  "slot_template": ["09:00", "11:00", "14:30", "16:00", "18:00", "20:00"],
  "currency": "IRT"
}
```

### `GET /booking/api/breeds?q=`

```json
{ "results": [ { "id": 1, "name": "ژرمن شپرد" } ] }
```

### `GET /booking/api/providers`

Query: `service_type` (required), `city_id?`, `breed_id?` (hint only), `q?`

```json
{
  "results": [
    {
      "id": 12,
      "name": "سارا",
      "family": "محمدی",
      "display_name": "سارا محمدی",
      "city": { "id": 3, "name": "شیراز" },
      "photo": null,
      "role": "coach"
    }
  ]
}
```

### `GET /booking/api/slots`

Query: `provider_id` (required), `date` (Gregorian `Y-m-d`, required), `service_type?`

```json
{
  "date": "2025-11-19",
  "date_jalali": "1404/08/28",
  "slots": [
    { "time": "09:00", "available": true },
    { "time": "11:00", "available": false },
    { "time": "14:30", "available": true }
  ]
}
```

Unavailable = already booked (`status` not `CANCELLED`) for that provider+datetime.

### `POST /booking/api`

Auth required. Body:

```json
{
  "service_type": "coach",
  "provider_id": 12,
  "city_id": 3,
  "breed_id": 1,
  "training_type": "beginner",
  "date": "2025-11-19",
  "slot": "11:00",
  "contact_name": "سعید",
  "contact_phone": "09121234567",
  "notes": "اختیاری",
  "payment_method": "online"
}
```

Validation highlights:

- `date` >= today (app timezone)
- `slot` must be available for provider
- `provider_id` must match role for `service_type`
- `breed_id` / `training_type` required when `service_type=coach` (soft: nullable for clinic/breeder/insurance)

Success `201`:

```json
{
  "booking": {
    "id": 99,
    "booking_code": "SP-482910",
    "status": "PENDING",
    "payment_status": "pending",
    "payment_method": "online",
    "service_type": "coach",
    "training_type": "beginner",
    "breed": { "id": 1, "name": "ژرمن شپرد" },
    "provider": { "id": 12, "display_name": "سارا محمدی" },
    "appointment_date": "2025-11-19T11:00:00+03:30",
    "appointment_jalali": "1404/08/28",
    "slot": "11:00",
    "price": 1200000,
    "tax": 0,
    "discount": 0,
    "total": 1200000,
    "contact_name": "سعید",
    "contact_phone": "09121234567",
    "notes": "اختیاری"
  },
  "success_url": "/booking/success/SP-482910"
}
```

Conflict (slot taken): `422` with message.

### `GET /booking/api/{booking_code}`

Returns same `booking` object (ownership rules above).

## Blade page routes (shell)

| Method | Path | Name | Notes |
|--------|------|------|-------|
| GET | `/booking` | `frontend.booking.index` | Stepper shell; no inventing prices client-side beyond meta |
| GET | `/booking/success/{code}` | `frontend.booking.success` | Calm success; code is FaNum-friendly |
| POST | `/booking` | `frontend.booking.store` | Form fallback (same validation as JSON) |

Deprecated JSON shim kept temporarily: `GET /booking/providers` → same as `/booking/api/providers`.

## Model / migration changes

Table `bookings` (existing columns kept):

| Column | Change |
|--------|--------|
| `booking_code` | **new** `string(32)` unique — public reference |
| `breed_id` | **new** nullable FK → `breeds` |
| `training_type` | **new** nullable string |
| `slot` | **new** nullable `string(10)` (`H:i`) |
| `contact_name` | **new** nullable |
| `contact_phone` | **new** nullable |

Existing: `service_type`, `provider_id`, `city_id`, `appointment_date`, `price`/`tax`/`discount`/`total`, `payment_*`, `status`, `notes`, `transaction_id`.

`Booking` fillable/casts/relations updated to match (no morph fields).  
Optional: attach pending `Transaction` later; not required for v1 create.

## Admin

- List shows `booking_code`, customer, provider, Jalali appointment, status, total.
- Eager-load `user`, `provider`, `breed`, `city`.
- Routes stay `admin.bookings.*`.

## Frontend integration notes

- Convert Jalali ↔ Gregorian in UI; APIs speak Gregorian `Y-m-d` + `H:i`.
- Use FaNum only in presentation (`booking_code`, prices, Jalali digits).
- Do not hardcode prices/slots — always from meta/slots endpoints.
- CSRF header on `POST /booking/api`.

## Out of scope (v1 gaps)

- Provider weekly calendar UI / custom hours per weekday (fixed slot template + conflict filter).
- Online gateway charge on confirm (status stays `payment_status=pending`).
- Cart pivot for pure service bookings (relation remains for shop hybrids).