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
        try{
            Log::info('Llamada al método CategoryController.index ');
            $categories=Category::getTotalCategories();
            $events=Category::getCategorizablesCards();
            return view('home.index', ['events'=>$events,'categories' =>$categories]);
        }catch(Exception $e){
            Log::debug($e->getMessage());
        }
    }
}
