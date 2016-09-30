<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPlanesToEstablecimientos extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::table('establecimientos', function (Blueprint $table) {
            $table->integer('plan_id')->unsigned()
            ->nullable()->after('administrador_id');
            $table->foreign('plan_id')->references('id')
            ->on('planes')->onUpdate('cascade');
            
            $table->smallInteger('sms_restantes')->after('plan_id');
            $table->tinyInteger('vendedores_restantes')->after('sms_restantes');
        });
    }
    
    /**
    * Reverse the migrations.
    *
    * @return void
    */
    public function down()
    {
        Schema::table('establecimientos', function (Blueprint $table) {
            $table->dropForeign('establecimientos_plan_id_foreign');
            $table->dropColumn('plan_id');
            $table->dropColumn('sms_restantes');
            $table->dropColumn('vendedores_restantes');
        });
    }
}