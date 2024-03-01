<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    public function index(): View
    {
        try {
            Log::info('Llamada al método CategoryController.index ');
            $categories = Category::getTotalCategories();
            $results = Category::getCategorizablesCards();
            $events = [];
            foreach ($results as $result) {
                $event = new Event([
                    'name' => $result->event,
                ]);
                $event->id = $result->id;
                $event->category = $result->category;
                $event->date = $result->date;
                $event->price = $result->price;
                $event->getMainImage();
                $events[]=$event;
            }
            return view('home.index', ['events' => $events, 'categories' => $categories]);
        } catch (Exception $e) {
            Log::debug($e->getMessage());
        }
    }
}
