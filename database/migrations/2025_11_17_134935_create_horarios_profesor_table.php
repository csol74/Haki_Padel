<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('horarios_profesor', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('profesor_id');
            $table->enum('dia_semana', [
                'lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'
            ]);
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->boolean('disponible')->default(true);
            $table->timestamps();

            $table->foreign('profesor_id')
                  ->references('id')
                  ->on('profesores')
                  ->onDelete('restrict');

            // Evitar horarios duplicados para el mismo profesor
            $table->unique(['profesor_id', 'dia_semana', 'hora_inicio']);

            $table->index(['dia_semana', 'disponible']);
            $table->index(['profesor_id', 'dia_semana']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('horarios_profesor');
    }
};
