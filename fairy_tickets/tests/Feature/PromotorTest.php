<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Event;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PromotorTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_show_promotor_view_rendered_correctly(): void
    {
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);

        $userEvents = Event::factory(2)->hasSessions(1)->create([
            'user_id' => $user->id,
        ]);
        
        $response = $this->get('/promotor/'.$user->id);

        $response->assertStatus(200);

        

        
        $response->assertSee('Bienvenido/a, ' . $user->name);

       
        $response->assertSee('Esta es tu sección privada de Fairy Tickets');

        $response->assertSee('Añadir evento');
        $response->assertSee('Mis sesiones');

        foreach ($userEvents as $event) {
            $response->assertSee($event->name);
            $response->assertSee($event->image); 
        }
    }

    
}
