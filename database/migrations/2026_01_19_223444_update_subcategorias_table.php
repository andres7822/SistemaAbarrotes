<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSubcategoriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subcategorias', function (Blueprint $table) {
            $table->decimal('descuento', 12, 2)->nullable();
            $table->integer('piezas')->nullable();
            $table->integer('paga')->nullable();
            $table->foreignId('tipo_descuento_id')->nullable()->constrained('tipo_descuentos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('subcategorias', function (Blueprint $table) {
            $table->dropColumn('descuento');
            $table->dropColumn('piezas');
            $table->dropColumn('paga');
            $table->dropForeign('subcategorias_tipo_descuento_id_foreign');
            $table->dropColumn('tipo_descuento_id');
        });
    }
}
