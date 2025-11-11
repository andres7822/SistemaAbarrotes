<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 128);
            $table->string('nombre_ruta', 32)->nullable();
            $table->text('descripcion')->nullable();
            $table->integer('prioridad')->nullable();
            $table->foreignId('icono_id')->constrained('iconos');
            $table->foreignId('tipo_menu_id')->constrained('tipo_menus');
            $table->foreignId('menu_id')->nullable()->constrained('menus')->onDelete('cascade');
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
        Schema::dropIfExists('menus');
    }
}
