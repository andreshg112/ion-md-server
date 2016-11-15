<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSedeToProductos extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->integer('sede_id')->unsigned()->nullable()->after('valor');
            $table->foreign('sede_id')->references('id')
            ->on('sedes')->onUpdate('cascade');
        });
    }
    
    /**
    * Reverse the migrations.
    *
    * @return void
    */
    public function down()
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropForeign('productos_sede_id_foreign');
            $table->dropColumn('sede_id');
        });
    }
}