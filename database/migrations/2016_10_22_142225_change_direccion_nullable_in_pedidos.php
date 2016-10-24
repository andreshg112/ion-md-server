<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeDireccionNullableInPedidos extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::table('pedidos', function (Blueprint $table) {
            DB::statement('ALTER TABLE pedidos MODIFY direccion varchar(100) null');
        });
    }
    
    /**
    * Reverse the migrations.
    *
    * @return void
    */
    public function down()
    {
        Schema::table('pedidos', function (Blueprint $table) {
            DB::statement('ALTER TABLE pedidos MODIFY direccion varchar(100) not null');
        });
    }
}