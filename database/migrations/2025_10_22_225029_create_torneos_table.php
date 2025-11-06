<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('torneos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150);
            $table->text('descripcion')->nullable();
            $table->unsignedBigInteger('id_organizador');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->date('fecha_inscripcion_limite');
            $table->enum('categoria', ['masculino', 'femenino', 'mixto'])->default('mixto');
            $table->decimal('precio_inscripcion', 10, 2)->nullable();
            $table->integer('cantidad_max_participantes')->nullable();
            $table->enum('estado', ['planificacion', 'inscripciones_abiertas', 'en_curso', 'finalizado'])->default('planificacion');
            $table->timestamps();

            $table->foreign('id_organizador')->references('id')->on('users')->onDelete('cascade');
            $table->index('estado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('torneos');
    }
};
