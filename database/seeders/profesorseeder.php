<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Profesor;
use App\Models\HorarioProfesor;
use Illuminate\Support\Facades\Hash;

class ProfesorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Datos de profesores
        $profesores = [
            [
                'nombre' => 'Carlos Mendoza',
                'email' => 'carlos.mendoza@hakipadel.com',
                'especialidad' => 'competitivo',
                'biografia' => 'Ex-jugador profesional con más de 15 años de experiencia en competiciones nacionales e internacionales. Especializado en técnicas avanzadas y estrategias de juego.',
                'tarifa_hora' => 100000,
                'tarifa_base_30min' => 55000,
                'experiencia_anios' => 15,
                'foto' => 'profesores/carlos.jpg',
                'horarios' => [
                    ['dia' => 'lunes', 'inicio' => '06:00', 'fin' => '12:00', 'prime' => false],
                    ['dia' => 'miercoles', 'inicio' => '06:00', 'fin' => '12:00', 'prime' => false],
                    ['dia' => 'viernes', 'inicio' => '06:00', 'fin' => '12:00', 'prime' => false],
                    ['dia' => 'sabado', 'inicio' => '08:00', 'fin' => '14:00', 'prime' => false],
                ]
            ],
            [
                'nombre' => 'María Fernanda López',
                'email' => 'maria.lopez@hakipadel.com',
                'especialidad' => 'infantil',
                'biografia' => 'Educadora física especializada en enseñanza infantil. Métodos didácticos y divertidos para que los niños aprendan mientras se divierten.',
                'tarifa_hora' => 70000,
                'tarifa_base_30min' => 40000,
                'experiencia_anios' => 8,
                'foto' => 'profesores/maria.jpg',
                'horarios' => [
                    ['dia' => 'lunes', 'inicio' => '14:00', 'fin' => '18:00', 'prime' => false],
                    ['dia' => 'martes', 'inicio' => '14:00', 'fin' => '18:00', 'prime' => false],
                    ['dia' => 'miercoles', 'inicio' => '14:00', 'fin' => '18:00', 'prime' => false],
                    ['dia' => 'jueves', 'inicio' => '14:00', 'fin' => '18:00', 'prime' => false],
                    ['dia' => 'sabado', 'inicio' => '09:00', 'fin' => '13:00', 'prime' => false],
                ]
            ],
            [
                'nombre' => 'Andrés Ramírez',
                'email' => 'andres.ramirez@hakipadel.com',
                'especialidad' => 'acondicionamiento_fisico',
                'biografia' => 'Preparador físico certificado. Enfoque en resistencia, fuerza y prevención de lesiones específicas para pádel.',
                'tarifa_hora' => 85000,
                'tarifa_base_30min' => 48000,
                'experiencia_anios' => 10,
                'foto' => 'profesores/andres.jpg',
                'horarios' => [
                    ['dia' => 'martes', 'inicio' => '06:00', 'fin' => '10:00', 'prime' => false],
                    ['dia' => 'jueves', 'inicio' => '06:00', 'fin' => '10:00', 'prime' => false],
                    ['dia' => 'martes', 'inicio' => '18:00', 'fin' => '21:00', 'prime' => true],
                    ['dia' => 'jueves', 'inicio' => '18:00', 'fin' => '21:00', 'prime' => true],
                    ['dia' => 'domingo', 'inicio' => '07:00', 'fin' => '12:00', 'prime' => false],
                ]
            ],
            [
                'nombre' => 'Laura Gómez',
                'email' => 'laura.gomez@hakipadel.com',
                'especialidad' => 'casual',
                'biografia' => 'Entrenadora versátil enfocada en jugadores recreativos. Ambiente relajado y clases personalizadas según tu nivel.',
                'tarifa_hora' => 65000,
                'tarifa_base_30min' => 38000,
                'experiencia_anios' => 6,
                'foto' => 'profesores/laura.jpg',
                'horarios' => [
                    ['dia' => 'lunes', 'inicio' => '16:00', 'fin' => '21:00', 'prime' => true],
                    ['dia' => 'miercoles', 'inicio' => '16:00', 'fin' => '21:00', 'prime' => true],
                    ['dia' => 'viernes', 'inicio' => '16:00', 'fin' => '21:00', 'prime' => true],
                    ['dia' => 'sabado', 'inicio' => '14:00', 'fin' => '20:00', 'prime' => false],
                ]
            ],
            [
                'nombre' => 'Diego Vargas',
                'email' => 'diego.vargas@hakipadel.com',
                'especialidad' => 'competitivo',
                'biografia' => 'Entrenador táctico especializado en jugadores de alto rendimiento. Análisis de video y estrategias personalizadas.',
                'tarifa_hora' => 95000,
                'tarifa_base_30min' => 52000,
                'experiencia_anios' => 12,
                'foto' => 'profesores/diego.jpg',
                'horarios' => [
                    ['dia' => 'martes', 'inicio' => '15:00', 'fin' => '20:00', 'prime' => true],
                    ['dia' => 'jueves', 'inicio' => '15:00', 'fin' => '20:00', 'prime' => true],
                    ['dia' => 'sabado', 'inicio' => '07:00', 'fin' => '13:00', 'prime' => false],
                    ['dia' => 'domingo', 'inicio' => '08:00', 'fin' => '14:00', 'prime' => false],
                ]
            ],
            [
                'nombre' => 'Sofia Castro',
                'email' => 'sofia.castro@hakipadel.com',
                'especialidad' => 'infantil',
                'biografia' => 'Psicopedagoga y entrenadora de pádel. Desarrollo de habilidades motrices y valores deportivos en niños.',
                'tarifa_hora' => 68000,
                'tarifa_base_30min' => 39000,
                'experiencia_anios' => 7,
                'foto' => 'profesores/sofia.jpg',
                'horarios' => [
                    ['dia' => 'lunes', 'inicio' => '15:00', 'fin' => '19:00', 'prime' => false],
                    ['dia' => 'miercoles', 'inicio' => '15:00', 'fin' => '19:00', 'prime' => false],
                    ['dia' => 'viernes', 'inicio' => '15:00', 'fin' => '19:00', 'prime' => false],
                    ['dia' => 'sabado', 'inicio' => '10:00', 'fin' => '14:00', 'prime' => false],
                ]
            ],
            [
                'nombre' => 'Juan Pablo Moreno',
                'email' => 'juanpablo.moreno@hakipadel.com',
                'especialidad' => 'casual',
                'biografia' => 'Enfoque en jugadores principiantes e intermedios. Clases dinámicas y adaptadas a cada estudiante.',
                'tarifa_hora' => 60000,
                'tarifa_base_30min' => 35000,
                'experiencia_anios' => 5,
                'foto' => 'profesores/juanpablo.jpg',
                'horarios' => [
                    ['dia' => 'lunes', 'inicio' => '08:00', 'fin' => '13:00', 'prime' => false],
                    ['dia' => 'martes', 'inicio' => '08:00', 'fin' => '13:00', 'prime' => false],
                    ['dia' => 'miercoles', 'inicio' => '08:00', 'fin' => '13:00', 'prime' => false],
                    ['dia' => 'jueves', 'inicio' => '08:00', 'fin' => '13:00', 'prime' => false],
                    ['dia' => 'viernes', 'inicio' => '08:00', 'fin' => '13:00', 'prime' => false],
                ]
            ],
            [
                'nombre' => 'Patricia Ruiz',
                'email' => 'patricia.ruiz@hakipadel.com',
                'especialidad' => 'acondicionamiento_fisico',
                'biografia' => 'Fisioterapeuta y preparadora física. Programas de entrenamiento funcional y recuperación post-lesión.',
                'tarifa_hora' => 80000,
                'tarifa_base_30min' => 46000,
                'experiencia_anios' => 9,
                'foto' => 'profesores/patricia.jpg',
                'horarios' => [
                    ['dia' => 'lunes', 'inicio' => '06:00', 'fin' => '11:00', 'prime' => false],
                    ['dia' => 'miercoles', 'inicio' => '06:00', 'fin' => '11:00', 'prime' => false],
                    ['dia' => 'viernes', 'inicio' => '06:00', 'fin' => '11:00', 'prime' => false],
                    ['dia' => 'domingo', 'inicio' => '09:00', 'fin' => '13:00', 'prime' => false],
                ]
            ],
            [
                'nombre' => 'Roberto Sánchez',
                'email' => 'roberto.sanchez@hakipadel.com',
                'especialidad' => 'competitivo',
                'biografia' => 'Campeón regional múltiple. Especializado en golpes de potencia y técnicas de ataque.',
                'tarifa_hora' => 90000,
                'tarifa_base_30min' => 50000,
                'experiencia_anios' => 11,
                'foto' => 'profesores/roberto.jpg',
                'horarios' => [
                    ['dia' => 'martes', 'inicio' => '14:00', 'fin' => '19:00', 'prime' => true],
                    ['dia' => 'jueves', 'inicio' => '14:00', 'fin' => '19:00', 'prime' => true],
                    ['dia' => 'sabado', 'inicio' => '08:00', 'fin' => '12:00', 'prime' => false],
                ]
            ],
            [
                'nombre' => 'Valentina Ríos',
                'email' => 'valentina.rios@hakipadel.com',
                'especialidad' => 'casual',
                'biografia' => 'Entrenadora enfocada en técnica y disfrute del juego. Perfecta para quien busca mejorar sin presión.',
                'tarifa_hora' => 63000,
                'tarifa_base_30min' => 37000,
                'experiencia_anios' => 4,
                'foto' => 'profesores/valentina.jpg',
                'horarios' => [
                    ['dia' => 'lunes', 'inicio' => '17:00', 'fin' => '21:00', 'prime' => true],
                    ['dia' => 'miercoles', 'inicio' => '17:00', 'fin' => '21:00', 'prime' => true],
                    ['dia' => 'viernes', 'inicio' => '17:00', 'fin' => '21:00', 'prime' => true],
                    ['dia' => 'domingo', 'inicio' => '14:00', 'fin' => '19:00', 'prime' => false],
                ]
            ],
        ];

        // Crear profesores y sus horarios
        foreach ($profesores as $profesorData) {
            // Crear usuario para el profesor
            $user = User::create([
                'name' => $profesorData['nombre'],
                'email' => $profesorData['email'],
                'password' => Hash::make('password123'),
                'role' => 'cliente', // o podrías crear un rol 'profesor'
            ]);

            // Crear profesor
            $profesor = Profesor::create([
                'user_id' => $user->id,
                'especialidad' => $profesorData['especialidad'],
                'biografia' => $profesorData['biografia'],
                'foto' => $profesorData['foto'],
                'tarifa_hora' => $profesorData['tarifa_hora'],
                'tarifa_base_30min' => $profesorData['tarifa_base_30min'],
                'experiencia_anios' => $profesorData['experiencia_anios'],
                'activo' => true,
            ]);

            // Crear horarios
            foreach ($profesorData['horarios'] as $horario) {
                // Calcular multiplicador según día
                $multiplicadores = [
                    'lunes' => 1.0, 'martes' => 1.0, 'miercoles' => 1.0,
                    'jueves' => 1.0, 'viernes' => 1.0, 'sabado' => 1.2, 'domingo' => 1.3
                ];

                HorarioProfesor::create([
                    'profesor_id' => $profesor->id,
                    'dia_semana' => $horario['dia'],
                    'hora_inicio' => $horario['inicio'],
                    'hora_fin' => $horario['fin'],
                    'multiplicador_precio' => $multiplicadores[$horario['dia']],
                    'es_horario_prime' => $horario['prime'],
                    'disponible' => true,
                ]);
            }
        }

        $this->command->info('✅ Se crearon ' . count($profesores) . ' profesores con sus horarios.');
    }
}