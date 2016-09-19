<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSexoToClientes extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->enum('genero', ['masculino', 'femenino', 'otro'])->after('telefono');
            
            $table->string('barrio_casa', 30)->after('direccion_casa');
            $table->string('barrio_oficina', 30)->after('direccion_oficina');
            $table->string('barrio_otra', 30)->after('direccion_otra');
            
            $table->integer('establecimiento_id')->unsigned()
            ->nullable()->after('fecha_nacimiento');
            $table->foreign('establecimiento_id')
            ->references('id')->on('establecimientos')
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
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn('genero');
            $table->dropColumn('barrio_casa');
            $table->dropColumn('barrio_oficina');
            $table->dropColumn('barrio_otra');
            
            $table->dropForeign('clientes_establecimiento_id_foreign');
            $table->dropColumn('establecimiento_id');
        });
    }
}