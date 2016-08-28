<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MateriasTest extends TestCase
{
    /**
    * A basic test example.
    *
    * @return void
    */
    use DatabaseMigrations;
    
    /*public function testMaterias()
    {
    $data = $this->getData();
    // Creamos un nuevo usuario y verificamos la respuesta
    $this->post('/materias', $data)
    ->seeJsonEquals(['created' => true]);
    
    $data = $this->getData(['codigo' => 'AE2']);
    // Actualizamos al usuario recien creado (id = 1)
    $this->put('/materias/1', $data)
    ->seeJsonEquals(['updated' => true]);
    
    // Obtenemos los datos de dicho usuario modificado
    // y verificamos que el nombre sea el correcto
    $this->get('materias/1')->seeJson(['codigo' => 'AE2']);
    
    // Eliminamos al usuario
    $this->delete('materias/1')->seeJson(['deleted' => true]);
    }*/
    
    // public function testValidationErrorOnCreate()
    // {
    //     $data = $this->getData(['nombre' => 'Programación', 'codigo' => 'ING1']);
    //     $this->post('/materias', $data)->dump();
    // }
    
    public function testNotFound()
    {
        $this->get('materias/10')->seeJsonEquals(['error' => 'Model not found']);
    }
    
    
    public function getData($custom = array())
    {
        $data = [
        //'name'      => 'joe',
        'codigo'     => 'ING1',
        'creditos'  => 4
        ];
        $data = array_merge($data, $custom);
        return $data;
    }
}