<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Programa;
use \stdClass;

class ProgramasController extends Controller
{
    
    public function index()
    {
        $respuesta = [];
        $respuesta['result'] = Programa::all();
        if (count($respuesta['result']) == 0) {
            $respuesta['result'] = false;
            $respuesta['mensaje'] = "No hay registros.";
        }
        return $respuesta;
    }
    
}