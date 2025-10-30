<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('noticias', function (Blueprint $table) {
            $table->id();
            $table->string('titulo', 200);
            $table->text('contenido');
            $table->unsignedBigInteger('id_autor');
            $table->enum('estado', ['borrador', 'publicada', 'archivada'])->default('borrador');
            $table->timestamps();
            
            $table->foreign('id_autor')->references('id')->on('usuarios')->onDelete('cascade');
            $table->index('estado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('noticias');
    }
};