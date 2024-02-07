<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class OpinionsController extends Controller
{
    //
    public function showPage($token)
{
    // Decodifica el token
    try {
        $cliente = json_decode(Crypt::decryptString($token), true);
    } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
        abort(404); // Token inválido
    }

    // Verifica si la información del cliente es válida y muestra la pantalla deseada
    return view('opinions.user-opinion');
}
}
