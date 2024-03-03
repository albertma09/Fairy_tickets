<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Event;
use App\Models\Image;
use App\Models\Session;
use Illuminate\Support\Facades\Log;

class PromotorController extends Controller
{
    public function mostrarPromotor($userId)
    {
        try {
            Log::info("Llamada al metodo PromotorController.mostrarPromotor");
            $events = Event::getEventsByUserId($userId);
            foreach ($events as $event) {
                $event->getMainImage();
            }
            return view('home.promotor', ['events' => $events]);
        } catch (Exception $e) {
            Log::debug($e->getMessage());
        }
    }
}
