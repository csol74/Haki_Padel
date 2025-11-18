<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modificar el enum para incluir 'socio'
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('cliente', 'socio', 'profesor', 'admin') DEFAULT 'cliente'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Volver al enum original
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('cliente', 'profesor', 'admin') DEFAULT 'cliente'");
    }
};
