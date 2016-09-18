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
        DB::transaction(function () {
            $super_user_id = DB::table('users')->insertGetId([
            'username' => 'fidelivery_su',
            'email' => 'fideliveryapp@gmail.com',
            'password' => password_hash('fd17.Yre', PASSWORD_DEFAULT),
            'primer_nombre' => 'Fidelivery',
            'primer_apellido' => 'SU',
            'rol' => 'SUPER_USER',
            'genero' => 'masculino'
            ]);
            
            DB::table('super_users')->insert([
            'user_id' => $super_user_id
            ]);
        });
    }
}