<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfertasClientes extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        /**
        * Por ejemplo, muchos usuarios pueden poseer el rol de "Admin".
        * To define this relationship, three database tables are needed: users, roles, and role_user.
        * The role_user table is derived from the alphabetical order of the related model names,
        * and contains the user_id and role_id columns.
        */
        Schema::create('cliente_oferta', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('oferta_id')->unsigned();
            $table->foreign('oferta_id')->references('id')->on('ofertas')
            ->onUpdate('cascade');
            
            $table->integer('cliente_id')->unsigned();
            $table->foreign('cliente_id')->references('id')->on('clientes')
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
        Schema::drop('ofertas_clientes');
    }
}