<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropEstablecimientoFromPedidos extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropForeign('pedidos_establecimiento_id_foreign');
            $table->dropColumn('establecimiento_id');
            
            //Agregar vendedor a pedidos
            $table->integer('vendedor_id')->unsigned()
            ->nullable()->after('enviado');
            $table->foreign('vendedor_id')
            ->references('id')->on('vendedores')
            ->onUpdate('cascade');
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
            $table->dropForeign('pedidos_vendedor_id_foreign');
            $table->dropColumn('vendedor_id');
            
            $table->integer('establecimiento_id')->unsigned()->nullable();
            $table->foreign('establecimiento_id')
            ->references('id')->on('establecimientos')
            ->onUpdate('cascade');
        });
    }
}