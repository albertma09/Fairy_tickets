<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PromotorController;
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

// Todas las rutas que necesitan autenticación de usuario
Route::middleware(['auth.redirect'])->group(function () {
    // Home del promotor
    Route::get('/promotor/{userId}', [PromotorController::class, 'mostrarPromotor'])->name('promotor');

    // Creación de nuevos eventos
    Route::get('/promotor/new-event', function () {
        return view('events.create');
    })->name('events.create');
});

// Formulario donde el usuario pone su email para que le enviemos el email de resetear la contraseña
Route::get('/formulario-recuperar-contrasenia', [AuthController::class, 'formularioRecuperarContrasenia'])->name('formulario-recuperar-contrasenia');

// Función que se ejecuta al enviar el formulario y que enviará el email al usuario
Route::post('/enviar-recuperar-contrasenia', [AuthController::class, 'enviarRecuperarContrasenia'])->name('enviar-recuperacion');

// Formulario donde se modificará la contraseña
Route::get('/reiniciar-contrasenia/{token}/{email}', [AuthController::class, 'formularioActualizacion'])->name('formulario-actualizar-contrasenia');

// Función que actualiza la contraseña del usuario
Route::post('/actualizar-contrasenia', [AuthController::class, 'actualizarContrasenia'])->name('actualizar-contrasenia');


Route::get('/home', [EventController::class, 'index'])->name('home.index');

Route::post('/events', [EventController::class, 'searchBySearchingItem'])->name('search.index');

Route::post('/events/categories', [EventController::class, 'searchByCategoryItem'])->name('searchByCategory.index');

Route::get('/detalles-evento/{id}', [EventController::class, 'mostrarEvento'])->name('events.mostrar');

Route::fallback(function () {
    return ('Opps!!');
});
