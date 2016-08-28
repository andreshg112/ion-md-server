<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Horario;
use \stdClass;

class HorariosController extends Controller
{
    
    public function destroy($id)
    {
        $instancia = Horario::find($id);
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
        return Horario::with('materia', 'tutor')->get();
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
            'materia_id'      => 'required|exists:materias,id',
            'tutor_id'  => 'required|exists:users,id,tipo_usuario,tutor',
            'dia'  => 'required|in:lunes,martes,miércoles,jueves,viernes,sábado',
            'hora_inicio'  => 'required',
            'hora_fin'  => 'required'
            ];
            
            try {
                // Ejecutamos el validador y en caso de que falle devolvemos la respuesta
                //$request->all()['dia'] = strtolower($request->all()['dia']);
                $validator = \Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    $respuesta['result'] = false;
                    $respuesta['validator'] = $validator->errors()->all();
                    $respuesta['mensaje'] = '¡Error!';
                } else {
                    $instancia = new Horario($request->all());
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
        $respuesta = [];
        if (!is_array($request->all())) {
            $respuesta['result'] = false;
            $respuesta['mensaje'] = 'Los datos enviados no tienen el formato correcto.';
        } else {
            $instancia = Horario::find($id);
            if ($instancia) {
                $instancia->fill($request->all());
                $rules = [
                'materia_id'      => 'required|exists:materias,id',
                'tutor_id'  => 'required|exists:users,id,tipo_usuario,tutor',
                'dia'  => 'required|in:lunes,martes,miércoles,jueves,viernes,sábado',
                'hora_inicio'  => 'required',
                'hora_fin'  => 'required'
                ];
                try {
                    $validator = \Validator::make($request->all(), $rules);
                    if ($validator->fails()) {
                        $respuesta['result'] = false;
                        $respuesta['validator'] = $validator->errors()->all();
                        $respuesta['mensaje'] = '¡Error!';
                    } else {
                        $respuesta['result'] = $instancia->save();
                        if ($respuesta['result']) {
                            $respuesta['mensaje'] = "Actualizado correctamente.";
                            $respuesta['result'] = $instancia;
                        } else {
                            $respuesta['mensaje'] = "No se pudo actualizar.";
                        }
                    }
                } catch (Exception $e) {
                    $respuesta['result'] = false;
                    $respuesta['mensaje'] = "Error: $e";
                }
            } else {
                $respuesta['result'] = false;
                $respuesta['mensaje'] = 'No se encuentra registrado.';
            }
        }
        return $respuesta;
    }
    
    public function show($id)
    {
        return Horario::findOrFail($id);
    }
    
}