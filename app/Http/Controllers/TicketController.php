<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;


class TicketController extends Controller
{
    /**
     * Kullanıcının tüm biletlerini döndür.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Kimlik doğrulaması yapılmış kullanıcıyı al
        $user = Auth::user();

        // Kullanıcının biletlerini rezervasyonlarına göre al
        $tickets = Ticket::whereHas('reservation', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with(['seat', 'reservation'])->get();

        return response()->json($tickets);
    }

    public function show($id)
    {
        // İlgili bileti bul
        $ticket = Ticket::with(['reservation', 'seat'])->find($id);

        if (!$ticket) {
            return response()->json(['error' => 'Ticket not found'], 404);
        }

        return response()->json($ticket, 200);
    }

    public function download($id)
    {
        // Bilet bilgilerini al
        $ticket = Ticket::with(['reservation', 'seat'])->find($id);

        if (!$ticket) {
            return response()->json(['error' => 'Ticket not found'], 404);
        }

        // PDF için veri hazırlama
        $data = [
            'ticket' => $ticket,
            'reservation' => $ticket->reservation,
            'seat' => $ticket->seat,
        ];

        // PDF'i oluştur
        $pdf = Pdf::loadView('tickets.pdf', $data);

        // İndirilebilir PDF döndür
        return $pdf->download("ticket_{$ticket->id}.pdf");
    }

    public function transfer(Request $request, $id)
{
    // Doğrulama: Hedef kullanıcıyı al
    $validated = $request->validate([
        'recipient_email' => 'required|email|exists:users,email', // Alıcı kullanıcı mevcut olmalı
    ]);

    // Mevcut kullanıcı ve bilet
    $user = Auth::user();
    $ticket = Ticket::where('id', $id)
                    ->whereHas('reservation', function ($query) use ($user) {
                        $query->where('user_id', $user->id); // Sadece bu kullanıcının bileti
                    })->first();

    if (!$ticket) {
        return response()->json(['error' => 'Ticket not found or you do not have permission to transfer it'], 404);
    }

    // Bilet durumu kontrolü
    if ($ticket->status !== 'active') {
        return response()->json(['error' => 'Only active tickets can be transferred'], 400);
    }

    // Alıcıyı bul
    $recipient = User::where('email', $validated['recipient_email'])->first();

    if (!$recipient) {
        return response()->json(['error' => 'Recipient user not found'], 404);
    }

    // Transfer işlemi
    $ticket->reservation->user_id = $recipient->id; // Rezervasyonu yeni kullanıcıya ata
    $ticket->reservation->save();

    $ticket->status = 'transferred'; // Bilet durumunu güncelle
    $ticket->save();

    return response()->json([
        'message' => 'Ticket successfully transferred!',
        'ticket' => $ticket,
    ]);
}



}
