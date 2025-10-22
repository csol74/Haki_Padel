<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('email', 100)->unique();
            $table->string('telefono', 20)->nullable();
            $table->string('password');
            $table->enum('rol', ['cliente', 'entrenador', 'operador', 'administrador'])->default('cliente');
            $table->enum('estado', ['activo', 'inactivo'])->default('activo');
            $table->timestamps();
            $table->index('email');
            $table->index('rol');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};