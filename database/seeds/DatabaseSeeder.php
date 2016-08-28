<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
    * Run the database seeds.
    *
    * @return void
    */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        //$this->call(TutoresTableSeeder::class);
        $this->call(ProgramasTableSeeder::class);
        $this->call(MateriasTableSeeder::class);
    }
}