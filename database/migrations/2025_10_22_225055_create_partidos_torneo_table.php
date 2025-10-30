<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('partidos_torneo', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_torneo');
            $table->unsignedBigInteger('id_cancha')->nullable();
            $table->date('fecha')->nullable();
            $table->time('hora_inicio')->nullable();
            $table->unsignedBigInteger('id_participante1')->nullable();
            $table->unsignedBigInteger('id_participante2')->nullable();
            $table->enum('resultado', ['pendiente', 'finalizado'])->default('pendiente');
            $table->unsignedBigInteger('ganador')->nullable();
            $table->timestamps();
            
            $table->foreign('id_torneo')->references('id')->on('torneos')->onDelete('cascade');
            $table->foreign('id_cancha')->references('id')->on('canchas')->onDelete('set null');
            $table->foreign('id_participante1')->references('id')->on('participantes_torneo')->onDelete('set null');
            $table->foreign('id_participante2')->references('id')->on('participantes_torneo')->onDelete('set null');
            $table->index('id_torneo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partidos_torneo');
    }
};