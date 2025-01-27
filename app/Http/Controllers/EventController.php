<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /**
     * Etkinliklerin tümünü listele.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Etkinlikleri almak için 'Event' modelini kullanıyoruz.
        $events = Event::with('venue')->get(); // venues ilişkisini de yüklüyoruz

        return response()->json($events);
    }

    public function show($id)
{
    // Belirtilen ID'ye sahip etkinliği bul
    $event = Event::with('venue')->find($id);

    // Eğer etkinlik bulunamazsa 404 döndür
    if (!$event) {
        return response()->json(['message' => 'Event not found'], 404);
    }

    // Etkinlik bilgilerini döndür
    return response()->json($event, 200);
}

public function store(Request $request)
{
    // Kullanıcı oturum açmamışsa hata döndür
    if (!Auth::check()) {
        return response()->json(['error' => 'You must be logged in to create an event.'], 401);
    }

    // Kullanıcının admin olup olmadığını kontrol et
    if (Auth::user()->role !== 'admin') {
        return response()->json(['error' => 'You are not authorized to create events.'], 403);
    }

    // Validasyon: Gerekli tüm alanların kontrol edilmesi
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'venue_id' => 'required|exists:venues,id',
        'start_date' => 'required|date|after:today',
        'end_date' => 'required|date|after:start_date',
        'status' => 'required|string|in:active,inactive',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    // Yeni etkinliği veritabanına kaydet
    $event = Event::create([
        'name' => $request->name,
        'description' => $request->description,
        'venue_id' => $request->venue_id,
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
        'status' => $request->status,
    ]);

    // Başarıyla kaydedildiğini belirten cevap
    return response()->json([
        'message' => 'Event created successfully',
        'event' => $event,
    ], 201);
}

public function update(Request $request, $id)
{
    // Kullanıcı kontrolü: Admin olup olmadığını kontrol et
    if (Auth::user()->role !== 'admin') {
        return response()->json(['error' => 'You do not have permission to update this event.'], 403);
    }

    // Etkinlik bulma
    $event = Event::find($id);

    // Eğer etkinlik bulunamazsa hata döndür
    if (!$event) {
        return response()->json(['error' => 'Event not found.'], 404);
    }

    // Verileri doğrula
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'venue_id' => 'required|exists:venues,id',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'status' => 'required|in:active,inactive',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    // Veriyi güncelle
    $event->update([
        'name' => $request->name,
        'description' => $request->description,
        'venue_id' => $request->venue_id,
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
        'status' => $request->status,
    ]);

    // Başarılı yanıt döndür
    return response()->json([
        'message' => 'Event updated successfully',
        'event' => $event,
    ]);
}

public function destroy($id)
{
    // Kullanıcı kontrolü: Admin olup olmadığını kontrol et
    if (Auth::user()->role !== 'admin') {
        return response()->json(['error' => 'You do not have permission to delete this event.'], 403);
    }

    // Etkinlik bulma
    $event = Event::find($id);

    // Eğer etkinlik bulunamazsa hata döndür
    if (!$event) {
        return response()->json(['error' => 'Event not found.'], 404);
    }

    // Etkinliği sil
    $event->delete();

    // Başarılı yanıt döndür
    return response()->json([
        'message' => 'Event deleted successfully',
    ]);
}

public function getEventSeats($id)
{
    // Etkinliği bul
    $event = Event::find($id);

    // Eğer etkinlik bulunamazsa hata döndür
    if (!$event) {
        return response()->json(['error' => 'Event not found'], 404);
    }

    // Etkinliğe ait koltukları al
    $seats = $event->seats;

    // Koltukları döndür
    return response()->json(['seats' => $seats]);
}



}
