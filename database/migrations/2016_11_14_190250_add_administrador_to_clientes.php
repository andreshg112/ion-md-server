<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdministradorToClientes extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->integer('administrador_id')->unsigned()->nullable()->after('fecha_nacimiento');
            $table->foreign('administrador_id')->references('id')
            ->on('administradores')->onUpdate('cascade');
        });
        //Actualizar la tabla automáticamente
        //...para que tome el administrador del establecimiento.
        /*update clientes set administrador_id =
        (select administrador_id from establecimientos
        where establecimientos.id = clientes.establecimiento_id)*/
        DB::transaction(function () {
            DB::statement('update clientes set clientes.administrador_id = ' .
            '(select establecimientos.administrador_id from establecimientos ' .
            'where establecimientos.id = clientes.establecimiento_id)');
        });
    }
    
    /**
    * Reverse the migrations.
    *
    * @return void
    */
    public function down()
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropForeign('clientes_administrador_id_foreign');
            $table->dropColumn('administrador_id');
        });
    }
}