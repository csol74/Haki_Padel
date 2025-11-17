<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('horarios_profesor', function (Blueprint $table) {
            // Multiplicador de precio por día (ej: fines de semana = 1.2)
            $table->decimal('multiplicador_precio', 3, 2)->default(1.00)->after('hora_fin');
            
            // Si es horario prime (hora pico)
            $table->boolean('es_horario_prime')->default(false)->after('multiplicador_precio');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('horarios_profesor', function (Blueprint $table) {
            $table->dropColumn(['multiplicador_precio', 'es_horario_prime']);
        });
    }
};