<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use DB;
use App\Models\Sede;
use App\Models\Vendedor;

class VendedoresController extends Controller
{
    public function index()
    {
        return Vendedor::with(['user', 'sede', 'sede.establecimiento'])->get();
    }
    
    public function store(Request $request)
    {
        $respuesta = [];
        $respuesta['result'] = false;
        DB::transaction(function() use($request, &$respuesta) {
            try {
                $rules = [
                'sede_id'      => 'exists:sedes,id|required',
                'user_id' => 'exists:users,id|required'
                ];
                $validator = \Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    $respuesta['result'] = false;
                    $respuesta['validator'] = $validator->errors()->all();
                    $respuesta['mensaje'] = '¡Error!';
                } else {
                    $instancia = new Vendedor($request->all());
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
    
    public function destroy($id)
    {
        $instancia = Vendedor::find($id);
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