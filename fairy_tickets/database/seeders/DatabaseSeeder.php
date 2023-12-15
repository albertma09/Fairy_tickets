<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Event;
use App\Models\Category;
use App\Models\Location;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        if ($this->command->confirm('Desea refrescar la base de datos?', true)) {
            $this->command->call('migrate:refresh');
            $this->command->info("Se ha reconstruido la base de datos");
        }

        $eventNum = max((int) $this->command->ask('Introduce el número de Eventos a crear', 20), 1);

        Category::factory(5)->create();
        Location::factory(3)->create();
        Event::factory($eventNum)->create();
        /*Event::factory($eventNum)->create()->each(function($event){
            Category::find(random_int(1, 5))->for($event)->create();
            Location::find(random_int(1, 3))->for($event)->create();
        });*/
        $this->command->info("Se han creado $eventNum eventos");
    }
}
