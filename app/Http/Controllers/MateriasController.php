<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Materia;
use \stdClass;

class MateriasController extends Controller
{
    
    public function destroy($id)
    {
        $instancia = Materia::find($id);
        $respuesta = new stdClass();
        if ($instancia) {
            $respuesta->result = $instancia->forceDelete();
            if ($respuesta->result) {
                $respuesta->mensaje = "Eliminado correctamente.";
                $respuesta->eliminado = $instancia;
            } else {
                $respuesta->mensaje = "Error tratando de eliminar.";
            }
        } else {
            $respuesta->mensaje = "No se encuentra registrado.";
        }
        return (array) $respuesta;
    }
    
    public function index()
    {
        return Materia::with('programa')->get();
    }
    
    public function store(Request $request)
    {
        $respuesta = [];
        if (!is_array($request->all())) {
            $respuesta['result'] = false;
            $respuesta['mensaje'] = 'Los datos enviados no tienen el formato correcto.';
        } else {
            // Creamos las reglas de validación
            $rules = [
            'codigo'      => 'required|unique:materias',
            'nombre'     => 'required',
            'creditos'  => 'required|integer',
            'programa_id'  => 'required|exists:programas,id',
            ];
            
            try {
                // Ejecutamos el validador y en caso de que falle devolvemos la respuesta
                $validator = \Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    $respuesta['result'] = false;
                    $respuesta['validator'] = $validator->errors()->all();
                    $respuesta['mensaje'] = '¡Error!';
                } else {
                    $instancia = new Materia($request->all());
                    $respuesta['result'] = $instancia->save();
                    if ($respuesta['result']) {
                        $respuesta['mensaje'] = "Registrado correctamente.";
                        $respuesta['result'] = $instancia;
                    } else {
                        $respuesta['mensaje'] = "No se pudo registrar.";
                    }
                }
            } catch (Exception $e) {
                $respuesta['result'] = false;
                $respuesta['mensaje'] = "Error: $e";
            }
            
        }
        return $respuesta;
    }
    
    public function update(Request $request, $id)
    {
        $materia = Materia::find($id);
        $materia->update($request->all());
        return ['updated' => true];
    }
    
    public function show($id)
    {
        return Materia::findOrFail($id);
    }
    
}