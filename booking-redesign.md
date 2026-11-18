# Booking redesign — product contract (v2)

Branch: `cursor/booking-redesign-71c0`  
Audience: frontend agent `bc-3401b1a6` + admin.

> **Breaking change vs v1.** Do not treat old service→provider→slot-only flow as final.

## Correct step flow

| Step | UI | Data |
|------|----|------|
| 0 | Gate | Customer **must be authenticated**. If they have **no owned dogs**, send them to add a dog first (deep-link back to `/booking`). |
| 1 | سگ من | `dog_id` — must belong to `auth()->id()` |
| 2 | شهر | `city_id` required |
| 3 | خدمت | `service_type`: `coach` \| `clinic` \| `breeder` \| `insurance`. If `coach`: `training_type` required. |
| 4 | نحوه انجام | `fulfillment_mode`: **`direct`** \| **`open`** |
| 4a | Direct | Select one or more `provider_ids[]` (role-matched). Preferred `date` + `slot` optional but recommended. |
| 4b | Open | Publish open request; no provider yet. Qualified providers submit **offers**. |
| 5 | تماس / یادداشت | `contact_name`, `contact_phone`, optional `notes` |
| 6 | تأیید | Quote from pricing API → `POST` create. Success: calm screen + FaNum `booking_code`. |

### Fulfillment semantics

| Mode | Behavior | Initial `status` |
|------|----------|------------------|
| `direct` | Request routed to selected provider(s). Provider confirms or declines. | `PENDING` |
| `open` | Listed for matching role providers in city (or nationwide). Providers apply with price/message. Customer **accepts one offer** → binds `provider_id`. | `OPEN` |

After accept / provider confirm → `ACCEPTED` → (optional pay) → `CONFIRMED` → `COMPLETED`.  
Cancel anytime (rules below) → `CANCELLED`.

## Auth rules

| Action | Auth |
|--------|------|
| `GET /booking/api/meta` | Public |
| `GET /booking/api/my-dogs` | **Auth** |
| `GET /booking/api/providers`, `slots`, `quote` | Auth recommended; providers/slots public OK |
| `POST /booking/api` (create) | **Auth** + must own `dog_id` |
| `GET /booking/api/{code}` | Owner or admin |
| `GET /booking/api/inbox/open` (provider feed) | Auth + provider role |
| `POST …/offers` | Auth + provider role matching service |
| `POST …/offers/{id}/accept` | Auth + booking owner |
| `POST …/cancel` | Owner or admin |

Guests hitting `/booking` → login with `?redirect=/booking`.

## Pricing

Quote endpoint returns amounts; create persists the same numbers (unless open offer later overrides).

```
base = catalog default for service_type
(+ training surcharge if coach + advanced|behavioral)
mode_fee = direct: 0 | open: open_listing_fee (catalog)
subtotal = base + surcharge
tax = round(subtotal * tax_rate)   // default 0 for v1
total = subtotal + tax + mode_fee - discount
```

| Field | Source |
|-------|--------|
| `price` | base (+ surcharge) |
| `tax` | computed |
| `discount` | 0 unless coupon later |
| `total` | final charged / expected |
| On offer accept | May replace `price`/`total` with accepted offer amount |

Catalog keys in `BookingCatalog` / `GET meta.pricing`.

## JSON endpoints

Base: `/booking/api` (web + CSRF on mutating).

### `GET /booking/api/meta`

```json
{
  "service_types": [
    { "key": "coach", "label": "مربی", "default_price": 1200000, "role": "coach" },
    { "key": "clinic", "label": "کلینیک", "default_price": 850000, "role": "clinic" },
    { "key": "breeder", "label": "پرورش‌دهنده", "default_price": 500000, "role": "breeder" },
    { "key": "insurance", "label": "بیمه", "default_price": 1500000, "role": "clinic" }
  ],
  "fulfillment_modes": [
    { "key": "direct", "label": "ارسال مستقیم به ارائه‌دهنده" },
    { "key": "open", "label": "درخواست باز — ارائه‌دهندگان پیشنهاد بدهند" }
  ],
  "training_types": [
    { "key": "beginner", "label": "آموزش مقدماتی", "surcharge": 0 },
    { "key": "advanced", "label": "تربیت پیشرفته", "surcharge": 200000 },
    { "key": "behavioral", "label": "اصلاح رفتار", "surcharge": 300000 },
    { "key": "care", "label": "آموزش نگهداری", "surcharge": 0 },
    { "key": "other", "label": "سایر", "surcharge": 0 }
  ],
  "slot_template": ["09:00", "11:00", "14:30", "16:00", "18:00", "20:00"],
  "statuses": ["PENDING", "OPEN", "ACCEPTED", "CONFIRMED", "CANCELLED", "COMPLETED"],
  "pricing": {
    "tax_rate": 0,
    "open_listing_fee": 50000,
    "currency": "IRT"
  },
  "add_dog_url": "/breeder/dashboard-create"
}
```

### `GET /booking/api/my-dogs` (auth)

```json
{
  "results": [
    { "id": 5, "title": "راکی", "breed_id": 2, "breed_name": "هاسکی", "image": null }
  ],
  "empty": false,
  "add_dog_url": "/breeder/dashboard-create"
}
```

