<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeUserToNotUniqueInVendedor extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::table('vendedores', function (Blueprint $table) {
            $table->dropForeign('vendedores_user_id_foreign');
            $table->dropUnique('vendedores_user_id_unique');
            
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
        Schema::table('vendedores', function (Blueprint $table) {
            $table->unique('user_id');
        });
    }
}