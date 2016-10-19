<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Establecimiento;
use DB;
use App\Models\Sede;
use App\Models\Plan;

class EstablecimientosController extends Controller
{
    public function getSedes($id)
    {
        return Sede::where('establecimiento_id', $id)->get();
    }
    
    public function index()
    {
        return Establecimiento::with(['administrador', 'administrador.user'])->get();
    }
    
    public function store(Request $request)
    {
        $respuesta = [];
        $respuesta['result'] = false;
        DB::transaction(function() use($request, &$respuesta) {
            try {
                $rules = [
                'nombre'      => 'string|required',
                'mensaje'      => 'string|required',
                'administrador_id' => 'exists:administradores,id|required',
                'plan_id' => 'exists:planes,id|required',
                'tiene_mensajero' => 'boolean'
                ];
                $validator = \Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    $respuesta['result'] = false;
                    $respuesta['validator'] = $validator->errors()->all();
                    $respuesta['mensaje'] = '¡Error!';
                } else {
                    $instancia = new Establecimiento($request->all());
                    $plan = Plan::find($instancia->plan_id);
                    if($plan) {
                        $instancia->sms_restantes = $plan->cantidad_sms;
                        $instancia->vendedores_restantes = $plan->cantidad_vendedores;
                    }
                    $respuesta['result'] = $instancia->save();
                    if ($respuesta['result']) {
                        
                        $respuesta['mensaje'] = "Registrado correctamente.";
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
                'mensaje'      => 'string|required',
                'administrador_id' => 'exists:administradores,id|required',
                'plan_id' => 'exists:planes,id|required'
                ];
                $validator = \Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    $respuesta['result'] = false;
                    $respuesta['validator'] = $validator->errors()->all();
                    $respuesta['mensaje'] = '¡Error!';
                } else {
                    $instancia = Establecimiento::find($id);
                    $instancia->fill($request->all());
                    $plan = Plan::find($instancia->plan_id);
                    if($plan) {
                        $instancia->sms_restantes = $plan->cantidad_sms;
                        $instancia->vendedores_restantes = $plan->cantidad_vendedores;
                    }
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
        $instancia = Establecimiento::find($id);
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