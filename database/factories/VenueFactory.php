<?php

namespace Database\Factories;

use App\Models\Sport;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class VenueFactory extends Factory
{
    protected $model = Venue::class;

    public function definition(): array
    {
        $name = fake()->company().' Sports';

        return [
            'user_id' => User::factory(),
            'sport_id' => Sport::factory(),
            'name' => $name,
            'slug' => Str::slug($name).'-'.Str::random(4),
            'description' => fake()->paragraph(),
            'address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'price_per_hour' => fake()->numberBetween(50000, 500000),
            'rating_avg' => fake()->randomFloat(1, 3.5, 5),
            'reviews_count' => fake()->numberBetween(0, 50),
            'is_active' => true,
            'is_featured' => false,
        ];
    }
}
