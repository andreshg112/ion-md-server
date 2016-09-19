<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdministradorToEstablecimiento extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::table('establecimientos', function (Blueprint $table) {
            $table->integer('administrador_id')->unsigned()
            ->nullable()->after('nombre');
            $table->foreign('administrador_id')
            ->references('id')->on('administradores')
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
        Schema::table('establecimientos', function (Blueprint $table) {
            $table->dropForeign('establecimientos_administrador_id_foreign');
            $table->dropColumn('administrador_id');
        });
    }
}