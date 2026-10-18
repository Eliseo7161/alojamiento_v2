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
        // 1. TABLA CATEGORIA
        Schema::create('CATEGORIA', function (Blueprint $table) {
            $table->id('id_categoria');
            $table->string('nombre_categoria', 50);
            $table->timestamps();
        });

        // 2. TABLA PRODUCTO
        Schema::create('PRODUCTO', function (Blueprint $table) {
            $table->id('id_producto');
            $table->string('nombre_producto', 100);
            $table->decimal('precio_venta', 10, 2);
            $table->unsignedBigInteger('id_categoria');
            $table->timestamps();

            $table->foreign('id_categoria', 'fk_prod_categoria')
                  ->references('id_categoria')
                  ->on('CATEGORIA')
                  ->onDelete('restrict');
        });

        // 3. TABLA STOCK
        Schema::create('STOCK', function (Blueprint $table) {
            $table->id('id_stock');
            $table->integer('cantidad_actual')->default(0);
            $table->integer('cantidad_minima')->default(0);
            $table->unsignedBigInteger('id_producto')->unique();
            $table->timestamps();

            $table->foreign('id_producto', 'fk_stock_producto')
                  ->references('id_producto')
                  ->on('PRODUCTO')
                  ->onDelete('cascade');
        });

        // 4. TABLA PROVEEDOR
        Schema::create('PROVEEDOR', function (Blueprint $table) {
            $table->id('id_proveedor');
            $table->string('razon_social', 100);
            $table->string('nit_ruc', 20);
            $table->string('telefono', 20)->nullable();
            $table->string('direccion', 200)->nullable();
            $table->timestamps();
        });

        // 5. TABLA COMPRA
        Schema::create('COMPRA', function (Blueprint $table) {
            $table->id('id_compra');
            $table->dateTime('fecha_compra')->useCurrent();
            $table->decimal('total_compra', 10, 2);
            $table->unsignedBigInteger('id_proveedor');
            $table->unsignedBigInteger('id_usuario');
            $table->timestamps();

            $table->foreign('id_proveedor', 'fk_compra_proveedor')
                  ->references('id_proveedor')
                  ->on('PROVEEDOR')
                  ->onDelete('restrict');

            $table->foreign('id_usuario', 'fk_compra_usuario')
                  ->references('id')
                  ->on('users')
                  ->onDelete('restrict');
        });

        // 6. TABLA DETALLE_COMPRA
        Schema::create('DETALLE_COMPRA', function (Blueprint $table) {
            $table->id('id_detalle_compra');
            $table->integer('cantidad');
            $table->decimal('precio_unitario', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->unsignedBigInteger('id_compra');
            $table->unsignedBigInteger('id_producto');
            $table->timestamps();

            $table->foreign('id_compra', 'fk_detcomp_compra')
                  ->references('id_compra')
                  ->on('COMPRA')
                  ->onDelete('cascade');

            $table->foreign('id_producto', 'fk_detcomp_producto')
                  ->references('id_producto')
                  ->on('PRODUCTO')
                  ->onDelete('restrict');
        });

        // 7. TABLA VENTA
        Schema::create('VENTA', function (Blueprint $table) {
            $table->id('id_venta');
            $table->dateTime('fecha_venta')->useCurrent();
            $table->decimal('total', 10, 2);
            $table->unsignedBigInteger('id_usuario');
            $table->unsignedBigInteger('id_cliente')->nullable();
            $table->unsignedBigInteger('id_asignacion')->nullable();
            $table->timestamps();

            $table->foreign('id_usuario', 'fk_venta_usuario')
                  ->references('id')
                  ->on('users')
                  ->onDelete('restrict');

            $table->foreign('id_cliente', 'fk_venta_cliente')
                  ->references('id_cliente')
                  ->on('CLIENTE')
                  ->onDelete('set null');

            $table->foreign('id_asignacion', 'fk_venta_asignacion')
                  ->references('id_asignacion')
                  ->on('ASIGNACION')
                  ->onDelete('set null');
        });

        // 8. TABLA DETALLE_VENTA
        Schema::create('DETALLE_VENTA', function (Blueprint $table) {
            $table->id('id_detalle_venta');
            $table->integer('cantidad');
            $table->decimal('precio_unitario', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->unsignedBigInteger('id_venta');
            $table->unsignedBigInteger('id_producto');
            $table->timestamps();

            $table->foreign('id_venta', 'fk_detvent_venta')
                  ->references('id_venta')
                  ->on('VENTA')
                  ->onDelete('cascade');

            $table->foreign('id_producto', 'fk_detvent_producto')
                  ->references('id_producto')
                  ->on('PRODUCTO')
                  ->onDelete('restrict');
        });

        // 9. TABLA PAGO
        Schema::create('PAGO', function (Blueprint $table) {
            $table->id('id_pago');
            $table->dateTime('fecha_pago')->useCurrent();
            $table->decimal('monto', 10, 2);
            $table->string('metodo_pago', 50);
            $table->enum('tipo_transaccion', ['INGRESO', 'EGRESO']);
            $table->unsignedBigInteger('id_asignacion')->nullable();
            $table->unsignedBigInteger('id_venta')->nullable();
            $table->unsignedBigInteger('id_compra')->nullable();
            $table->unsignedBigInteger('id_usuario');
            $table->timestamps();

            $table->foreign('id_asignacion', 'fk_pago_asignacion')
                  ->references('id_asignacion')
                  ->on('ASIGNACION')
                  ->onDelete('set null');

            $table->foreign('id_venta', 'fk_pago_venta')
                  ->references('id_venta')
                  ->on('VENTA')
                  ->onDelete('set null');

            $table->foreign('id_compra', 'fk_pago_compra')
                  ->references('id_compra')
                  ->on('COMPRA')
                  ->onDelete('set null');

            $table->foreign('id_usuario', 'fk_pago_usuario')
                  ->references('id')
                  ->on('users')
                  ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('PAGO');
        Schema::dropIfExists('DETALLE_VENTA');
        Schema::dropIfExists('VENTA');
        Schema::dropIfExists('DETALLE_COMPRA');
        Schema::dropIfExists('COMPRA');
        Schema::dropIfExists('PROVEEDOR');
        Schema::dropIfExists('STOCK');
        Schema::dropIfExists('PRODUCTO');
        Schema::dropIfExists('CATEGORIA');
    }
};
