<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reservation;
use App\Models\Seat;
use App\Models\Event;
use App\Models\User;

class ReservationSeeder extends Seeder
{
    public function run()
    {
        $user = User::first(); // İlk kullanıcıyı al
        $event = Event::first(); // İlk etkinliği al

        $seats = Seat::take(3)->get(); // 3 koltuk al

        // Rezervasyonu oluştur
        $reservation = Reservation::create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'status' => 'pending',
            'total_amount' => $seats->sum('price'),
            'expires_at' => now()->addMinutes(15),
        ]);

        // ReservationItem'ları oluştur
        foreach ($seats as $seat) {
            $reservation->reservationItems()->create([
                'seat_id' => $seat->id,
                'price' => $seat->price,
            ]);
        }

        // Ticket'ları oluştur
        foreach ($reservation->reservationItems as $item) {
            \App\Models\Ticket::create([
                'reservation_id' => $reservation->id,
                'seat_id' => $item->seat_id,
                'ticket_code' => 'TICKET-' . strtoupper(uniqid()),
                'status' => 'active',
            ]);
        }
    }
}
