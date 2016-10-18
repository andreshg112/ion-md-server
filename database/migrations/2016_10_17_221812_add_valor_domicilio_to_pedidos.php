<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddValorDomicilioToPedidos extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->mediumInteger('valor_domicilio')->nullable()->after('detalles');
            $table->mediumInteger('subtotal')->nullable()->after('detalles');
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
            $table->dropColumn('valor_domicilio');
            $table->dropColumn('subtotal');
        });
    }
}