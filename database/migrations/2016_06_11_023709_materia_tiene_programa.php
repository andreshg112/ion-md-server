<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MateriaTienePrograma extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::table('materias', function (Blueprint $table) {
            $table->integer('programa_id')->unsigned();
            $table->foreign('programa_id')
            ->references('id')->on('programas')
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
        Schema::table('materias', function (Blueprint $table) {
            $table->dropForeign('materias_programa_id_foreign');
            $table->dropColumn('programa_id');
        });
    }
}