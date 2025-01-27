<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Etkinlik
        Event::create([
            'name' => 'Rock Concert',
            'description' => 'A thrilling rock concert with live performances.',
            'venue_id' => 1, // Örneğin, venue_id 1 (Concert Hall)
            'start_date' => Carbon::parse('2025-02-01 20:00:00'),
            'end_date' => Carbon::parse('2025-02-01 23:00:00'),
            'status' => 'active',
        ]);

        // 2. Etkinlik
        Event::create([
            'name' => 'Basketball Game',
            'description' => 'Exciting basketball match between top teams.',
            'venue_id' => 2, // Sports Arena
            'start_date' => Carbon::parse('2025-03-15 19:00:00'),
            'end_date' => Carbon::parse('2025-03-15 21:00:00'),
            'status' => 'active',
        ]);

        // 3. Etkinlik
        Event::create([
            'name' => 'Business Conference',
            'description' => 'A two-day business conference on the latest industry trends.',
            'venue_id' => 3, // Conference Center
            'start_date' => Carbon::parse('2025-04-10 09:00:00'),
            'end_date' => Carbon::parse('2025-04-11 17:00:00'),
            'status' => 'active',
        ]);

        // 4. Etkinlik
        Event::create([
            'name' => 'Music Festival',
            'description' => 'A 3-day music festival featuring popular artists.',
            'venue_id' => 4, // Outdoor Stadium
            'start_date' => Carbon::parse('2025-05-01 14:00:00'),
            'end_date' => Carbon::parse('2025-05-03 23:59:59'),
            'status' => 'active',
        ]);
    }
}
