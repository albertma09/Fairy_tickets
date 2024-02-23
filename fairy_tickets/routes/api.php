<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Providers\AppServiceProvider;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });



Route::prefix('v1')->as('v1:')->group(static function (): void {
    Route::post('/login', [ApiController::class, 'login'])->name('login');
    Route::post('/logout', [ApiController::class, 'logout'])->name('logout');


    Route::get('/verificar-ticket/{ticket_id}', [APIController::class, 'verificarTicket']);
});