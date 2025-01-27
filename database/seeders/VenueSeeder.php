<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Venue;

class VenueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Etkinlik mekanını ekle
        Venue::create([
            'name' => 'Concert Hall',
            'address' => '123 Music Street, Downtown, City',
            'capacity' => 500,
        ]);

        // 2. Etkinlik mekanını ekle
        Venue::create([
            'name' => 'Sports Arena',
            'address' => '456 Arena Lane, Uptown, City',
            'capacity' => 10000,
        ]);

        // 3. Etkinlik mekanını ekle
        Venue::create([
            'name' => 'Conference Center',
            'address' => '789 Business Road, City Center, City',
            'capacity' => 250,
        ]);

        // 4. Etkinlik mekanını ekle
        Venue::create([
            'name' => 'Outdoor Stadium',
            'address' => '101 Stadium Blvd, Countryside, City',
            'capacity' => 20000,
        ]);
    }
}
