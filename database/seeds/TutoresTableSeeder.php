<?php

use Illuminate\Database\Seeder;

class TutoresTableSeeder extends Seeder
{
    /**
    * Run the database seeds.
    *
    * @return void
    */
    public function run()
    {
        //
        factory('App\Models\Tutor', 10)->create();
    }
}