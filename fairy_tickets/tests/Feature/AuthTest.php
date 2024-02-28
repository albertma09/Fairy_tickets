<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Event;
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

    public function test_authenticated_user_can_access_protected_page()
    {
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);

        
        $response = $this->get('/promotor/'.$user->id);

       
        $response->assertStatus(200);


        $response = $this->get('/manage/new-event');
        $response->assertStatus(200);

        $event = Event::factory(1)->hasSessions(1)->create()->first();

        $response = $this->get('/manage/update-event/'.$event->id);
        $response->assertStatus(200);


        $response = $this->get('/sesiones/'.$event->id);
        $response->assertStatus(200);

        $response = $this->get('/manage/'.$event->id.'/new-session');
        $response->assertStatus(200);
    }

    public function test_unauthenticated_user_is_redirected_to_login_page()
    {
        
        $user = \App\Models\User::factory()->create();
        $response = $this->get('/promotor/'.$user->id);

        
        $response->assertRedirect('/login');

        $response = $this->get('/manage/new-event');
        $response->assertRedirect('/login');

        $event = Event::factory(1)->hasSessions(1)->create()->first();

        $response = $this->get('/manage/update-event/'.$event->id);
        $response->assertRedirect('/login');


        $response = $this->get('/sesiones/'.$event->id);
        $response->assertRedirect('/login');

        $response = $this->get('/manage/'.$event->id.'/new-session');
        $response->assertRedirect('/login');
    }


}
