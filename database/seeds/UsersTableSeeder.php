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
        'username' => 'localempleado1',
        'password' => '1234',
        'primer_nombre' => 'Empleado 1',
        'primer_apellido' => 'Local',
        'rol' => 'EMPLEADO',
        'genero' => 'masculino',
        'establecimiento_id' => 1
        ]);
    }
}