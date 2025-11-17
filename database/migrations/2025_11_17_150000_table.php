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
        Schema::table('profesores', function (Blueprint $table) {
            // Cambiar especialidad a ENUM
            $table->enum('especialidad', [
                'competitivo',
                'infantil', 
                'casual',
                'acondicionamiento_fisico'
            ])->change();
            
            // Agregar campos adicionales
            $table->text('biografia')->nullable()->after('especialidad');
            $table->string('foto')->nullable()->after('biografia');
            $table->decimal('tarifa_base_30min', 10, 2)->after('tarifa_hora');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profesores', function (Blueprint $table) {
            $table->string('especialidad')->change();
            $table->dropColumn(['biografia', 'foto', 'tarifa_base_30min']);
        });
    }
};