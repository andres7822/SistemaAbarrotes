<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 128);
            $table->string('codigo_barras', 128)->nullable();
            $table->text('descripcion')->nullable();
            $table->decimal('precio_venta', 12, 2);
            $table->decimal('costo', 12, 2);
            $table->string('imagen', 256)->nullable();
            $table->tinyInteger('estado')->default(1);
            $table->foreignId('marca_id')->nullable()->constrained('marcas');
            $table->foreignId('presentacione_id')->nullable()->constrained('presentaciones');
            $table->foreignId('subcategoria_id')->constrained('subcategorias');
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
        Schema::dropIfExists('productos');
    }
}
