<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Cliente;
use App\Models\Pedido;
use DB;
use App\Models\Vendedor;
use App\Models\Sede;
use Carbon\Carbon;

class PedidosController extends Controller
{
    public function getPedidosCliente(Request $request, $cliente_id)
    {
        $establecimiento_id = $request->input('establecimiento_id', '');
        $sedes_id = Sede::select('id')->where('establecimiento_id', $establecimiento_id)->get();
        $vendedores_id = Vendedor::select('id')->whereIn('sede_id', $sedes_id)->get();
        $limit = $request->input('limit', 5);
        $enviado = $request->input('enviado', '');
        return Pedido::where('cliente_id', $cliente_id)
        ->whereIn('vendedor_id', $vendedores_id)
        ->where('enviado', 'like', "%$enviado%")
        ->limit($limit)
        ->orderBy('created_at', 'desc')
        ->get();
    }
    
    public function destroy(Request $request, $id)
    {
        $instancia = Pedido::find($id);
        $respuesta = [];
        if ($instancia) {
            $instancia->delete();
            $respuesta['result'] = $instancia->trashed();
            if ($respuesta['result']) {
                $respuesta['mensaje'] = "Pedido cancelado correctamente.";
                $respuesta['eliminado'] = $instancia;
            } else {
                $respuesta['mensaje'] = "Error tratando de eliminar.";
            }
        } else {
            $respuesta['mensaje'] = "No se encuentra registrado.";
        }
        return $respuesta;
    }
    
    
    /**
    * Busca los pedidos de una sede.
    * Para recibir los pedidos en cola, se envia el parametro enviado=0.
    * Para filtrar entre fechas, se pasa el parametro fecha_inicial y hasta por fecha_final.
    */
    public function index(Request $request)
    {
        $enviado = $request->input('enviado', '');
        $sede_id = $request->input('sede_id', '');
        
        $vendedores_id = Vendedor::select('id')->where('sede_id', $sede_id)->get();
        $consulta_base = Pedido::with('cliente')->where('enviado', 'like', "%$enviado%")
        ->whereIn('vendedor_id', $vendedores_id);
        
        $fecha_inicial_str = $request->get('fecha_inicial');
        if(isset($fecha_inicial_str)){
            $fecha_inicial = Carbon::parse($fecha_inicial_str);
            $fecha_final_str = $request->get('fecha_final');
            $fecha_final = (!isset($fecha_final_str)) ? $fecha_inicial : Carbon::parse($fecha_final_str);
            $consulta_base->whereBetween(DB::raw('date(created_at)'), [$fecha_inicial, $fecha_final]);
        }
        //return $consulta_base->getQuery();
        return $consulta_base->get();
    }
    
    public function store(Request $request)
    {
        $respuesta = [];
        DB::transaction(function () use($request, &$respuesta) {
            if (!is_array($request->all())) {
                $respuesta['result'] = false;
                $respuesta['mensaje'] = 'Los datos enviados no tienen el formato correcto.';
            } else {
                $datos = $request->all();
                $cli = $datos['cliente'];
                $cli['id'] = (isset($cli['id'])) ? $cli['id'] : null;
                $rules = [
                'detalles'  => 'required|string',
                'direccion' => 'required|string',
                'numero' => 'required|numeric|digits_between:7,10',
                'vendedor_id' => 'numeric|exists:vendedores,id',
                'cliente.id' => 'numeric|exists:clientes,id',
                'cliente.celular' => 'numeric|required_without:cliente.telefono|digits:10',
                'cliente.telefono' => 'numeric|required_without:cliente.celular|digits:7',
                'cliente.nombre_completo'  => 'required|string',
                'cliente.email' => 'email',
                'cliente.direccion_casa'  => 'string|required_without_all:cliente.direccion_oficina,cliente.direccion_otra',
                'cliente.direccion_oficina' => 'string|required_without_all:cliente.direccion_casa,cliente.direccion_otra',
                'cliente.direccion_otra' => 'string|required_without_all:cliente.direccion_oficina,cliente.direccion_casa',
                'cliente.fecha_nacimiento' => 'date',
                'cliente.establecimiento_id' => 'required|exists:establecimientos,id'
                ];
                try {
                    $validator = \Validator::make($datos, $rules);
                    if ($validator->fails()) {
                        $respuesta['result'] = false;
                        $respuesta['validator'] = $validator->errors()->all();
                        $respuesta['mensaje'] = '¡Error!';
                    } else {
                        unset($datos['cliente']);
                        $cliente = Cliente::firstOrNew(['id' => $cli['id']]);
                        $cliente->fill($cli)->save();
                        $respuesta['result'] = $cliente->pedidos()->save(new Pedido($datos));
                        if ($respuesta['result']) {
                            $respuesta['mensaje'] = "Registrado correctamente.";
                        } else {
                            $respuesta['mensaje'] = "No se pudo registrar.";
                        }
                    }
                } catch (Exception $e) {
                    $respuesta['result'] = false;
                    $respuesta['mensaje'] = "Error: $e";
                }
            }
        });
        return $respuesta;
    }
    
    //Función para despachar los pedidos.
    public function update(Request $request, $id)
    {
        $respuesta = [];
        DB::transaction(function () use($request, &$respuesta, $id) {
            if (!is_array($request->all())) {
                $respuesta['result'] = false;
                $respuesta['mensaje'] = 'Los datos enviados no tienen el formato correcto.';
            } else {
                $instancia = Pedido::find($id);
                if ($instancia) {
                    $rules = [
                    'detalles'      => 'required|string',
                    'direccion' => 'required|string',
                    'numero' => 'required|numeric|digits_between:7,10',
                    'enviado'  => 'required|boolean',
                    'establecimiento.mensaje' => 'string|required'
                    ];
                    try {
                        $validator = \Validator::make($request->all(), $rules);
                        if ($validator->fails()) {
                            $respuesta['result'] = false;
                            $respuesta['validator'] = $validator->errors()->all();
                            $respuesta['mensaje'] = '¡Error!';
                        } else {
                            $datos = $request->all();
                            $cliente = $datos['cliente'];
                            $establecimiento = $datos['establecimiento'];
                            if($instancia->enviado == 0 && $datos['enviado'] == 1) {
                                //Se esta despachando
                                $segundos = Carbon::instance($instancia->created_at)
                                ->diffInSeconds(Carbon::now());
                            }
                            $instancia->fill($datos);
                            $instancia->tiempo_despacho = $segundos;
                            $respuesta['result'] = $instancia->save();
                            if ($respuesta['result']) {
                                $respuesta['mensaje'] = "Actualizado correctamente.";
                                $respuesta['result'] = $instancia;
                                if($cliente['celular']) {
                                    //Construcción del mensaje personalizado
                                    $nombre = explode(' ', $cliente['nombre_completo']);
                                    $mensaje = $nombre[0].', '.$establecimiento['mensaje'];
                                    $destinatarios = Utilities::concatenarDestinatarios([$cliente]);
                                    $respuesta['notificacion'] = MensajesController::enviarMensaje($destinatarios, $mensaje);
                                } else {
                                    $respuesta['notificacion'] = 'El cliente no tiene celular registrado para enviar un mensaje.';
                                }
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
        });
        return $respuesta;
    }
    
}