<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    /**
     * Show the login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $userId = Auth::user()->id;
            return redirect()->route('promotor', ["userId"=>$userId]);
        }

        return redirect()->route('login')->with('error', 'Las credenciales de login no son correctas');
    }

    /**
     * Handle a logout request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Función que devuelve la vista con el formulario de recuperar contraseña
     *
     * @return response()
     */
    public function formularioRecuperarContrasenia()
    {
        return view('auth.formulario-recuperar-contrasenia');
    }

    /**
     * Función que recibe el email del usuario y en caso de que exista le envía el email de recuperación de contraseña
     *
     * @return response()
     */
    public function enviarRecuperarContrasenia(Request $request)
    {
       
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        
        $token = Str::random(64);
        

        
        DB::table('password_reset_tokens')->where(['email' => $request->email])->delete();

        
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        
        Mail::send('auth.recuperar-contrasenia', ['token' => $token, 'email' => $request->email], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('Recuperar Contraseña');
        });

        return back()->with('message', 'Te hemos enviado un email con las instrucciones para que recuperes tu contraseña');
    }

     /**
     * Función que devuelve la vista con el formulario que actualiza la contraseña
     *
     * @return response()
     */
    public function formularioActualizacion($token, $email)
    {
        return view('auth.formulario-actualizacion', ['token' => $token, 'email' => $email]);
    }

     /**
     * Función que actualiza la contraseña del usuario
     *
     * @return response()
     */
    public function actualizarContrasenia(Request $request)
    {
        
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|string|min:8|regex:/[A-Z]/|REGEX:/[a-z]/|regex:/[!@#$%^&*()_+{}\[\]:;<>,.?~\\-]/|confirmed',
            'password_confirmation' => 'required'
        ]);

        
        $updatePassword = DB::table('password_reset_tokens')
            ->where([
                'email' => $request->email,
                'token' => $request->token
            ])
            ->first();

        
        if (!$updatePassword) {
            return back()->withInput()->with('error', 'Token inválido');
        }

        
        $user = User::where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);


        
        DB::table('password_reset_tokens')->where(['email' => $request->email])->delete();

        
        return redirect('/login')->with('message', 'Tu contraseña se ha cambiado correctamente');
    }
    
}
