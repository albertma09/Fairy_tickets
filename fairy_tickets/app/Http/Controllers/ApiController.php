<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;


class ApiController extends Controller
{
    public function login(Request $request)
    {

        try{
           

            $session = DB::table('sessions')
            ->select('code')
            ->where('code', $request->input('code'))
            ->first();
    
            if (!$session) {
               return response()->json(['email' => 'The provided credentials are incorrect.']);
                    
                
            }
    
            // if (Hash::check($session->code)) { 
            //     return response()->json(['email' => 'The provided credentials are incorrect.']);
            // }
    
            $token = $session->createToken('api-token')->plainTextToken;
    
            return response()->json(['token' => $token]);
        }catch(Exception $ex){
            Log::error($ex->getMessage());
        }
        
    }
}
