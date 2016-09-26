<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPrecioToPedidosTable extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->mediumInteger('total')->nullable()->after('detalles');
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
            $table->dropColumn('total');
        });
    }
}