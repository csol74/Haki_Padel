<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Ejecutar los seeders en orden
        $this->call([
            AdminUserSeeder::class,  // Primero el admin
            ProfesorSeeder::class,   // Luego los profesores
            CanchaSeeder::class,     // Luego las canchas
            TorneoSeeder::class,     // Finalmente los torneos
        ]);

        // También puedes mantener el usuario de prueba si lo necesitas
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
