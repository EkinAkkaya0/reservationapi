<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ticket;
use App\Models\Reservation;
use App\Models\Seat;

class TicketSeeder extends Seeder
{
    public function run()
    {
        // Öncelikle aktif olan rezervasyonları alalım
        $reservations = Reservation::where('status', 'confirmed')->get();

        foreach ($reservations as $reservation) {
            // Her bir rezervasyon için ilgili rezervasyon item'larını alalım
            $reservationItems = $reservation->reservationItems;

            foreach ($reservationItems as $item) {
                // Her koltuk için bir ticket oluştur
                Ticket::create([
                    'reservation_id' => $reservation->id,
                    'seat_id' => $item->seat_id,
                    'ticket_code' => 'TICKET-' . strtoupper(uniqid()), // Benzersiz bir ticket_code üret
                    'status' => 'active', // Varsayılan olarak aktif yapıyoruz
                ]);
            }
        }

        $this->command->info('Tickets seeded successfully!');
    }
}
