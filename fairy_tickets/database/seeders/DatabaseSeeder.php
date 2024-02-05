<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Event;
use App\Models\Category;
use App\Models\Location;
use App\Models\Purchase;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

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
            $this->command->call('migrate:fresh');
            $this->command->info("Se ha reconstruido la base de datos");
        }

        $eventNum = max((int) $this->command->ask('Introduce el número de Eventos a crear', 100), 1);

        Category::factory(10)->create();
        Location::factory(5)->create();

        // Creación de los dos usuarios por defecto
        $seededUsers = User::factory()->seededUsers();
        foreach ($seededUsers as  $userData) {
            User::create($userData);
        }

        Event::factory($eventNum / 2)->hasSessions(1)->create();
        Event::factory($eventNum / 2)->hasSessions(rand(2, 4))->create();

        Purchase::factory(10)->create();

        $this->command->info("Se han creado $eventNum eventos");
    }
}
