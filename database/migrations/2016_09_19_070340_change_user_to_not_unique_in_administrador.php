<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeUserToNotUniqueInAdministrador extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::table('administradores', function (Blueprint $table) {
            $table->dropForeign('administradores_user_id_foreign');
            $table->dropUnique('administradores_user_id_unique');
            
            $table->foreign('user_id')
            ->references('id')->on('users')
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
        Schema::table('administradores', function (Blueprint $table) {
            $table->unique('user_id');
        });
    }
}