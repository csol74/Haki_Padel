<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('participantes_torneo', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_torneo');
            $table->unsignedBigInteger('user_id');
            $table->enum('estado', ['inscrito', 'confirmado', 'retirado'])->default('inscrito');
            $table->timestamps();

            $table->foreign('id_torneo')->references('id')->on('torneos')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['id_torneo', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('participantes_torneo');
    }
};
