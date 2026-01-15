<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovimientoCajasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movimiento_cajas', function (Blueprint $table) {
            $table->id();
            $table->text('concepto')->nullable();
            $table->decimal('monto', 12, 2);
            $table->string('origen', 64)->nullable();
            $table->integer('tupla')->nullable();
            $table->dateTime('fecha')->useCurrent();
            $table->foreignId('caja_id')->constrained('cajas');
            $table->foreignId('tipo_movimiento_id')->default(1)->constrained('tipo_movimientos');
            $table->foreignId('tipo_ingreso_id')->constrained('tipo_ingresos');
            $table->foreignId('tienda_id')->nullable()->constrained('tiendas');
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
        Schema::dropIfExists('movimiento_cajas');
    }
}
