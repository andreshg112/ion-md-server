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
}