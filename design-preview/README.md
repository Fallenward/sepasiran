# Sepasiran design preview

Static HTML/CSS mock of the dark / green / gold UI direction. No Laravel, Vite, or PHP.

## Open

- Double-click `index.html`, or
- From this folder: `python -m http.server 8765` then visit `http://127.0.0.1:8765`

Dana (Fontiran) loads from `../resources/fonts/css/fontiran.css`. Syne loads from Google Fonts for the Latin lockup.

## Chrome

One sticky glass header with **hover dropdowns** (desktop). Mobile uses the bottom bar. Shared **site footer** on every page. No duplicate top preview bar.

## Screens

| File | Screen |
|------|--------|
| `index.html` | Home (brand hero + paths + tiles) |
| `dogs.html` | Dog listing |
| `store.html` | Store / product |
| `booking.html` | Booking stepper |
| `auth.html` | Auth split |
| `dashboard.html` | User dashboard |
| `admin.html` | AdminLTE wrap concept |

Design docs live in the Project store: `docs/ui-design-system.md`, `docs/ui-page-specs.md`.
