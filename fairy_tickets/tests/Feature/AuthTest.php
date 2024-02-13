<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
   
    public function test_user_can_login_with_correct_credentials()
    {
        
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect('/promotor/'.$user->id);

    }

    public function test_user_cannot_login_with_incorrect_credentials()
    {

        $response = $this->post('/login', [
            'email' => 'wrong@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHas('error');
    }

    public function test_reset_password_page_is_rendered_correctly()
    {

        $token = 'random_token';
        $email = 'test@example.com';

        $response = $this->get(route('formulario-actualizar-contrasenia', ['token' => $token, 'email' => $email]));

        $response->assertStatus(200)
                 ->assertSee('Actualizar Contraseña')
                 ->assertSee('Contraseña')
                 ->assertSee('Confirmar Contraseña')
                 ->assertSee('Cambiar Contraseña');
    }

    
    public function test_reset_password_form_submits_correctly()
    {
        
        $token = 'random_token';
        $email = 'test@example.com';

       
        $response = $this->post(route('actualizar-contrasenia'), [
            'token' => $token,
            'email' => $email,
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ]);

        
        $response->assertRedirect('/'); 
    }

    public function test_password_recovery_page_is_rendered_correctly()
    {
       
        $response = $this->get(route('formulario-recuperar-contrasenia'));

        
        $response->assertStatus(200)
                 ->assertSee('Recuperar Contraseña')
                 ->assertSee('email')
                 ->assertSee('Recuperar contraseña');
    }

    
    public function test_password_recovery_form_submits_correctly()
    {
       
        $email = 'test@example.com';

    
        $response = $this->post(route('enviar-recuperacion'), [
            'email' => $email,
        ]);

        
        $response->assertRedirect('/');

    }


}
