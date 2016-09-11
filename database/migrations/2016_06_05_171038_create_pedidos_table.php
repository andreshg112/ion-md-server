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
            $table->string('direccion', 100);
            $table->bigInteger('numero');
            $table->boolean('enviado')->default(0);
            $table->integer('establecimiento_id')->unsigned();
            $table->foreign('establecimiento_id')
            ->references('id')->on('establecimientos')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            $table->timestamps();
            $table->softDeletes();
            $table->engine = 'InnoDB';
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