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
        Schema::table('reservas_clase', function (Blueprint $table) {
            // Duración en minutos (30, 60, 90, 120)
            $table->integer('duracion_minutos')->default(60)->after('hora_fin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservas_clase', function (Blueprint $table) {
            $table->dropColumn('duracion_minutos');
        });
    }
};