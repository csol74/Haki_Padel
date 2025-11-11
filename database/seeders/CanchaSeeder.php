<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cancha; // 👈 Esta línea es la importante

class CanchaSeeder extends Seeder
{
    public function run(): void
    {
        Cancha::create([
            'nombre' => 'Cancha Central',
            'numero' => 1,
            'tipo' => 'premium',
            'precio_hora' => 35000,
            'estado' => 'disponible'
        ]);

        Cancha::create([
            'nombre' => 'Cancha Norte',
            'numero' => 2,
            'tipo' => 'estandar',
            'precio_hora' => 25000,
            'estado' => 'disponible'
        ]);

        Cancha::create([
            'nombre' => 'Cancha Sur',
            'numero' => 3,
            'tipo' => 'vip',
            'precio_hora' => 40000,
            'estado' => 'ocupada'
        ]);
    }
}
