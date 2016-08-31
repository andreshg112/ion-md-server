<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientesTable extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre_completo', 50);
            $table->bigInteger('celular');
            $table->integer('telefono');
            $table->string('direccion_casa', 100)->nullable();
            $table->string('direccion_oficina', 100)->nullable();
            $table->string('direccion_otra', 100)->nullable();
            $table->string('email')->unique()->nullable();
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
        Schema::drop('clientes');
    }
}