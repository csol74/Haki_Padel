<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_entrenador');
            $table->unsignedBigInteger('id_cancha');
            $table->date('fecha');
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->enum('nivel', ['principiante', 'intermedio', 'avanzado'])->default('principiante');
            $table->decimal('precio', 10, 2)->nullable();
            $table->enum('estado', ['programada', 'cancelada', 'completada'])->default('programada');
            $table->timestamps();

            $table->foreign('id_entrenador')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_cancha')->references('id')->on('canchas')->onDelete('cascade');
            $table->index(['id_entrenador', 'fecha']);
            $table->index(['id_cancha', 'fecha']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clases');
    }
};
