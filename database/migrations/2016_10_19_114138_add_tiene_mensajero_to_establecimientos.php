<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTieneMensajeroToEstablecimientos extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::table('establecimientos', function (Blueprint $table) {
            $table->boolean('tiene_mensajero')
            ->after('administrador_id')->default(true);
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
            $table->dropColumn('tiene_mensajero');
        });
    }
}