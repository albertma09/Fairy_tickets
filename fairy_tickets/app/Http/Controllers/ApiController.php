<?php

namespace App\Http\Controllers;

use App\Models\Session;
use App\Models\Ticket;
use App\Models\Token;
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
           
            $session = Session::getSessionByCode($request->input('code'));
            
    
            if (!$session) {
               return response()->json(['session' => 'el codigo de la session no es valido'],401);
            }
            
            $token = Token::getTokenBySessionId($session->id);
            
    
            return response()->json(['token' => $token, 'session_id' => $session->id]);
        }catch(Exception $ex){
            Log::error($ex->getMessage());
        }
        
    }

    public function logout(Request $request)
    {
        $token =  $token = request()->cookie('Token');
        DB::table('tokens')->where('token', $token)->delete();

        return response()->json(['message' => 'Logout exitoso'], 200);
    }

    public function verificarTicket($ticket_id)
    {

        try{
            $token = request()->cookie('Token');
            $validateToken = Token::verifyToken($token);
    
            if ($validateToken) {
                
                $validateTicket = Ticket::validateTicket($ticket_id, request()->cookie('session_id'));
                if($validateTicket->verified){
                    return response()->json(['error' => 'Este ticket no es valido, ya ha sido verificado'],400);
                }else{
                    $verifyTicket = Ticket::findOrFail($ticket_id);
                    $verifyTicket->verified = true;
                    $verifyTicket->save();

                    if($verifyTicket->name&&$verifyTicket->dni&&$verifyTicket->phone_number){
                        return response()->json(['correct' => 'Ticket valido, verificado con exito', 'nombre' =>$verifyTicket->name, 'dni' => $verifyTicket->dni, 'numero' => $verifyTicket->phone_number ],200);
                    }else{
                        return response()->json(['correct' => 'Ticket valido, verificado con exito'],200);
                    }
                    
                    
                }

                
            } else {
                
                return response()->json(['error' => 'Acceso denegado'], 401);
            }
        }catch(Exception $ex){
            Log::error($ex->getMessage());
            return response()->json(['error' => 'Ha habido un error, posiblemente este ticket no exista o no pertenezca a esta session'],404);
        }
        
    }

}
