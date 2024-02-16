<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Event;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventsTest extends TestCase
{
    
    public function test_show_event_view_rendered_correctly()
    {
       
        $evento = Event::factory(1)->hasSessions(1)->create()->first();

        $locacion = $evento->location;

        // dd($locacion);

        $sesiones = $evento->sessions->first();

        $tickets = $sesiones->ticketTypes->first();

        

        $response = $this->get('/detalles-evento/'.$evento->id);

       
        $response->assertSee($evento->name);
        $response->assertSee($evento->description);
        $response->assertSee($evento->image);

        $response->assertSee($sesiones->date);
        $response->assertSee(\Carbon\Carbon::createFromFormat('H:i:s', $sesiones->hour)->format('H:i'));

        $response->assertSee($locacion->name);
        $response->assertSee($locacion->province);
        $response->assertSee($locacion->city);
        $response->assertSee($locacion->street);
        $response->assertSee($locacion->number);
        $response->assertSee($locacion->cp);

        $response->assertSee($tickets->description);
        $response->assertSee($tickets->price);

    }

    







}
