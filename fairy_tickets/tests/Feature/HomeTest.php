<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Event;
use App\Models\Category;
use App\Models\Location;
use Illuminate\Support\Facades\App;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HomeTest extends TestCase
{
    use RefreshDatabase;

    public function test_when_navigate_to_index_expect_redirect_to_home_index(): void
    {
        $response = $this->get('/');
        $response->assertFound(); // assertStatus(302); 
        $response->assertRedirectToRoute('home.index');
    }

    public function test_when_redirect_to_home_show_title()
    {
        $response = $this->get('/home');
        $response->assertSeeText('Próximos eventos');
        $response->assertOk();
    }

    public function test_when_no_exist_events_to_home_show_title()
    {
        $response = $this->get('/home');
        $response->assertSeeText('No hay eventos');
        $response->assertOk();
    }

    public function test_when_search_by_eventName_redirects_to_events_page_and_show_the_event()
    {
        $category = Category::factory()->create();
        $location = Location::factory()->create();
        $user = User::factory()->create();
        $event = Event::factory()->for($category)->for($location)->for($user)->create();

        // dd($event->name);
        $this->assertDatabaseHas('events', ['name' => $event->name]);

        // Realizar la búsqueda y verificar la redirección
        $response = $this->post('/events', ['search-input' => $event->name]);

        // Verificar que se redirige correctamente a la página de eventos
        $response->assertSeeText($event->event);
        $response->assertOk();
    }

    public function test_when_search_by_eventName_with_upper_case_redirects_to_events_page_and_show_the_event()
    {
        $category = Category::factory()->create();
        $location = Location::factory()->create();
        $user = User::factory()->create();
        $event = Event::factory()->for($category)->for($location)->for($user)->create();

        $this->assertDatabaseHas('events', ['name' => $event->name]);

        $response = $this->post('/events', ['search-input' => strtoupper($event->name)]);

        $response->assertSeeText($event->event);
        $response->assertOk();

    }

    public function test_when_search_by_eventName_with_camel_case_redirects_to_events_page_and_show_the_event()
    {
        $category = Category::factory()->create();
        $location = Location::factory()->create();
        $user = User::factory()->create();
        $event = Event::factory()->for($category)->for($location)->for($user)->create();

        $this->assertDatabaseHas('events', ['name' => $event->name]);

        $response = $this->post('/events', ['search-input' => ucwords($event->name,' ')]);

        $response->assertSeeText($event->event);
        $response->assertOk();

    }

    public function test_when_search_by_eventName_with_spaces_at_the_beginning_and_end_and_redirects_to_events_page_and_show_the_event()
    {
        $category = Category::factory()->create();
        $location = Location::factory()->create();
        $user = User::factory()->create();
        $event = Event::factory()->for($category)->for($location)->for($user)->create();

        $this->assertDatabaseHas('events', ['name' => $event->name]);
        
        $response = $this->post('/events', ['search-input' =>' '. $event->name .' ']);

        $response->assertSeeText($event->event);
        $response->assertOk();

    }

    public function test_when_search_with_accents_at_event_name_and_redirects_to_events_page_and_show_the_event()
    {
        $category = Category::factory()->create();
        $location = Location::factory()->create();
        $user = User::factory()->create();
        $event = Event::factory()->for($category)->for($location)->for($user)->create();

        $this->assertDatabaseHas('events', ['name' => $event->name]);
        $original = array('a','e','i','o','u','A','E','I','O','U');
        $replace = array('á','é','í','ó','ú','Á','É','Í','Ó','Ú');
        
        $response = $this->post('/events', ['search-input' => str_replace($original,$replace,$event->name)]);

        $response->assertSeeText($event->event);
        $response->assertOk();

    }

    public function test_when_search_withOut_parameter_and_redirects_to_events_page_and_show_all_event()
    {
        $category = Category::factory()->create();
        $location = Location::factory()->create();
        $user = User::factory()->create();
        $event = Event::factory()->for($category)->for($location)->for($user)->create();

        $response = $this->post('/events', ['search-input' => null]);

        $response->assertSee($event->event);
        $response->assertOk();

    }

    public function test_when_search_with_incomplete_parameter_name_and_redirects_to_events_page_and_show_all_event()
    {
        $category = Category::factory()->create();
        $location = Location::factory()->create();
        $user = User::factory()->create();
        $event = Event::factory()->for($category)->for($location)->for($user)->create();

        $response = $this->post('/events', ['search-input' => substr($event->name,0,4)]);

        $response->assertSee($event->event);
        $response->assertOk();

    }

    public function test_when_search_with_blank_parameter_and_redirects_to_events_page_and_show_all_event()
    {
        $category = Category::factory()->create();
        $location = Location::factory()->create();
        $user = User::factory()->create();
        $event = Event::factory()->for($category)->for($location)->for($user)->create();

        $response = $this->post('/events', ['search-input' => ' ']);

        $response->assertSee($event->event);
        $response->assertOk();

    }

    public function test_when_search_by_location_redirects_to_events_page_and_show_the_event()
    {
        $category = Category::factory()->create();
        $location = Location::factory()->create();
        $user = User::factory()->create();
        $event = Event::factory()->for($category)->for($location)->for($user)->create();

        $this->assertDatabaseHas('locations', ['name' => $location->name]);
        
        $response = $this->post('/events', ['search-input' =>$location->name]);
        
        $response->assertSeeText($event->event);
        $response->assertOk();

    }

    public function test_when_search_with_incomplete_location_name_and_redirects_to_events_page_and_show_all_event()
    {
        $category = Category::factory()->create();
        $location = Location::factory()->create();
        $user = User::factory()->create();
        $event = Event::factory()->for($category)->for($location)->for($user)->create();

        $this->assertDatabaseHas('locations', ['name' => $location->name]);

        $response = $this->post('/events', ['search-input' => substr($location->name,0,4)]);

        $response->assertSee($event->event);
        $response->assertOk();

    }

    public function test_when_search_with_accents_at_location_name_and_redirects_to_events_page_and_show_the_event()
    {
        $category = Category::factory()->create();
        $location = Location::factory()->create();
        $user = User::factory()->create();
        $event = Event::factory()->for($category)->for($location)->for($user)->create();

        $this->assertDatabaseHas('locations', ['name' => $location->name]);
        $original = array('a','e','i','o','u','A','E','I','O','U');
        $replace = array('á','é','í','ó','ú','Á','É','Í','Ó','Ú');
        
        $response = $this->post('/events', ['search-input' => str_replace($original,$replace,$location->name)]);

        $response->assertSeeText($event->event);
        $response->assertOk();

    }

    public function test_when_search_by_city_redirects_to_events_page_and_show_the_event()
    {
        $category = Category::factory()->create();
        $location = Location::factory()->create();
        $user = User::factory()->create();
        $event = Event::factory()->for($category)->for($location)->for($user)->create();
        
        $this->assertDatabaseHas('locations', ['city' => $location->city]);
        
        $response = $this->post('/events', ['search-input' =>$location->city]);
        
        $response->assertSeeText($event->event);
        $response->assertOk();

    }

    public function test_when_search_with_incomplete_city_name_and_redirects_to_events_page_and_show_all_event()
    {
        $category = Category::factory()->create();
        $location = Location::factory()->create();
        $user = User::factory()->create();
        $event = Event::factory()->for($category)->for($location)->for($user)->create();

        $this->assertDatabaseHas('locations', ['city' => $location->city]);

        $response = $this->post('/events', ['search-input' => substr($location->city,0,4)]);

        $response->assertSee($event->event);
        $response->assertOk();

    }

    public function test_when_search_with_accents_at_city_name_and_redirects_to_events_page_and_show_the_event()
    {
        $category = Category::factory()->create();
        $location = Location::factory()->create();
        $user = User::factory()->create();
        $event = Event::factory()->for($category)->for($location)->for($user)->create();

        $this->assertDatabaseHas('locations', ['city' => $location->city]);
        $original = array('a','e','i','o','u','A','E','I','O','U');
        $replace = array('á','é','í','ó','ú','Á','É','Í','Ó','Ú');
        
        $response = $this->post('/events', ['search-input' => str_replace($original,$replace,$location->city)]);

        $response->assertSeeText($event->event);
        $response->assertOk();

    }

    public function test_when_navigate_to_login_page()
    {
        
        $response = $this->get('/login');
        $response->assertSeeTextInOrder(['Iniciar Sesión','Iniciar Sesión','¿Olvidaste la contraseña?','Volver']);
        $response->assertOk();
    }



}

