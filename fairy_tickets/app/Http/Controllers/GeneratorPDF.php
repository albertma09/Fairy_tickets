<?php

namespace App\Http\Controllers;

use DateTime;
use Exception;
use App\Models\Event;
use App\Models\Ticket;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class GeneratorPDF extends Controller
{


    public function generatePDF($session_id, $email)
    {
        $tickets = Ticket::getTicketsInformation($session_id, $email);
        $html = '';

        foreach ($tickets as $ticket) {
            $codigoQR = base64_encode(QrCode::format('svg')->size(200)->errorCorrection('H')->generate($ticket->id));
            $date = $ticket->date;
            $dateObj = new DateTime($date);


            $day = $dateObj->format('d');
            $month = $dateObj->format('m');
            $year = $dateObj->format('Y');
            $html .= view('components.ticket-pdf', ['codigoQR' => $codigoQR, 'ticket' => $ticket, 'day' => $day, 'month' => $month, 'year' => $year])->render();
        }

        $pdf = Pdf::loadHtml($html);



        return $pdf->stream('ticket.pdf');
    }

    public static function sendPdfEmail($ownerEmail, $sessionID)
    {

        try {
            Log::info('Llamada al método GeneratorPDF.sendPdfEmail');
            $data = Event::getEventBySessionId($sessionID);

            $event = $data[0];
            $event_id = $event->id;

            Mail::send('email.email-pdf', ['session_id' => $sessionID, 'email' => $ownerEmail, 'event' => $event, 'event_id' => $event_id], function ($message) use($ownerEmail) {
                $message->to($ownerEmail)
                    ->subject('Compra tickets');
            });



            return redirect()->route('home.index');
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
