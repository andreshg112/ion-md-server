<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Calificacion;
use \stdClass;

class CalificacionesController extends Controller
{
    
    public function get_by_tutor($tutor_id)
    {
        $respuesta = [];
        $respuesta['result'] = Calificacion::select('nota', 'observaciones')->where('tutor_id', $tutor_id)->get();
        if (count($respuesta['result']) == 0) {
            $respuesta['result'] = false;
            $respuesta['mensaje'] = "No hay registros.";
        }
        return $respuesta;
    }
    
    public function store(Request $request)
    {
        $respuesta = [];
        if (!is_array($request->all())) {
            $respuesta['result'] = false;
            $respuesta['mensaje'] = 'Los datos enviados no tienen el formato correcto.';
        } else {
            $rules = [
            'alumno_id'  => 'required|exists:users,id,tipo_usuario,alumno|unique_with:calificaciones,tutor_id',
            'tutor_id'  => 'required|exists:users,id,tipo_usuario,tutor',
            'nota'  => 'required|in:1,2,3,4,5',
            'observaciones'  => 'string',
            ];
            
            try {
                $validator = \Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    $respuesta['result'] = false;
                    $respuesta['validator'] = $validator->errors()->all();
                    $respuesta['mensaje'] = '¡Error!';
                } else {
                    $instancia = new Calificacion($request->all());
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
            $instancia = Calificacion::find($id);
            if ($instancia) {
                $instancia->fill($request->all());
                $rules = [
                'alumno_id'  => 'required|exists:calificaciones,alumno_id,tutor_id,'.$instancia->tutor_id,
                'tutor_id'  => 'required|exists:calificaciones,tutor_id,alumno_id,'.$instancia->alumno_id,
                'nota'  => 'required|in:1,2,3,4,5',
                'observaciones'  => 'string',
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
    
}