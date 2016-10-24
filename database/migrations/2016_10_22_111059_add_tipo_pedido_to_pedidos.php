<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTipoPedidoToPedidos extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->enum('tipo_pedido', ['domicilio', 'mesa'])
            ->default('domicilio')->after('tipo_mensajero');
        });
    }
    
    /**
    * Reverse the migrations.
    *
    * @return void
    */
    public function down()
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropColumn('tipo_pedido');
        });
    }
}