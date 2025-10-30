<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('canchas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->integer('numero')->unique();
            $table->enum('tipo', ['indoor', 'outdoor'])->default('indoor');
            $table->decimal('precio_hora', 10, 2);
            $table->enum('estado', ['activa', 'mantenimiento'])->default('activa');
            $table->timestamps();
            $table->index('estado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('canchas');
    }
};
