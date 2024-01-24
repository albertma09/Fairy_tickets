<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class HomeTest extends TestCase
{
    use RefreshDatabase;

    public function test_when_navigate_to_index_expect_redirect_to_home_index(): void
    {
        $response = $this->get('/'); 
        $response->assertFound(); // assertStatus(302); 
        $response->assertRedirectToRoute('home.index'); 
    }

    public function test_when_redirect_to_home_show_title(){
        $response = $this->get('/home');
        $response->assertSeeText('Próximos eventos');
        $response->assertOk();
    }

    public function test_when_search_event_redirect_to_events_page(){

        $loc = \App\Models\Location::factory()->create(3);
        $cat = \App\Models\Category::factory()->create(10);
        $event = \App\Models\Event::factory()->create();
        $this->assertDatabaseHas('events',['name' => $event->name]);
        $response = $this->post('/events',$event->name);
        $response->assertSee($event->name);
        $response->assertOk();

    }
    
}
