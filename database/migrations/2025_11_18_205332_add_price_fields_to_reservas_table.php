<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservas', function (Blueprint $table) {
            $table->decimal('precio_base', 10, 2)->default(0)->after('numero_jugadores');
            $table->decimal('descuento', 10, 2)->default(0)->after('precio_base');
            $table->decimal('precio_final', 10, 2)->default(0)->after('descuento');
            $table->decimal('duracion_horas', 4, 2)->default(1)->after('precio_final');
        });
    }

    public function down(): void
    {
        Schema::table('reservas', function (Blueprint $table) {
            $table->dropColumn(['precio_base', 'descuento', 'precio_final', 'duracion_horas']);
        });
    }
};
