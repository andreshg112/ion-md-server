<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ClienteFechaNacimiento extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->date('fecha_nacimiento')->nullable()->after('email');
            $table->dropUnique('clientes_email_unique');
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
            $table->dropColumn('fecha_nacimiento');
            $table->string('email')->unique()->change();
        });
    }
}