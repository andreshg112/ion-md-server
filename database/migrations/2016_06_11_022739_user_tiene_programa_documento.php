<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserTieneProgramaDocumento extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('tipo_documento', ['CC', 'CE', 'TI'])->after('password');
            $table->string('numero_documento', 11)->after('tipo_documento')->unique();
            $table->integer('programa_id')->nullable()->unsigned();
            $table->foreign('programa_id')
            ->references('id')->on('programas')
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
            $table->dropColumn('tipo_documento');
            $table->dropColumn('numero_documento');
            $table->dropForeign('users_programa_id_foreign');
            $table->dropColumn('programa_id');
        });
    }
}