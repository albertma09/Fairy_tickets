<?php


use App\Http\Controllers\PromotorController;
use App\Models\Category;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\GeneratorPDF;
use App\Http\Controllers\OpinionsController;
use App\Http\Controllers\PaymentController;

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

    // Eventos
    Route::get(
        '/manage/new-event',
        [EventController::class, 'showCreateForm']
    )->name('events.create');

    Route::post(
        '/manage/new-event',
        [EventController::class, 'store']
    )->name('events.store');

    Route::get(
        '/manage/update-event/{id}',
        [EventController::class, 'showUpdateForm']
    )->name('events.update');

    Route::post(
        '/manage/update-event',
        [EventController::class, 'edit']
    )->name('events.edit');
    // Sesiones
    Route::get(
        '/sesiones/{id}',
        [SessionController::class, 'showSessionsByPromotor']
    )->name('sessions.mostrar');

    Route::get('session/{session_id}',[SessionController::class, 'generateCSV'])->name('generar.csv');

    Route::get(
        '/manage/{eventId}/new-session',
        [SessionController::class, 'showCreateForm']
    )->name('sessions.create');

    Route::post(
        '/manage/new-session',
        [SessionController::class, 'store']
    )->name('sessions.store');

    // Direcciones
    Route::post(
        '/manage/new-location',
        [LocationController::class, 'store']
    )->name('location.store');

});

// Formulario donde el usuario pone su email para que le enviemos el email de resetear la contraseña
Route::get('/formulario-recuperar-contrasenia', [AuthController::class, 'formularioRecuperarContrasenia'])->name('formulario-recuperar-contrasenia');

// Función que se ejecuta al enviar el formulario y que enviará el email al usuario
Route::post('/enviar-recuperar-contrasenia', [AuthController::class, 'enviarRecuperarContrasenia'])->name('enviar-recuperacion');

// Formulario donde se modificará la contraseña
Route::get('/reiniciar-contrasenia/{token}/{email}', [AuthController::class, 'formularioActualizacion'])->name('formulario-actualizar-contrasenia');

// Función que actualiza la contraseña del usuario
Route::post('/actualizar-contrasenia', [AuthController::class, 'actualizarContrasenia'])->name('actualizar-contrasenia');

// Rutas relacionadas con los eventos
Route::get('/home', [CategoryController::class, 'index'])->name('home.index');

Route::post('/events', [EventController::class, 'searchBySearchingItem'])->name('search.index');

// Route::post('/events/categories', [EventController::class, 'searchByCategoryItem'])->name('searchByCategory.index');

Route::get('/events/categories/{name}', [EventController::class, 'searchByCategoryItem'])->name('searchByCategory.index');

Route::get('/detalles-evento/{id}', [EventController::class, 'mostrarEvento'])->name('events.mostrar');

Route::get('/buyTicket/{session_id}/{email}', [GeneratorPDF::class, 'generatePDF'])->name('buy-ticket');

Route::get('/sendPdfEmail', [GeneratorPDF::class, 'sendPdfEmail'])->name('send-pfd-email');

Route::get('/opinion/{token}',[OpinionsController::class, 'showPage'])->name('user-opinion');

Route::post('/createOpinion',[OpinionsController::class, 'createOpinion'])->name('create-opinion');

Route::post('/close-sale',[SessionController::class, 'closeSale'])->name('close.sale');

//pagos
Route::post('/summary-purchase',[PaymentController::class,'getSessionDataForPayment'])->name('payment.index');
Route::post('/pay',[PaymentController::class,'paymentRedsys'])->name('payment.payToRedsys');
Route::get('/confirm-purchase',[PaymentController::class,'responseRedsys'])->name('payment.confirmation');


Route::fallback(function () {
    return ('Opps!!');
});

// Rutas para peticiones asíncronas
Route::get('/Location/fetch', [LocationController::class, 'showLocation']);
