<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use DB;
use App\Models\Sede;
use App\Models\Vendedor;

class SedesController extends Controller
{
    public function index()
    {
        return Sede::with('establecimiento')->get();
    }
    
    public function store(Request $request)
    {
        $respuesta = [];
        $respuesta['result'] = false;
        DB::transaction(function() use($request, &$respuesta) {
            try {
                $rules = [
                'nombre'      => 'string|required',
                'establecimiento_id' => 'exists:establecimientos,id'
                ];
                $validator = \Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    $respuesta['result'] = false;
                    $respuesta['validator'] = $validator->errors()->all();
                    $respuesta['mensaje'] = '¡Error!';
                } else {
                    $datos_recibidos = $request->all();
                    $instancia = new Sede($datos_recibidos);
                    $respuesta['result'] = $instancia->save();
                    if ($respuesta['result']) {
                        $respuesta['mensaje'] = "Registrado correctamente.";
                        $respuesta['result'] = $instancia;
                    } else {
                        $respuesta['mensaje'] = "No se pudo registrar.";
                    }
                }
            } catch (Exception $e) {
                $respuesta['mensaje'] = $e->getMessage();
            }
        });
        return $respuesta;
    }
    
    public function update(Request $request, $id)
    {
        $respuesta = [];
        $respuesta['result'] = false;
        DB::transaction(function() use($request, &$respuesta, $id) {
            try {
                $rules = [
                'nombre'      => 'string|required',
                'establecimiento_id' => 'exists:establecimientos,id'
                ];
                $validator = \Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    $respuesta['result'] = false;
                    $respuesta['validator'] = $validator->errors()->all();
                    $respuesta['mensaje'] = '¡Error!';
                } else {
                    $instancia = Sede::find($id);
                    $instancia->fill($request->all());
                    $respuesta['result'] = $instancia->save();
                    if ($respuesta['result']) {
                        $respuesta['mensaje'] = "Actualizado correctamente.";
                    } else {
                        $respuesta['mensaje'] = "No se pudo actualizar.";
                    }
                }
            } catch (Exception $e) {
                $respuesta['mensaje'] = $e->getMessage();
            }
        });
        return $respuesta;
    }
    
    public function destroy($id)
    {
        $instancia = Sede::find($id);
        $respuesta = [];
        if ($instancia) {
            $instancia->delete();
            $respuesta['result'] = $instancia->trashed();
            if ($respuesta['result']) {
                $respuesta['mensaje'] = "Desactivado correctamente.";
                $respuesta['eliminado'] = $instancia;
            } else {
                $respuesta['mensaje'] = "Error tratando de desactivar.";
            }
        } else {
            $respuesta['mensaje'] = "No se encuentra registrado.";
        }
        return $respuesta;
    }
}