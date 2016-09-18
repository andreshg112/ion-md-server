<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendedoresTable extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::create('vendedores', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('user_id')->unsigned()->unique();
            $table->foreign('user_id')
            ->references('id')->on('users')
            ->onUpdate('cascade');
            
            $table->integer('sede_id')->unsigned();
            $table->foreign('sede_id')
            ->references('id')->on('sedes')
            ->onUpdate('cascade');
            
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
        Schema::drop('vendedores');
    }
}