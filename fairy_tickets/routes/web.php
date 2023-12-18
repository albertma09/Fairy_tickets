<?php

use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('home.index');
})->name('index');

Route::get('/home',[EventController::class,'index'])->name('home.index');

Route::post('/home',[EventController::class,'searchByRandomItem'])->name('home.index');

Route::fallback(function(){
    return ('Opps!!');
});
