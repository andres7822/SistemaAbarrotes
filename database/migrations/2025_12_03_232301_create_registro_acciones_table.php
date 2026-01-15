<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegistroAccionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registro_acciones', function (Blueprint $table) {
            $table->id();
            $table->string('origen', 128);
            $table->integer('tupla')->nullable();
            $table->dateTime('fecha')->useCurrent();
            $table->text('detalle')->nullable();
            $table->foreignId('accione_id')->nullable()->constrained('acciones');
            $table->foreignId('user_id')->constrained('users');
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
        Schema::dropIfExists('registro_acciones');
    }
}
