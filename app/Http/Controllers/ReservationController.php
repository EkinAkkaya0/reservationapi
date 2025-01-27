<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\ReservationItem; // ReservationItem modelini import edin
use App\Models\Seat;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;




class ReservationController extends Controller
{
    public function store(Request $request)
{
    // Kullanıcının kimlik doğrulaması yapılmış mı kontrol et
    $user = Auth::user();

    // Geçici olarak verileri doğrula
    $validated = $request->validate([
        'event_id' => 'required|exists:events,id',
        'seats' => 'required|array', // Seçilen koltuklar array olmalı
        'seats.*' => 'exists:seats,id', // Her bir koltuğun geçerli olduğuna emin ol
    ]);

    // Event bul
    $event = Event::findOrFail($validated['event_id']);

    // Toplam tutarı hesaplamak için seat'leri alın ve fiyatlarını toplayın
    $totalAmount = 0;

    foreach ($validated['seats'] as $seatId) {
        $seat = Seat::findOrFail($seatId);
        $totalAmount += $seat->price;
    }

    // Rezervasyon oluştur
    $reservation = Reservation::create([
        'user_id' => $user->id,
        'event_id' => $event->id,
        'status' => 'pending',
        'total_amount' => $totalAmount,
        'expires_at' => now()->addMinutes(15), // 15 dakika içinde geçerlidir
    ]);

    // ReservationItem'ları oluştur
    foreach ($validated['seats'] as $seatId) {
        $seat = Seat::findOrFail($seatId);
        ReservationItem::create([
            'reservation_id' => $reservation->id,
            'seat_id' => $seat->id,
            'price' => $seat->price,
        ]);
    }

    return response()->json([
        'message' => 'Reservation created successfully!',
        'reservation' => $reservation,
    ], 201);
}


public function index()
{
    // Kullanıcının tüm rezervasyonlarını alıyoruz
    $user = Auth::user();
    $reservations = Reservation::where('user_id', $user->id)->get();

    return response()->json($reservations);
}

public function show($id)
    {
        // Kullanıcıya ait rezervasyonu alıyoruz
        $user = Auth::user();
        $reservation = Reservation::where('user_id', $user->id)->find($id);

        if (!$reservation) {
            return response()->json(['error' => 'Reservation not found'], 404);
        }

        return response()->json($reservation);
    }

    public function confirm($id)
{
    // Kullanıcıyı al
    $user = Auth::user();

    // Rezervasyonu kullanıcıya göre bul
    $reservation = Reservation::where('user_id', $user->id)->find($id);

    if (!$reservation) {
        return response()->json(['error' => 'Reservation not found'], 404);
    }

    // Rezervasyon durumu zaten onaylanmış mı kontrol et
    if ($reservation->status == 'confirmed') {
        return response()->json(['error' => 'Reservation already confirmed'], 400);
    }

    // Rezervasyonu "confirmed" olarak güncelle
    $reservation->status = 'confirmed';
    $reservation->save();

    // ReservationItems'ları al
    $reservationItems = ReservationItem::where('reservation_id', $reservation->id)->get();

    if ($reservationItems->isEmpty()) {
        return response()->json(['error' => 'No seats found for this reservation'], 400);
    }

    // Her koltuk için bilet oluştur
    foreach ($reservationItems as $item) {
        // Benzersiz bir ticket_code üret
        $ticketCode = 'TICKET-' . strtoupper(uniqid());

        // Bilet oluştur
        \App\Models\Ticket::create([
            'reservation_id' => $reservation->id,
            'seat_id' => $item->seat_id,
            'ticket_code' => $ticketCode,
            'status' => 'active',
        ]);

        // Koltuğu 'sold' durumuna getir
        $seat = $item->seat;
        $seat->status = 'sold';
        $seat->save();
    }

    return response()->json([
        'message' => 'Reservation confirmed successfully and tickets generated!',
        'reservation' => $reservation,
    ]);
}



public function destroy($id)
{
    // Kullanıcının kimliğini al
    $user = Auth::user();

    // Rezervasyonu kullanıcıya göre bul
    $reservation = Reservation::where('user_id', $user->id)->find($id);

    if (!$reservation) {
        return response()->json(['error' => 'Reservation not found'], 404);
    }

    // Etkinlik başlangıç tarihini al
    $event = $reservation->event; // Rezervasyon ile ilişkili etkinlik
    $eventStartTime = Carbon::parse($event->start_date); // start_date'i Carbon nesnesine çeviriyoruz

    // Etkinlik başlangıcına 24 saatten az kaldığında iptal işlemi yapılmamalı
    $hoursUntilEvent = now()->diffInHours($eventStartTime);

    // Eğer etkinlik başlangıcına 24 saatten az kalmışsa iptal yapılamaz
    if ($hoursUntilEvent <= 24) {
        return response()->json(['error' => "Reservation cannot be cancelled less than 24 hours before event. Only $hoursUntilEvent hours left."], 400);
    }

    // Eğer rezervasyon 'confirmed' durumundaysa, ticket'ları iptal et
    if ($reservation->status == 'confirmed') {
        // Biletleri iptal et (status değiştir)
        $tickets = \App\Models\Ticket::where('reservation_id', $reservation->id)->get();
        
        foreach ($tickets as $ticket) {
            // Bilet durumunu 'cancelled' yap
            $ticket->status = 'cancelled';
            $ticket->save();
        }

        // Koltukları 'available' durumuna getirelim
        foreach ($reservation->reservationItems as $reservationItem) {
            $seat = $reservationItem->seat;
            $seat->status = 'available';
            $seat->save();
        }
    }

    // Rezervasyonu sil
    $reservation->delete();

    return response()->json(['message' => 'Reservation cancelled successfully']);
}




public function releaseExpiredReservations()
{
    $expiredReservations = Reservation::where('status', 'pending')
        ->where('expires_at', '<', Carbon::now()) // Geçmişteki rezervasyonlar
        ->get();

    foreach ($expiredReservations as $reservation) {
        // Rezervasyon süresi dolmuşsa, ilgili rezervasyonun koltuklarını serbest bırak
        foreach ($reservation->reservationItems as $item) {
            $seat = $item->seat;
            if ($seat->status == 'reserved') {
                $seat->status = 'available'; // Koltuğu serbest bırak
                $seat->save();
            }
        }

        // Rezervasyonu sil (isteğe bağlı)
        $reservation->delete();
    }

    return response()->json(['message' => 'Expired reservations processed and seats released.']);
}


}
