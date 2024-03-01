<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->as('v1:')->group(static function (): void {
    Route::post('/images', [ImageController::class, 'store'])->name('image.store');
    Route::delete('/images', [ImageController::class, 'destroy'])->name('image.destroy');
    Route::get('/images/{filename}', [ImageController::class, 'show'])->name('image.show');
});
