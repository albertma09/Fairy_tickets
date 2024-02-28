<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Event;

use App\Models\Session;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SessionTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_session_component_rendered_correctly()
    {
        
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);

        
        $event = Event::factory(1)->hasSessions(1)->create()->first();

        

        $session = $event->sessions->first();

        $response = $this->get('/sesiones/'.$event->user_id);

        $response->assertStatus(200);
        

        $response->assertSee($event->name);

        $fecha = Carbon::parse($session->date);
        $nombreMes = $fecha->monthName;
        $response->assertSee(date('d', strtotime($session->date)));
        $response->assertSee(ucfirst(substr($nombreMes, 0, 3)));
        $response->assertSee(date('Y', strtotime($session->date)));
        
    }
}
