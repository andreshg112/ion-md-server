<?php

use Illuminate\Database\Seeder;

class ProgramasTableSeeder extends Seeder
{
    /**
    * Run the database seeds.
    *
    * @return void
    */
    public function run()
    {
        DB::table('programas')->insert([
        ['nombre' => 'Administración de Empresas'],
        ['nombre' => 'Comercio Internacional'],
        ['nombre' => 'Contaduría Pública'],
        ['nombre' => 'Derecho'],
        ['nombre' => 'Economía'],
        ['nombre' => 'Enfermería Superior'],
        ['nombre' => 'Ingeniería Agroindustrial'],
        ['nombre' => 'Ingeniería Ambiental y Sanitaria'],
        ['nombre' => 'Ingeniería de Sistemas'],
        ['nombre' => 'Ingeniería Electrónica'],
        ['nombre' => 'Instrumentación Quirúrgica'],
        ['nombre' => 'Licenciatura en Arte, Folclor y Cultura'],
        ['nombre' => 'Licenciatura en Ciencias Naturales y Educación Ambiental'],
        ['nombre' => 'Licenciatura en Lengua Castellana e Inglés'],
        ['nombre' => 'Licenciatura en Matemáticas y Física'],
        ['nombre' => 'Microbiología'],
        ['nombre' => 'Psicología'],
        ['nombre' => 'Sociología'],
        ]);
    }
}