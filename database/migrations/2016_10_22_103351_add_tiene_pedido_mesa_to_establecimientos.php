<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTienePedidoMesaToEstablecimientos extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::table('establecimientos', function (Blueprint $table) {
            $table->boolean('tiene_pedido_mesa')
            ->after('tiene_mensajero')->default(false);
        });
    }
    
    /**
    * Reverse the migrations.
    *
    * @return void
    */
    public function down()
    {
        Schema::table('establecimientos', function (Blueprint $table) {
            $table->dropColumn('tiene_pedido_mesa');
        });
        
    }
}