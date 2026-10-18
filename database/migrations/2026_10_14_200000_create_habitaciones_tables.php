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
        // 1. TABLA TIPO_HABITACION
        Schema::create('TIPO_HABITACION', function (Blueprint $table) {
            $table->id('id_tipo_habitacion');
            $table->string('nombre_tipo', 50);
            $table->decimal('precio_noche', 10, 2);
            $table->integer('capacidad');
            $table->timestamps();
        });

        // 2. TABLA ESTADO_HABITACION
        Schema::create('ESTADO_HABITACION', function (Blueprint $table) {
            $table->id('id_estado_habitacion');
            $table->string('nombre_estado', 30)->unique();
            $table->string('descripcion', 150)->nullable();
            $table->string('color_hex', 10)->nullable();
            $table->timestamps();
        });

        // 3. TABLA HABITACION
        Schema::create('HABITACION', function (Blueprint $table) {
            $table->id('id_habitacion');
            $table->string('numero_habitacion', 10)->unique();
            $table->integer('piso');
            $table->unsignedBigInteger('id_tipo_habitacion');
            $table->unsignedBigInteger('id_estado_habitacion');
            $table->timestamps();

            $table->foreign('id_tipo_habitacion', 'fk_hab_tipohab')
                  ->references('id_tipo_habitacion')
                  ->on('TIPO_HABITACION')
                  ->onDelete('cascade');

            $table->foreign('id_estado_habitacion', 'fk_hab_estado')
                  ->references('id_estado_habitacion')
                  ->on('ESTADO_HABITACION')
                  ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('HABITACION');
        Schema::dropIfExists('ESTADO_HABITACION');
        Schema::dropIfExists('TIPO_HABITACION');
    }
};
