<?php

namespace App\Http\Controllers;

use App\Models\Seat;
use Illuminate\Http\Request;

class SeatController extends Controller
{
    public function blockSeats(Request $request)
    {
        // Geçerli gelen seat ID'lerini al
        $seatIds = $request->input('seat_ids'); // JSON array formatında

        // Koltukları bul
        $seats = Seat::whereIn('id', $seatIds)->get();

        // Eğer hiç koltuk bulunmazsa hata döndür
        if ($seats->isEmpty()) {
            return response()->json(['error' => 'No seats found'], 404);
        }

        // Koltukları güncelle (status'ü 'blocked' olarak)
        foreach ($seats as $seat) {
            $seat->status = 'blocked';
            $seat->save();
        }

        return response()->json(['message' => 'Seats successfully blocked']);
    }

    public function releaseSeats(Request $request)
{
    // Geçerli gelen seat ID'lerini al
    $seatIds = $request->input('seat_ids'); // JSON array formatında

    // Koltukları bul
    $seats = Seat::whereIn('id', $seatIds)->where('status', 'blocked')->get();

    // Eğer hiç bloklanmış koltuk bulunmazsa hata döndür
    if ($seats->isEmpty()) {
        return response()->json(['error' => 'No blocked seats found'], 404);
    }

    // Koltukları güncelle (status'ü 'available' olarak)
    foreach ($seats as $seat) {
        $seat->status = 'available';
        $seat->save();
    }

    return response()->json(['message' => 'Seats successfully released']);
}

}
