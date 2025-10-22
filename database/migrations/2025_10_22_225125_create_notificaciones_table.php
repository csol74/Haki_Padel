<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_usuario');
            $table->string('titulo', 150);
            $table->text('contenido');
            $table->enum('tipo', ['reserva', 'torneo', 'clase', 'general'])->default('general');
            $table->boolean('leida')->default(false);
            $table->timestamps();
            
            $table->foreign('id_usuario')->references('id')->on('usuarios')->onDelete('cascade');
            $table->index(['id_usuario', 'leida']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notificaciones');
    }
};