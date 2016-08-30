<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username')->unique();
            $table->string('email')->unique()->nullable();
            $table->string('password');
            $table->string('primer_nombre', 15);
            $table->string('segundo_nombre', 15)->nullable();
            $table->string('primer_apellido', 15);
            $table->string('segundo_apellido', 15)->nullable();
            $table->enum('genero', ['masculino', 'femenino', 'otro']);
            $table->enum('rol', ['ADMIN', 'EMPLEADO'])->default('EMPLEADO');
            $table->rememberToken();
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
        Schema::drop('users');
    }
}