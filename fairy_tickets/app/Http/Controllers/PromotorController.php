<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Event;
use App\Models\Session;
use Illuminate\Support\Facades\Log;

class PromotorController extends Controller
{
    public function mostrarPromotor($userId)
    {
        try {
            Log::info("Llamada al metodo PromotorController.mostrarPromotor");
            $events = Event::getEventsByUserId($userId);
            return view('home.promotor', ['events' => $events]);
        } catch (Exception $e) {
            Log::debug($e->getMessage());
        }
    }

    public function getSessionsByPromotor($id)
    {
        
        try {
            Log::info("Llamada al metodo PromotorController.getSessionsByPromotor");
            $sessions = Session::getAllSessionsByPromotor($id);
            
            return view('home.sessions', ['sessions' => $sessions]);
            
        } catch (Exception $e) {
            Log::debug($e->getMessage());
        }
    }
}
