<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTipoUsuarioInUsers extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            DB::statement("ALTER TABLE users MODIFY COLUMN rol ENUM('SUPER_USER', 'ADMIN', 'VENDEDOR') NOT NULL DEFAULT 'VENDEDOR'");
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
            DB::statement("ALTER TABLE users MODIFY COLUMN rol ENUM('ADMIN', 'EMPLEADO') NOT NULL DEFAULT 'EMPLEADO'");
        });
    }
}