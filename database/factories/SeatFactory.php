<?php

namespace Database\Factories;

use App\Models\Venue;
use App\Models\Seat;
use Illuminate\Database\Eloquent\Factories\Factory;

class SeatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'venue_id' => Venue::factory(),  // Assuming that Venue factory exists and is used to generate venue data
            'section' => $this->faker->word(),
            'row' => $this->faker->randomLetter,
            'number' => $this->faker->numberBetween(1, 20),
            'status' => $this->faker->randomElement(['available', 'reserved', 'sold', 'blocked']),
            'price' => $this->faker->randomFloat(2, 10, 100), // Generates a random price between 10 and 100
        ];
    }
}
