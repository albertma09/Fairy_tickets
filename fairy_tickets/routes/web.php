<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\AuthController;
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

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth.redirect'])->group(function () {
    Route::get('/promotor', function () {
        return view('home.promotor');
    });
});


Route::get('/home',[EventController::class,'index'])->name('home.index');

Route::post('/events',[EventController::class,'searchBySearchingItem'])->name('search.index');

Route::post('/events/categories',[EventController::class,'searchByCategoryItem'])->name('search.index');

Route::fallback(function(){
    return ('Opps!!');
});
