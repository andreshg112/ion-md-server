<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Administrador;
use App\Models\Cliente;
use App\Models\Establecimiento;
use App\Models\Oferta;
use App\Models\Pedido;
use App\Models\Sede;
use App\Models\Vendedor;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class AdministradoresController extends Controller
{
    public function enviarOferta(Request $request, $administrador_id)
    {
        $respuesta = [];
        $respuesta['result'] = false;
        DB::transaction(function() use($request, &$respuesta, $administrador_id) {
            try {
                $rules = [
                'mensaje' => 'string|max:155|required',
                'clientes.*.celular' => 'numeric|exists:clientes,celular',
                "establecimiento_id' => 'numeric|exists:establecimientos,id,administrador_id,$administrador_id"
                ];
                $validator = \Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    $respuesta['validator'] = $validator->errors()->all();
                    $respuesta['mensaje'] = '¡Error!';
                } else {
                    $datosRecibidos = $request->all();
                    $clientes = $datosRecibidos['clientes'];
                    unset($datosRecibidos['clientes']);
                    $mensaje = $datosRecibidos['mensaje'];
                    $instancia = new Oferta($datosRecibidos);
                    $instancia->administrador_id = $administrador_id;
                    $instancia_saved = $instancia->save();
                    if($instancia_saved) {
                        $respuesta['result'] = $instancia->clientes()->saveMany(
                        array_map(function($cliente){
                            return Cliente::find($cliente['id']);
                        }, $clientes)
                        );
                        if($respuesta['result']){
                            $destinatarios = Utilities::concatenarDestinatarios($clientes);
                            $respuesta['notificacion'] = MensajesController::enviarMensaje($destinatarios, $mensaje);
                            $cantidadDestinatarios = count($clientes);
                            //Solamente está restando 1.
                            $respuesta['sms_restantes'] =
                            Establecimiento::restarSMS($datosRecibidos['establecimiento_id']);
                        } else {
                            $instancia->delete();
                            $respuesta['mensaje'] = 'No se pudo guardar.';
                        }
                    }
                }
            } catch (Exception $e) {
                $respuesta['mensaje'] = $e->getMessage();
            }
        });
        return $respuesta;
    }
    
    public function felicitarCliente(Request $request, $administrador_id)
    {
        $respuesta = [];
        $respuesta['result'] = false;
        DB::transaction(function() use($request, &$respuesta) {
            try {
                $rules = [
                'mensaje'      => 'string|max:155|required',
                'cliente.celular' => 'numeric|exists:clientes,celular'
                ];
                $validator = \Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    $respuesta['validator'] = $validator->errors()->all();
                    $respuesta['mensaje'] = '¡Error!';
                } else {
                    $datos_recibidos = $request->all();
                    $cliente = $datos_recibidos['cliente'];
                    $mensaje = $datos_recibidos['mensaje'];
                    $respuesta['result'] = true;
                    $destinatarios = Utilities::concatenarDestinatarios([$cliente]);
                    $respuesta['notificacion'] = MensajesController::enviarMensaje($destinatarios, $mensaje);
                }
            } catch (Exception $e) {
                $respuesta['mensaje'] = $e->getMessage();
            }
        });
        return $respuesta;
    }
    
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
        $consulta_base = Cliente::select(DB::raw('*, (select count(*) from pedidos where pedidos.cliente_id = clientes.id and enviado = 1 and deleted_at is null) as total_pedidos'))
        ->with('establecimiento')
        ->whereIn('establecimiento_id', $establecimientos_id)
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
            $establecimientos_id = Establecimiento::select('id')
            ->where('administrador_id', $administrador_id)->get();
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
    
    //Retorna la cantidad de pedidos por día, en un período de tiempo determinado.
    //El período de tiempo se pasa por parámetros GET fecha_final y fecha_inicial.
    public function getPedidosPorDiaEnLapso(Request $request, $administrador_id)
    {
        $establecimiento_id = $request->get('establecimiento_id');
        $sede_id = $request->get('sede_id');
        $establecimientos_id = [];
        $sedes_id = [];
        
        if(!isset($establecimiento_id)){
            $establecimientos_id = Establecimiento::select('id')->where('administrador_id', $administrador_id)->get();
        } else {
            array_push($establecimientos_id, $establecimiento_id);
        }
        
        if(!isset($sede_id)){
            $sedes_id = Sede::select('id')->whereIn('establecimiento_id', $establecimientos_id)->get();
        } else {
            array_push($sedes_id, $sede_id);
        }
        
        $vendedores_id = Vendedor::select('id')->whereIn('sede_id', $sedes_id)->get();
        
        $fecha_final_str = $request->get('fecha_final');
        $fecha_final = (!isset($fecha_final_str))
        ? Carbon::now() : Carbon::parse($fecha_final_str);
        $fecha_inicial_str = $request->get('fecha_inicial');
        $fecha_inicial = (!isset($fecha_inicial_str))
        ? $fecha_final->copy()->subMonth() : Carbon::parse($fecha_inicial_str);
        
        return Pedido
        ::selectRaw('dayname(created_at) as dayname, date(created_at) as fecha, count(enviado) as pedidos_enviados, sum(subtotal) as valor, sum(IF(tipo_mensajero = "propio" and tipo_pedido = "domicilio", valor_domicilio, 0)) as valor_domicilios')
        ->whereIn('vendedor_id', $vendedores_id)
        ->whereBetween(DB::raw('date(created_at)'), [$fecha_inicial, $fecha_final])
        ->where('enviado', 1)->groupBy('fecha')
        ->get();
    }
    
    //Retorna la el valor total por día de los pedidos, no la cantidad de pedidos
    //... en un período de tiempo determinado.
    /*public function getValorPedidosPorDia(Request $request, $administrador_id)
    {
    $establecimiento_id = $request->get('establecimiento_id');
    $sede_id = $request->get('sede_id');
    $establecimientos_id = [];
    $sedes_id = [];
    
    if(!isset($establecimiento_id)){
    $establecimientos_id = Establecimiento::select('id')->where('administrador_id', $administrador_id)->get();
    } else {
    array_push($establecimientos_id, $establecimiento_id);
    }
    
    if(!isset($sede_id)){
    $sedes_id = Sede::select('id')->whereIn('establecimiento_id', $establecimientos_id)->get();
    } else {
    array_push($sedes_id, $sede_id);
    }
    
    $vendedores_id = Vendedor::select('id')->whereIn('sede_id', $sedes_id)->get();
    
    $fecha_final_str = $request->get('fecha_final');
    $fecha_final = (!isset($fecha_final_str))
    ? Carbon::now() : Carbon::parse($fecha_final_str);
    $fecha_inicial_str = $request->get('fecha_inicial');
    $fecha_inicial = (!isset($fecha_inicial_str))
    ? $fecha_final->copy()->subMonth() : Carbon::parse($fecha_inicial_str);
    
    return Pedido::selectRaw('dayname(created_at) as dayname, date(created_at) as fecha, sum(total) as valor')
    ->whereIn('vendedor_id', $vendedores_id)
    ->whereBetween(DB::raw('date(created_at)'), [$fecha_inicial, $fecha_final])
    ->where('enviado', 1)->groupBy('fecha')->havingRaw('fecha is not null')
    ->get();
    }*/
    
    public function getClientesPorGenero(Request $request, $administrador_id)
    {
        $establecimiento_id = $request->get('establecimiento_id');
        $establecimientos_id = [];
        
        if(!isset($establecimiento_id)){
            $establecimientos_id = Establecimiento::select('id')
            ->where('administrador_id', $administrador_id)->get();
        } else {
            array_push($establecimientos_id, $establecimiento_id);
        }
        
        return Cliente::selectRaw('count(*) as cantidad, genero')
        ->whereIn('establecimiento_id', $establecimientos_id)
        ->groupBy('genero')->get();
    }
}