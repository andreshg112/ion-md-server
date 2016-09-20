<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Establecimiento;
use App\Models\Pedido;
use DB;
use App\Models\Sede;
use App\Models\Vendedor;
use App\Models\Cliente;

class AdministradoresController extends Controller
{
    public function getClientes(Request $request, $administrador_id)
    {
        $cumpleanos = $request->input('cumpleanos', false);
        $establecimiento_id = $request->get('establecimiento_id');
        $establecimientos_id = [];
        if(!isset($establecimiento_id)){
            $establecimientos_id = Establecimiento::select('id')->where('administrador_id', $administrador_id)->get();
        } else {
            array_push($establecimientos_id, $establecimiento_id);
        }
        $consulta_base = Cliente::select(DB::raw('*, (select count(*) from pedidos where pedidos.cliente_id = clientes.id) as total_pedidos'))->whereIn('establecimiento_id', $establecimientos_id)
        ->orderBy('total_pedidos', 'desc')->orderBy('nombre_completo', 'asc');
        if($cumpleanos) {
            $consulta_base->where('fecha_nacimiento', DB::raw('date(now())'));
        }
        return $consulta_base->get();
    }
    
    public function getPedidosDiaSemana(Request $request, $administrador_id)
    {
        $establecimiento_id = $request->get('establecimiento_id');
        $sede_id = $request->get('sede_id');
        $establecimientos_id = [];
        $sedes_id = [];
        
        // Si no se envia establecimiento, se consultan todos los de un administrador.
        if(!isset($establecimiento_id)){
            $establecimientos_id = Establecimiento::select('id')->where('administrador_id', $administrador_id)->get();
        } else {
            array_push($establecimientos_id, $establecimiento_id);
        }
        
        // Si no se envia sede, se consultan todas las de un establecimiento.
        if(!isset($sede_id)){
            $sedes_id = Sede::select('id')->whereIn('establecimiento_id', $establecimientos_id)->get();
        } else {
            array_push($sedes_id, $sede_id);
        }
        
        $vendedores_id = Vendedor::select('id')->whereIn('sede_id', $sedes_id)->get();
        
        $respuesta = [];
        $respuesta['result'] = [];
        $respuesta['result']['Lunes'] = Pedido::whereIn('vendedor_id', $vendedores_id)
        ->where(DB::raw('weekday(created_at)'), 0)
        ->count();
        
        $respuesta['result']['Martes'] = Pedido::whereIn('vendedor_id', $vendedores_id)
        ->where(DB::raw('weekday(created_at)'), 1)
        ->count();
        
        $respuesta['result']['Miércoles'] = Pedido::whereIn('vendedor_id', $vendedores_id)
        ->where(DB::raw('weekday(created_at)'), 2)
        ->count();
        
        $respuesta['result']['Jueves'] = Pedido::whereIn('vendedor_id', $vendedores_id)
        ->where(DB::raw('weekday(created_at)'), 3)
        ->count();
        
        $respuesta['result']['Viernes'] = Pedido::whereIn('vendedor_id', $vendedores_id)
        ->where(DB::raw('weekday(created_at)'), 4)
        ->count();
        
        $respuesta['result']['Sábado'] = Pedido::whereIn('vendedor_id', $vendedores_id)
        ->where(DB::raw('weekday(created_at)'), 5)
        ->count();
        
        $respuesta['result']['Domingo'] = Pedido::whereIn('vendedor_id', $vendedores_id)
        ->where(DB::raw('weekday(created_at)'), 6)
        ->count();
        
        return $respuesta;
    }
}