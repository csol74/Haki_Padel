<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TorneoSeeder extends Seeder
{
    public function run()
    {
        // Obtener el admin como organizador
        $admin = DB::table('users')->where('role', 'admin')->first();

        if (!$admin) {
            echo "No se encontró un usuario admin. Ejecuta primero AdminUserSeeder\n";
            return;
        }

        $organizadorId = $admin->id;
        echo "Usando admin como organizador: {$admin->name}\n";

        $torneos = [
            [
                'nombre' => 'Copa Hakipadel Verano 2025',
                'descripcion' => 'Torneo de verano con premios increíbles. Participa y demuestra tu habilidad en la cancha. Incluye arbitraje profesional y premios para los 3 primeros lugares. Modalidad mixta, pueden participar parejas de cualquier género.',
                'id_organizador' => $organizadorId,
                'fecha_inicio' => Carbon::now()->addDays(15)->format('Y-m-d'),
                'fecha_fin' => Carbon::now()->addDays(17)->format('Y-m-d'),
                'fecha_inscripcion_limite' => Carbon::now()->addDays(10)->format('Y-m-d'),
                'categoria' => 'mixto',
                'precio_inscripcion' => 50000.00,
                'cantidad_max_participantes' => 16,
                'estado' => 'inscripciones_abiertas',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Torneo Masculino Diciembre',
                'descripcion' => 'Torneo exclusivo masculino. Ambiente competitivo para jugadores que buscan desafíos. Premios y reconocimientos para todos los participantes. Sistema de eliminación directa.',
                'id_organizador' => $organizadorId,
                'fecha_inicio' => Carbon::now()->addDays(20)->format('Y-m-d'),
                'fecha_fin' => Carbon::now()->addDays(21)->format('Y-m-d'),
                'fecha_inscripcion_limite' => Carbon::now()->addDays(15)->format('Y-m-d'),
                'categoria' => 'masculino',
                'precio_inscripcion' => 40000.00,
                'cantidad_max_participantes' => 20,
                'estado' => 'inscripciones_abiertas',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Championship Femenino Hakipadel',
                'descripcion' => 'El torneo más competitivo del año para mujeres. Gran premio y reconocimiento. Formato profesional con arbitraje certificado. ¡Demuestra tu nivel en la cancha!',
                'id_organizador' => $organizadorId,
                'fecha_inicio' => Carbon::now()->subDays(2)->format('Y-m-d'),
                'fecha_fin' => Carbon::now()->addDays(5)->format('Y-m-d'),
                'fecha_inscripcion_limite' => Carbon::now()->subDays(7)->format('Y-m-d'),
                'categoria' => 'femenino',
                'precio_inscripcion' => 60000.00,
                'cantidad_max_participantes' => 12,
                'estado' => 'en_curso',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Torneo Navideño Mixto',
                'descripcion' => 'Cierra el año con broche de oro en este torneo mixto. Excelentes premios y ambiente festivo navideño. Parejas mixtas o del mismo género bienvenidas. Incluye refrigerio y premios para los primeros 3 lugares.',
                'id_organizador' => $organizadorId,
                'fecha_inicio' => Carbon::parse('2025-12-20')->format('Y-m-d'),
                'fecha_fin' => Carbon::parse('2025-12-22')->format('Y-m-d'),
                'fecha_inscripcion_limite' => Carbon::parse('2025-12-15')->format('Y-m-d'),
                'categoria' => 'mixto',
                'precio_inscripcion' => 45000.00,
                'cantidad_max_participantes' => 16,
                'estado' => 'inscripciones_abiertas',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        DB::table('torneos')->insert($torneos);

        echo "Se crearon " . count($torneos) . " torneos exitosamente.\n";
    }
}
