<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserTieneEstablecimiento extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::table('users', function (Blueprint $table)
        {
            $table->integer('establecimiento_id')->unsigned();
            $table->foreign('establecimiento_id')
            ->references('id')->on('establecimientos')
            ->onDelete('cascade')
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
        Schema::table('users', function (Blueprint $table)
        {
            $table->dropForeign('users_establecimiento_id_foreign');
            $table->dropColumn('establecimiento_id');
        });
        
    }
}