<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(VenueSeeder::class);
        $this->call(EventSeeder::class);
        $this->call(SeatSeeder::class);
        $this->call(TicketSeeder::class);
        $this->call(ReservationSeeder::class);
       // $this->call(ReservationsTableSeeder::class);
    }
}
