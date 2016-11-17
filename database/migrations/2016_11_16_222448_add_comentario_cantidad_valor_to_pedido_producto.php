<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddComentarioCantidadValorToPedidoProducto extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::table('pedido_producto', function (Blueprint $table) {
            $table->string('comentario', 100)->nullable();
            $table->mediumInteger('valor')->nullable();
            $table->tinyInteger('cantidad')->nullable();
        });
    }
    
    /**
    * Reverse the migrations.
    *
    * @return void
    */
    public function down()
    {
        Schema::table('pedido_producto', function (Blueprint $table) {
            $table->dropColumn(['comentario', 'valor', 'cantidad']);
        });
    }
}