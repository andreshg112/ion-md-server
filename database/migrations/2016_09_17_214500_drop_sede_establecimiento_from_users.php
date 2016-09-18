<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropSedeEstablecimientoFromUsers extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_sede_id_foreign');
            $table->dropColumn('sede_id');
            $table->dropForeign('users_establecimiento_id_foreign');
            $table->dropColumn('establecimiento_id');
        });
    }
    
    /**
    * Reverse the migrations.
    *
    * @return void
    */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('establecimiento_id')->unsigned()->nullable();
            $table->foreign('establecimiento_id')
            ->references('id')->on('establecimientos')
            ->onUpdate('cascade');
            
            $table->integer('sede_id')->unsigned()
            ->nullable()->after('establecimiento_id');
            $table->foreign('sede_id')
            ->references('id')->on('sedes')
            ->onUpdate('cascade');
        });
    }
}