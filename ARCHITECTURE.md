# Rumble Yard — Architecture

## Stack
- Laravel 12, PHP 8.2+
- Tailwind CSS 4, Alpine.js, Flowbite, AOS, Lucide, Chart.js
- Spatie Laravel Permission, Laravel Socialite
- MySQL (production) / SQLite (local dev)

## Folder Structure (key paths)
```
app/
  Contracts/          Payment gateway interface
  Enums/              BookingStatus, PaymentStatus, PaymentGateway
  Events/             BookingCreated, PaymentCompleted
  Http/Controllers/   Web + Admin + Owner + Auth
  Http/Requests/      Form validation
  Listeners/          Notifications & activity logging
  Models/             Eloquent models
  Policies/           Venue, Booking authorization
  Repositories/       Venue & Booking data access
  Services/           Booking, Payment, Schedule, ActivityLog
database/migrations/  Schema
database/seeders/     Roles, sports, demo data
resources/views/      Blade UI
routes/web.php        Public + authenticated routes
routes/auth.php       Login, register, Google OAuth
```

## ERD (Entity Relationships)

```
users 1───* venues (owner)
users 1───* bookings
users *───* venues (favorites pivot)
users 1───* reviews
users 1───* activity_logs

sports 1───* venues

venues 1───* venue_images
venues 1───* bookings
venues 1───* reviews

bookings 1───* booking_details
bookings 1───1 payments
bookings 1───0..1 reviews

roles *───* users (Spatie permission tables)
```

## Roles
| Role | Guard | Access |
|------|-------|--------|
| guest | — | Home, search, venue detail |
| customer | web | Book, pay, favorites, profile |
| venue_owner | web | Owner dashboard, manage venues |
| admin | web | Admin analytics & logs |

## Booking Flow
1. Select venue → date/time/duration on venue detail (Alpine + slots API)
2. `BookingService` creates booking with unique code `RY-{year}-{5digits}`
3. DB unique constraint prevents double booking
4. `PaymentService` + `MockPaymentGateway` for checkout
5. On success → booking confirmed, notification + activity log

## Payment Architecture
- `PaymentGatewayInterface` → `MockPaymentGateway` (now)
- Ready for `MidtransGateway`, `XenditGateway` implementing same interface
- Config via `config/services.php` and `.env`

## Demo Accounts (after `php artisan db:seed`)
| Email | Password | Role |
|-------|----------|------|
| admin@rumbleyard.test | password | admin |
| owner@rumbleyard.test | password | venue_owner |
| customer@rumbleyard.test | password | customer |
