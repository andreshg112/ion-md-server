<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSedeToUsersTable extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('sede_id')->unsigned()
            ->nullable()->after('establecimiento_id');
            $table->foreign('sede_id')
            ->references('id')->on('sedes')
            ->onDelete('cascade')
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
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_sede_id_foreign');
            $table->dropColumn('sede_id');
        });
    }
}