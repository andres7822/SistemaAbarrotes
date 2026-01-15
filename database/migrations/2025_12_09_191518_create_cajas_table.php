<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCajasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cajas', function (Blueprint $table) {
            $table->id();
            $table->dateTime('fecha_inicio')->useCurrent();
            $table->decimal('cantidad_inicial', 12, 2);
            $table->decimal('cantidad_cierre', 12, 2)->nullable();
            $table->decimal('cambio_dejado', 12, 2)->nullable();
            $table->dateTime('fecha_cierre')->nullable();
            $table->text('observaciones')->nullable();
            $table->text('denominaciones')->nullable();
            $table->foreignId('user_id')->constrained('users');
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
        Schema::dropIfExists('cajas');
    }
}
