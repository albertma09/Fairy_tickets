<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Opinion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;

class OpinionsController extends Controller
{
    //
    public function showPage($token)
{
    // Decodifica el token
    try {
        $cliente = json_decode(Crypt::decryptString($token), true);
        // dd($cliente);
        $purchase_id = $cliente['id'];
        
        $opinion = Opinion::where('purchase_id', $purchase_id)->get();
        
    } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
        abort(404); // Token inválido
    }

    // Verifica si la información del cliente es válida y muestra la pantalla deseada
    return view('opinions.user-opinion', ['token'=> $token, 'id'=> $cliente, 'opinion'=>$opinion]);
}

    public function createOpinion( Request $request){


        

            $validatedData = $request->validate([
                'purchase_id' => 'required|numeric',
                'name' => 'required|max:100',
                'face_rating' => 'required|numeric|min:1|max:3',
                'star_rating' => 'required|numeric|min:1|max:5',
                'title' => 'required|max:100',
                'comment' => 'required',
            ]);
    
           
            Opinion::create($validatedData);

            return back();

        
        
        

    }
}
