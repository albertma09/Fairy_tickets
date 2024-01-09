<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class EventController extends Controller
{
    public function index(): View
    {
        return view('home.index', ['events' => Event::getAllEvents()]);
    }

    // public function index(): View
    // {
    //     return view('home.index', ['events' => Event::all()]);
    // }

    public function searchBySearchingItem(Request $request): View
    {
        $item = $request->input('search-input');
        $events = Event::getEventsBySearching($item);
        dd($events);      
        return view('search.index',['events' => $events]);
    }

    public function searchByCategoryItem(Request $request):View
    {   //dd($request);
        $item = $request->input('category-item');
        //dd($item);
        $events = Event::getEventsByCategory($item);
        //dd($events);
        return view('search.index',['events' => $events]);
    }

}
