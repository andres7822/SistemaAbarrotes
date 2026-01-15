<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->string('folio', 32);
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->decimal('efectivo', 12, 2)->default(0);
            $table->decimal('pago_con', 12, 2)->default(0);
            $table->decimal('tarjeta_debito', 12, 2)->default(0);
            $table->decimal('tarjeta_credito', 12, 2)->default(0);
            $table->decimal('transferencia', 12, 2)->default(0);
            $table->decimal('deposito', 12, 2)->default(0);
            $table->decimal('ultimo_pago', 12, 2)->default(0);
            $table->decimal('debe', 12, 2)->default(0);
            $table->tinyInteger('estado_cobro')->default(0);
            $table->dateTime('fecha_cobro')->nullable();
            $table->dateTime('fecha')->useCurrent();
            $table->foreignId('cliente_id')->nullable()->constrained('clientes');
            $table->foreignId('tipo_venta_id')->constrained('tipo_ventas');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('tienda_id')->nullable()->constrained('tiendas');
            $table->foreignId('estatus_venta_id')->default(1)->constrained('estatus_ventas');
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
        Schema::dropIfExists('ventas');
    }
}
