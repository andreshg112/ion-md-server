<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Establecimiento;
use App\Models\Pedido;
use DB;
use App\Models\Cliente;

class EstablecimientosController extends Controller
{
    
    public function getPedidosDiaSemana(Request $request, $establecimiento_id)
    {
        $respuesta = [];
        $respuesta['result'] = [];
        //$respuesta['establecimiento'] = Establecimiento::find($establecimiento_id);
        $respuesta['result']['Lunes'] = Pedido::where('establecimiento_id', $establecimiento_id)
        ->where(DB::raw('weekday(created_at)'), 0)
        ->count();
        
        $respuesta['result']['Martes'] = Pedido::where('establecimiento_id', $establecimiento_id)
        ->where(DB::raw('weekday(created_at)'), 1)
        ->count();
        
        $respuesta['result']['Miércoles'] = Pedido::where('establecimiento_id', $establecimiento_id)
        ->where(DB::raw('weekday(created_at)'), 2)
        ->count();
        
        $respuesta['result']['Jueves'] = Pedido::where('establecimiento_id', $establecimiento_id)
        ->where(DB::raw('weekday(created_at)'), 3)
        ->count();
        
        $respuesta['result']['Viernes'] = Pedido::where('establecimiento_id', $establecimiento_id)
        ->where(DB::raw('weekday(created_at)'), 4)
        ->count();
        
        $respuesta['result']['Sábado'] = Pedido::where('establecimiento_id', $establecimiento_id)
        ->where(DB::raw('weekday(created_at)'), 5)
        ->count();
        
        $respuesta['result']['Domingo'] = Pedido::where('establecimiento_id', $establecimiento_id)
        ->where(DB::raw('weekday(created_at)'), 6)
        ->count();
        
        return $respuesta;
    }
    
    public function getClientesEstablecimiento($establecimiento_id)
    {
        return Cliente::select(DB::raw('*, (select count(*) from pedidos where pedidos.cliente_id = clientes.id) as total_pedidos'))
        ->whereHas('pedidos', function ($q) use($establecimiento_id) {
            $q->where('establecimiento_id', $establecimiento_id);
        })->orderBy('total_pedidos', 'desc')->orderBy('nombre_completo', 'asc')->get();
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
                'administrador_id' => 'exists:administradores,id|required'
                ];
                $validator = \Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    $respuesta['result'] = false;
                    $respuesta['validator'] = $validator->errors()->all();
                    $respuesta['mensaje'] = '¡Error!';
                } else {
                    $instancia = new Establecimiento($request->all());
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
                'administrador_id' => 'exists:administradores,id|required'
                ];
                $validator = \Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    $respuesta['result'] = false;
                    $respuesta['validator'] = $validator->errors()->all();
                    $respuesta['mensaje'] = '¡Error!';
                } else {
                    $instancia = Establecimiento::find($id);
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