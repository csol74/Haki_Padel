<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('id_cancha');
            $table->date('fecha');
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->enum('estado', ['pendiente', 'confirmada', 'cancelada', 'completada'])->default('pendiente');
            $table->integer('numero_jugadores')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_cancha')->references('id')->on('canchas')->onDelete('cascade');
            $table->unique(['id_cancha', 'fecha', 'hora_inicio']);
            $table->index(['user_id', 'fecha']);
            $table->index(['id_cancha', 'fecha']);
            $table->index('estado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};
