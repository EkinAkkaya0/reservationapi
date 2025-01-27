<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Seat;

class SeatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Venue - Concert Hall (venue_id 1)
        Seat::create([
            'venue_id' => 1, // Concert Hall
            'section' => 'A',
            'row' => '1',
            'number' => 1,
            'status' => 'available',
            'price' => 50.00,
        ]);
        
        Seat::create([
            'venue_id' => 1,
            'section' => 'A',
            'row' => '1',
            'number' => 2,
            'status' => 'available',
            'price' => 50.00,
        ]);

        Seat::create([
            'venue_id' => 1,
            'section' => 'A',
            'row' => '2',
            'number' => 1,
            'status' => 'reserved',
            'price' => 55.00,
        ]);

        // 2. Venue - Sports Arena (venue_id 2)
        Seat::create([
            'venue_id' => 2, // Sports Arena
            'section' => 'B',
            'row' => '1',
            'number' => 1,
            'status' => 'sold',
            'price' => 70.00,
        ]);

        Seat::create([
            'venue_id' => 2,
            'section' => 'B',
            'row' => '1',
            'number' => 2,
            'status' => 'available',
            'price' => 70.00,
        ]);

        Seat::create([
            'venue_id' => 2,
            'section' => 'B',
            'row' => '2',
            'number' => 1,
            'status' => 'blocked',
            'price' => 80.00,
        ]);

        // 3. Venue - Conference Center (venue_id 3)
        Seat::create([
            'venue_id' => 3, // Conference Center
            'section' => 'C',
            'row' => '1',
            'number' => 1,
            'status' => 'available',
            'price' => 40.00,
        ]);

        Seat::create([
            'venue_id' => 3,
            'section' => 'C',
            'row' => '1',
            'number' => 2,
            'status' => 'reserved',
            'price' => 45.00,
        ]);

        Seat::create([
            'venue_id' => 3,
            'section' => 'C',
            'row' => '2',
            'number' => 1,
            'status' => 'sold',
            'price' => 60.00,
        ]);

        // 4. Venue - Outdoor Stadium (venue_id 4)
        Seat::create([
            'venue_id' => 4, // Outdoor Stadium
            'section' => 'D',
            'row' => '1',
            'number' => 1,
            'status' => 'available',
            'price' => 90.00,
        ]);

        Seat::create([
            'venue_id' => 4,
            'section' => 'D',
            'row' => '1',
            'number' => 2,
            'status' => 'sold',
            'price' => 100.00,
        ]);
    }
}
