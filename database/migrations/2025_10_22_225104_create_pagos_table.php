<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_usuario');
            $table->enum('concepto', ['reserva', 'clase', 'torneo']);
            $table->unsignedBigInteger('id_referencia')->nullable();
            $table->decimal('monto', 10, 2);
            $table->enum('metodo_pago', ['efectivo', 'transferencia', 'tarjeta'])->default('efectivo');
            $table->enum('estado', ['pendiente', 'completado', 'reembolsado'])->default('pendiente');
            $table->timestamps();
            
            $table->foreign('id_usuario')->references('id')->on('usuarios')->onDelete('cascade');
            $table->index('id_usuario');
            $table->index('estado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};