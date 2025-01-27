<?php

namespace Database\Factories;

use App\Models\Venue;
use Illuminate\Database\Eloquent\Factories\Factory;

class VenueFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Venue::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company(), // Generate a random company name for the venue name
            'address' => $this->faker->address(), // Generate a random address
            'capacity' => $this->faker->numberBetween(100, 1000), // Random capacity between 100 and 1000
        ];
    }
}