If `results` empty → UI blocks stepper and shows CTA to add dog.

### `GET /booking/api/providers`

Query: `service_type` (required), `city_id?`, `q?`  
Same shape as before (`display_name`, `city`, `role`). Used for **direct** mode.

### `GET /booking/api/slots`

Query: `provider_id`, `date=Y-m-d` — preferred schedule helper for direct mode (availability filter unchanged).

### `POST /booking/api/quote` (auth)

Body:

```json
{
  "service_type": "coach",
  "fulfillment_mode": "open",
  "training_type": "behavioral"
}
```

Response:

```json
{
  "currency": "IRT",
  "price": 1500000,
  "surcharge": 300000,
  "mode_fee": 50000,
  "tax": 0,
  "discount": 0,
  "total": 1550000,
  "breakdown": [
    { "label": "پایه خدمت", "amount": 1200000 },
    { "label": "نوع آموزش", "amount": 300000 },
    { "label": "کارمزد درخواست باز", "amount": 50000 }
  ]
}
```

### `POST /booking/api` (auth) — create request

```json
{
  "dog_id": 5,
  "city_id": 3,
  "service_type": "coach",
  "training_type": "beginner",
  "fulfillment_mode": "direct",
  "provider_ids": [12],
  "preferred_date": "2025-11-20",
  "preferred_slot": "11:00",
  "contact_name": "سعید",
  "contact_phone": "09121234567",
  "notes": "اختیاری",
  "payment_method": "online"
}
```

Validation:

- `dog_id` required + `dogs.user_id === auth id`
- `city_id` required
- `service_type` required
- `fulfillment_mode` required
- `direct` → `provider_ids` min:1, each role-matched
- `open` → `provider_ids` must be empty/absent
- `coach` → `training_type` required
- `preferred_date` optional, `after_or_equal:today` if present
- `preferred_slot` optional, must be in template if present

`201`:

```json
{
  "booking": { "...serialized..." },
  "success_url": "/booking/success/SP-482910"
}
```

### `GET /booking/api/{booking_code}` (auth owner/admin)

Serialized booking includes `dog`, `city`, `fulfillment_mode`, `status`, `offers[]` (if any), pricing, preferred date/slot Jalali helpers.

### Provider open inbox — `GET /booking/api/inbox/open` (auth provider)

Lists `OPEN` bookings matching provider role (+ optional same city).

### `POST /booking/api/{booking_code}/offers` (auth provider)

```json
{ "price": 1100000, "message": "می‌توانم فردا بیایم", "proposed_date": "2025-11-21", "proposed_slot": "14:30" }
```

Creates `booking_offers` row `status=pending`.

### `POST /booking/api/{booking_code}/offers/{offer_id}/accept` (auth owner)

- Sets offer `accepted`, others on booking `rejected`
- Sets booking `provider_id`, `status=ACCEPTED`, syncs price/total from offer
- Optionally copies proposed date/slot onto appointment fields

### `POST /booking/api/{booking_code}/cancel` (auth owner/admin)

Allowed while status in `PENDING|OPEN|ACCEPTED` → `CANCELLED`.

## Schema

### `bookings` (additive)

| Column | Type | Notes |
|--------|------|-------|
| `dog_id` | FK nullable→required on new rows | owned dog |
| `fulfillment_mode` | `direct`\|`open` | default `direct` |
| `preferred_date` | date nullable | |
| `preferred_slot` | string(10) nullable | |
| existing | `booking_code`, `service_type`, `provider_id`, `city_id`, `training_type`, `slot`, `appointment_date`, contact_*, price fields, `status` | |
| `status` values | `PENDING`, `OPEN`, `ACCEPTED`, `CONFIRMED`, `CANCELLED`, `COMPLETED` | migrate enum / string |

### `booking_providers` (pivot for direct multi-select)

`booking_id`, `provider_id`, timestamps. Primary provider mirrored on `bookings.provider_id` (first selected).

### `booking_offers`

| Column | Type |
|--------|------|
| `id` | bigint |
| `booking_id` | FK |
| `provider_id` | FK users |
| `price` | decimal |
| `message` | text nullable |
| `proposed_date` | date nullable |
| `proposed_slot` | string nullable |
| `status` | `pending`\|`accepted`\|`rejected`\|`withdrawn` |
| timestamps | |

## Blade / Vue shell

| Route | Role |
|-------|------|
| `GET /booking` | Stepper shell (auth gate + empty-dogs CTA) |
| `GET /booking/success/{code}` | FaNum code success |
| Legacy `/select-*` | Redirect → `/booking` |

Frontend owns Jalali UI; API Gregorian `Y-m-d` + `H:i`.

## Admin

List/filter by `fulfillment_mode`, `status`, dog, city; show open-offer counts.

## Implementation map

| Piece | Path |
|-------|------|
| Catalog + pricing | `app/Support/BookingCatalog.php` |
| JSON API | `app/Http/Controllers/Frontend/BookingApiController.php` |
| Shell | `Frontend\BookingController` |
| Models | `Booking`, `BookingOffer` |
| Migrations | `2026_11_19_*_booking_v2_*` |
| Tests | `tests/Feature/BookingApiTest.php` |

## Out of scope (still)

- Live payment gateway capture
- Provider calendar hours beyond slot template
- Guest draft persistence
