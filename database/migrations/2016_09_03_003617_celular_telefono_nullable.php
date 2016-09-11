<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CelularTelefonoNullable extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->bigInteger('celular')->nullable()->change();
            $table->integer('telefono')->nullable()->change();
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
            $table->bigInteger('celular')->change();
            $table->integer('telefono')->change();
        });
    }
}