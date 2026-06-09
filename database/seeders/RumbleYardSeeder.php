<?php

namespace Database\Seeders;

use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Review;
use App\Models\Sport;
use App\Models\User;
use App\Models\Venue;
use App\Models\VenueImage;
use App\Services\BookingCodeService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RumbleYardSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'Admin Rumble',
            'email' => 'admin@rumbleyard.test',
        ]);
        $admin->assignRole('admin');

        $owner = User::factory()->create([
            'name' => 'Owner Lapangan',
            'email' => 'owner@rumbleyard.test',
        ]);
        $owner->assignRole('venue_owner');

        $customer = User::factory()->create([
            'name' => 'Customer Demo',
            'email' => 'customer@rumbleyard.test',
        ]);
        $customer->assignRole('customer');

        $sports = Sport::all();
        $cities = ['Jakarta', 'Bandung', 'Surabaya', 'Yogyakarta', 'Bali'];

        $venueNames = [
            'Arena Bola Prime', 'Futsal Galaxy', 'Hoops Center', 'Shuttle King Court',
            'Volley Max Arena', 'Tennis Elite Club', 'Green Fairway', 'Diamond Field',
            'Urban Pitch', 'Night Futsal Hub',
        ];

        foreach ($venueNames as $i => $name) {
            $sport = $sports->random();
            $city = $cities[$i % count($cities)];

            $venue = Venue::create([
                'user_id' => $owner->id,
                'sport_id' => $sport->id,
                'name' => $name,
                'slug' => Str::slug($name).'-'.Str::lower(Str::random(4)),
                'description' => "Lapangan {$sport->name} premium dengan fasilitas lengkap, parkir luas, dan lighting terbaik.",
                'address' => 'Jl. Olahraga No. '.($i + 10).', '.$city,
                'city' => $city,
                'latitude' => -6.2 + ($i * 0.01),
                'longitude' => 106.8 + ($i * 0.01),
                'price_per_hour' => rand(15, 80) * 10000,
                'rating_avg' => rand(40, 50) / 10,
                'reviews_count' => rand(5, 120),
                'opening_hours' => $this->openingHours(),
                'is_active' => true,
                'is_featured' => $i < 4,
            ]);

            foreach (range(1, 3) as $img) {
                VenueImage::create([
                    'venue_id' => $venue->id,
                    'path' => 'https://picsum.photos/seed/'.($venue->id.$img).'/800/600',
                    'is_primary' => $img === 1,
                    'sort_order' => $img,
                ]);
            }

            Review::create([
                'user_id' => $customer->id,
                'venue_id' => $venue->id,
                'rating' => rand(4, 5),
                'comment' => 'Lapangan bersih, booking mudah, recommended!',
            ]);
        }

        $venue = Venue::first();
        $codeService = app(BookingCodeService::class);

        $booking = Booking::create([
            'code' => $codeService->generate(),
            'user_id' => $customer->id,
            'venue_id' => $venue->id,
            'booking_date' => now()->addDays(2)->toDateString(),
            'start_time' => '10:00',
            'end_time' => '12:00',
            'duration_hours' => 2,
            'players_count' => 10,
            'status' => BookingStatus::Confirmed,
            'subtotal' => $venue->price_per_hour * 2,
            'total' => $venue->price_per_hour * 2,
        ]);

        Payment::create([
            'booking_id' => $booking->id,
            'gateway' => 'mock',
            'transaction_id' => 'MOCK-DEMO-001',
            'amount' => $booking->total,
            'status' => PaymentStatus::Paid,
            'paid_at' => now(),
        ]);

        $customer->favorites()->attach($venue->id);
    }

    protected function openingHours(): array
    {
        $default = ['open' => '08:00', 'close' => '22:00'];

        return collect(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'])
            ->mapWithKeys(fn ($d) => [$d => $default])
            ->put('default', $default)
            ->all();
    }
}
