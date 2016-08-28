<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsistenciasTable extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::create('asistencias', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('horario_id')->unsigned();
            $table->foreign('horario_id')
            ->references('id')->on('horarios')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            $table->integer('alumno_id')->unsigned();
            $table->foreign('alumno_id')
            ->references('id')->on('users')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            $table->string('temas_tutoriados');
            $table->date('fecha');
            $table->unique(['horario_id', 'alumno_id', 'fecha']);
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
        Schema::drop('asistencias');
    }
}