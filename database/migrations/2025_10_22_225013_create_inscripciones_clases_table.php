<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inscripciones_clases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_clase');
            $table->unsignedBigInteger('id_usuario');
            $table->timestamps();
            
            $table->foreign('id_clase')->references('id')->on('clases')->onDelete('cascade');
            $table->foreign('id_usuario')->references('id')->on('usuarios')->onDelete('cascade');
            $table->unique(['id_clase', 'id_usuario']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inscripciones_clases');
    }
};