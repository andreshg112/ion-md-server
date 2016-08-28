<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHorariosTable extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::create('horarios', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('materia_id')->unsigned();
            $table->foreign('materia_id')
            ->references('id')->on('materias')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            $table->integer('tutor_id')->unsigned();
            $table->foreign('tutor_id')
            ->references('id')->on('users')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            $table->enum('dia', ['lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado']);
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->timestamps();
            $table->softDeletes();
        });
    }
    
    /**
    * Reverse the migrations.
    *
    * @return void
    */
    public function down()
    {
        Schema::drop('horarios');
    }
}