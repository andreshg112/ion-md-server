<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Asistencia;
use App\Models\Horario;
use \stdClass;

class AsistenciasController extends Controller
{
    
    public function get_by_tutor($tutor_id)
    {
        $respuesta = [];
        $horarios_tutor_id = Horario::select('id')->where('tutor_id', $tutor_id)->get();
        $respuesta['result'] = Asistencia::with(['alumno', 'alumno.programa', 'horario', 'horario.materia'])->whereIn('horario_id', $horarios_tutor_id)->get();
        if (count($respuesta['result']) == 0) {
            $respuesta['result'] = false;
            $respuesta['mensaje'] = "No hay registros.";
        }
        return $respuesta;
    }
    
    public function store(Request $request, $horario_id)
    {
        $respuesta = [];
        if (!is_array($request->all())) {
            $respuesta['result'] = false;
            $respuesta['mensaje'] = 'Los datos enviados no tienen el formato correcto.';
        } else {
            $recibido = (object) $request->all();
            $rules = [
            'horario_id'  => 'required|exists:horarios,id|unique_with:asistencias,alumno_id,horario_id=horario_id,fecha = fecha',
            'alumno_id'  => 'required|exists:users,id,tipo_usuario,alumno',
            'temas_tutoriados'  => 'required|string',
            'fecha'  => 'required|date',
            ];
            $messages = [
            'horario_id.unique_with' => 'Ya registraste tu asistencia a esta asesoría.',
            ];
            
            try {
                $validator = \Validator::make($request->all(), $rules, $messages);
                if ($validator->fails()) {
                    $respuesta['result'] = false;
                    $respuesta['validator'] = $validator->errors()->all();
                    $respuesta['mensaje'] = '¡Error!';
                } else {
                    $instancia = new Asistencia($request->all());
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
    
}