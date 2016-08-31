<?php

use Illuminate\Database\Seeder;

class EstablecimientosTableSeeder extends Seeder
{
    /**
    * Run the database seeds.
    *
    * @return void
    */
    public function run()
    {
        DB::table('establecimientos')->insert([
        'nombre' => 'Restaurante Local'
        ]);
    }
}