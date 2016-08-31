<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
    * Run the database seeds.
    *
    * @return void
    */
    public function run()
    {
        DB::table('users')->insert([
        'nombre_completo' => 'Andres Herrera Garcia',
        'celular' => '3017558591',
        'telefono' => '5883306',
        'direccion_casa' => 'Cra 4E N 20B3 - 15, Barrio Sicarare',
        'email' => 'andreshg112@gmail.com'
        ]);
    }
}