<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVentaDetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('venta_detalles', function (Blueprint $table) {
            $table->id();
            $table->decimal('precio', 12, 2);
            $table->integer('cantidad');
            $table->decimal('descuento_porcentaje')->default(0);
            $table->decimal('descuento_numero')->default(0);
            $table->tinyInteger('edit')->default(0);
            $table->foreignId('producto_id')->constrained('productos');
            $table->foreignId('inventario_id')->constrained('inventarios');
            $table->foreignId('venta_id')->constrained('ventas')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('venta_detalles');
    }
}
