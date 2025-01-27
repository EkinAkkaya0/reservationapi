<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use Illuminate\Http\Request;

class VenueController extends Controller
{
    public function getVenueSeats($id)
    {
        // Mekânı bul
        $venue = Venue::find($id);

        // Eğer mekân bulunamazsa hata döndür
        if (!$venue) {
            return response()->json(['error' => 'Venue not found'], 404);
        }

        // Mekâna ait koltukları al
        $seats = $venue->seats;

        // Koltukları döndür
        return response()->json(['seats' => $seats]);
    }
}
