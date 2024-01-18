<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    public function index(): View
    {
        
            $categories=Category::getTotalCategories();
            $events=Category::getCategorizablesCards();
            // dd(gettype($categories));
            return view('home.index', ['events'=>$events,'categories' =>$categories]);
        
    }
}
