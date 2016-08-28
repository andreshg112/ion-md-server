<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePedidosTable extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cliente_id')->unsigned();
            $table->foreign('cliente_id')
            ->references('id')->on('clientes')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            $table->string('detalles');
            $table->enum('dia', ['lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado', 'domingo']);
            $table->timestamps();
            $table->softDeletes();
        });
    }
    
    /**
    * Reverse the migrations.
    *
    * @return void
    */
    public function down()
    {
        Schema::drop('pedidos');
    }
}