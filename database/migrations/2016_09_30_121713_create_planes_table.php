<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlanesTable extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::create('planes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre', 20);
            $table->smallInteger('cantidad_sms');
            $table->tinyInteger('cantidad_vendedores');
            $table->timestamps();
            $table->softDeletes();
            $table->engine = 'InnoDB';
        });
    }
    
    /**
    * Reverse the migrations.
    *
    * @return void
    */
    public function down()
    {
        Schema::drop('planes');
    }
}