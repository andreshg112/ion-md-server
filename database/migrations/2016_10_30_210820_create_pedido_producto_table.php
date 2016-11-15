<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePedidoProductoTable extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::create('pedido_producto', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('pedido_id')->unsigned();
            $table->foreign('pedido_id')->references('id')
            ->on('pedidos')->onUpdate('cascade');
            
            $table->integer('producto_id')->unsigned();
            $table->foreign('producto_id')->references('id')
            ->on('productos')->onUpdate('cascade');
            
            $table->timestamps();
            $table->softDeletes();
            $table->engine = 'InnoDB'; //Olvidé colocarlo
        });
    }
    
    /**
    * Reverse the migrations.
    *
    * @return void
    */
    public function down()
    {
        Schema::drop('pedido_producto');
    }
}