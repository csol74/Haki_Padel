<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profesores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Relación con el usuario
            $table->string('especialidad');
            $table->decimal('tarifa_hora', 10, 2);
            $table->integer('experiencia_anios')->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('restrict');

            $table->index(['activo', 'especialidad']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profesores');
    }
};
