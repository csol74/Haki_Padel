<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservas_clase', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Cliente que reserva
            $table->unsignedBigInteger('profesor_id');
            $table->unsignedBigInteger('horario_id'); // Horario específico reservado
            $table->date('fecha_clase');
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->enum('nivel', ['principiante', 'intermedio', 'avanzado'])->default('principiante');
            $table->decimal('precio', 10, 2);
            $table->enum('estado', [
                'pendiente',
                'confirmada',
                'en_curso',
                'completada',
                'cancelada',
                'ausente'
            ])->default('pendiente');
            $table->text('notas')->nullable();
            $table->timestamp('confirmada_at')->nullable();
            $table->timestamp('cancelada_at')->nullable();
            $table->text('motivo_cancelacion')->nullable();
            $table->timestamps();

            // Relaciones con restrict
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('restrict');

            $table->foreign('profesor_id')
                  ->references('id')
                  ->on('profesores')
                  ->onDelete('restrict');

            $table->foreign('horario_id')
                  ->references('id')
                  ->on('horarios_profesor')
                  ->onDelete('restrict');

            // Índices para optimización
            $table->index(['fecha_clase', 'estado']);
            $table->index(['profesor_id', 'fecha_clase']);
            $table->index(['user_id', 'estado']);
            $table->index(['horario_id', 'fecha_clase']);

            // Evitar reservas duplicadas para el mismo horario y fecha
            $table->unique(['horario_id', 'fecha_clase']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservas_clase');
    }
};
