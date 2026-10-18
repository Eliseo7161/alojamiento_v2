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
        // 1. TABLA CLIENTE
        Schema::create('CLIENTE', function (Blueprint $table) {
            $table->id('id_cliente');
            $table->string('documento', 20)->unique();
            $table->string('nombre', 50);
            $table->string('apellido', 50);
            $table->string('telefono', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->timestamps();
        });

        // 2. TABLA ASIGNACION
        Schema::create('ASIGNACION', function (Blueprint $table) {
            $table->id('id_asignacion');
            $table->dateTime('fecha_ingreso');
            $table->dateTime('fecha_salida')->nullable();
            $table->enum('estado', ['Activa', 'Finalizada', 'Cancelada'])->default('Activa');

            $table->unsignedBigInteger('id_cliente');
            $table->unsignedBigInteger('id_habitacion');
            $table->unsignedBigInteger('id_usuario');

            $table->foreign('id_cliente')->references('id_cliente')->on('CLIENTE')->onDelete('restrict');
            $table->foreign('id_habitacion')->references('id_habitacion')->on('HABITACION')->onDelete('restrict');
            $table->foreign('id_usuario')->references('id')->on('users')->onDelete('restrict');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ASIGNACION');
        Schema::dropIfExists('CLIENTE');
    }
};
