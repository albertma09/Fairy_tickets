<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Session;
use App\Models\Event;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;

class SessionController extends Controller
{
   public function showCreateForm($eventId)
    {
         $session = Event::find($eventId);
        return view('sessions.create', ['event' => $event]);
    }

   public function showSessionsByPromotor($id)
   {
       
       try {
           Log::info("Llamada al metodo PromotorController.getSessionsByPromotor");
           $sessions = Session::getAllSessionsByPromotor($id);
           
           return view('sessions.mostrar', ['sessions' => $sessions]);
           
       } catch (Exception $e) {
           Log::debug($e->getMessage());
       }
   }
}
