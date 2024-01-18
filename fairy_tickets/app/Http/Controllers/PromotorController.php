<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class PromotorController extends Controller
{
    public function mostrarPromotor($userId)
    {
       

        $events = Event::getEventsByUserId($userId);
        
        return view('home.promotor', ['events' => $events]);
    }
}
