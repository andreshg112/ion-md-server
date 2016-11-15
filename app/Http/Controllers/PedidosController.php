<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Establecimiento;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\Sede;
use App\Models\Vendedor;
use App\Http\Requests;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

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
    * Por defecto, enviado=0 (Pedidos en cola).
    * Para filtrar entre fechas, se pasan los parametros fecha_inicial y fecha_final.
    * Para una sola fecha, se pasa el parametro fecha_inicial.
    * Las fechas debe estar en formato YYYY-MM-DD.
    * Para los pedidos de un establecimiento, se envía establecimiento_id.
    * Para los pedidos de una sede, se envía sede_id.
    */
    public function index(Request $request)
    {
        $enviado = $request->input('enviado', 0);
        $establecimiento_id = $request->get('establecimiento_id');
        $fecha_final_str = $request->get('fecha_final');
        $fecha_inicial_str = $request->get('fecha_inicial');
        $sede_id = $request->get('sede_id');
        $vendedores_id = [];
        
        if(isset($sede_id) && is_numeric($sede_id)) {
            $vendedores_id = Vendedor::select('id')->where('sede_id', $sede_id)->get();
        } elseif (isset($establecimiento_id) && is_numeric($establecimiento_id)) {
            $establecimiento = Establecimiento::find($establecimiento_id);
            $vendedores_id = ($establecimiento)
            ? $establecimiento->vendedores->pluck('id') : [];
        }
        
        $consulta_base = Pedido::with(['cliente', 'productos'])->where('enviado', $enviado)
        ->whereIn('vendedor_id', $vendedores_id);
        
        if(isset($fecha_inicial_str)){
            $fecha_inicial = Carbon::parse($fecha_inicial_str);
            $fecha_final = (!isset($fecha_final_str))
            ? $fecha_inicial : Carbon::parse($fecha_final_str);
            $consulta_base->whereBetween(DB::raw('date(created_at)'), [$fecha_inicial, $fecha_final]);
        }
        return $consulta_base->get();
    }
    
    public function store(Request $request)
    {
        $respuesta = [];
        $respuesta['result'] = false;
        DB::transaction(function () use($request, &$respuesta) {
            if (!is_array($request->all())) {
                $respuesta['mensaje'] = 'Los datos enviados no tienen el formato correcto.';
            } else {
                $datos = $request->all();
                $rules = [
                'detalles'  => 'required|string',
                'direccion' => 'string|required_if:tipo_pedido,domicilio',
                'numero' => 'required|numeric|digits_between:7,10',
                'vendedor_id' => 'required|numeric|exists:vendedores,id,sede_id,'.$datos['sede_id'],
                'sede_id' => 'required|numeric|exists:sedes,id',
                'tipo_pedido' => 'in:domicilio,mesa|required',
                'subtotal' => 'numeric|required',
                'valor_domicilio' => 'numeric',
                'total' => 'numeric|required',
                'cliente.id' => 'numeric|exists:clientes,id',
                'cliente.celular' => 'numeric|required_without:cliente.telefono|digits:10',
                'cliente.telefono' => 'numeric|required_without:cliente.celular|digits:7',
                'cliente.nombre_completo'  => 'required|string',
                'cliente.email' => 'email',
                'cliente.fecha_nacimiento' => 'date',
                'cliente.establecimiento_id' => 'required|exists:establecimientos,id',
                'cliente.administrador_id' => 'required|exists:administradores,id',
                'productos' => 'array|required',
                'productos.*.nombre' => 'string|required',
                'productos.*.valor' => 'numeric|required',
                'productos.*.id' => 'numeric|exists:productos,id'
                ];
                try {
                    $validator = \Validator::make($datos, $rules);
                    $validator->sometimes(['cliente.direccion_casa', 'cliente.direccion_oficina', 'cliente.direccion_otra'],
                    'string|required_without_all:cliente.direccion_casa,cliente.direccion_oficina,cliente.direccion_otra', function($input) {
                        return $input->tipo_pedido == 'domicilio';
                    });
                    if ($validator->fails()) {
                        $respuesta['validator'] = $validator->errors()->all();
                        $respuesta['mensaje'] = '¡Error!';
                    } else {
                        $cli = $datos['cliente'];
                        //unset($datos['cliente']);
                        $cli['id'] = (isset($cli['id'])) ? $cli['id'] : null;
                        //Si no existe el cliente, se crea.
                        $cliente = Cliente::firstOrNew(['id' => $cli['id']]);
                        $cliente->fill($cli)->save();
                        $pedido = $cliente->pedidos()->save(new Pedido($datos));
                        if ($pedido) {
                            $productos = $datos['productos'];
                            $pedido->productos()->saveMany(array_map(function($prod) use($cli, $datos){
                                //Se proceden a guardar todos los productos.
                                $prod['id'] = (isset($prod['id'])) ? $prod['id'] : null;
                                //Si no existe el producto, lo crea:
                                $producto = Producto::firstOrNew(['id' => $prod['id']]);
                                //Si existe el producto lo actualiza.
                                $producto->establecimiento_id = $cli['establecimiento_id'];
                                $producto->sede_id = $datos['sede_id'];
                                $producto->fill($prod)->save();
                                return $producto;
                            }, $productos));
                            $pedido->load(['cliente', 'productos']);
                            $respuesta['result'] = $pedido;
                            $respuesta['mensaje'] = "Registrado correctamente.";
                        } else {
                            $respuesta['mensaje'] = "No se pudo registrar.";
                        }
                    }
                } catch (Exception $e) {
                    $respuesta['mensaje'] = "Error: $e->getMessage()";
                }
            }
        });
        return $respuesta;
    }
    
    //Función para despachar los pedidos.
    public function update(Request $request, $id)
    {
        $respuesta = [];
        $respuesta['result'] = false;
        DB::transaction(function () use($request, &$respuesta, $id) {
            if (!is_array($request->all())) {
                $respuesta['mensaje'] = 'Los datos enviados no tienen el formato correcto.';
            } else {
                $instancia = Pedido::find($id);
                if ($instancia) {
                    $rules = [
                    'detalles'  => 'required|string',
                    'direccion' => 'string|required_if:tipo_pedido,domicilio',
                    'numero' => 'required|numeric|digits_between:7,10',
                    'vendedor_id' => 'numeric|exists:vendedores,id',
                    'tipo_pedido' => 'in:domicilio,mesa|required',
                    'subtotal' => 'numeric|required',
                    'valor_domicilio' => 'numeric',
                    'total' => 'numeric|required',
                    'cliente.id' => 'numeric|exists:clientes,id',
                    'cliente.celular' => 'numeric|required_without:cliente.telefono|digits:10',
                    'cliente.telefono' => 'numeric|required_without:cliente.celular|digits:7',
                    'cliente.nombre_completo'  => 'required|string',
                    'cliente.email' => 'email',
                    'cliente.fecha_nacimiento' => 'date',
                    'enviado'  => 'required|boolean',
                    'tipo_mensajero' => 'in:externo,propio',
                    'establecimiento.mensaje' => 'string|required_if:enviado,1',
                    'productos' => 'array|required',
                    'productos.*.nombre' => 'string|required',
                    'productos.*.valor' => 'numeric|required',
                    'productos.*.id' => 'numeric|exists:productos,id'
                    ];
                    try {
                        $validator = \Validator::make($request->all(), $rules);
                        if ($validator->fails()) {
                            $respuesta['validator'] = $validator->errors()->all();
                            $respuesta['mensaje'] = '¡Error!';
                        } else {
                            $datos = $request->all();
                            $cliente = $datos['cliente'];
                            if($instancia->enviado == 0 && $datos['enviado'] == 1) {
                                //Se esta despachando
                                $segundos = Carbon::instance($instancia->created_at)
                                ->diffInSeconds(Carbon::now());
                            }
                            $instancia->fill($datos);
                            if(isset($segundos)) {
                                //Si se calcularon los segundos de despacho.
                                $instancia->tiempo_despacho = $segundos;
                            }
                            $guardo = $instancia->save();
                            if ($guardo) {
                                
                                //Actualizar cliente
                                $cli = $datos['cliente'];
                                $cli['id'] = (isset($cli['id'])) ? $cli['id'] : null;
                                $cliente = Cliente::find($cli['id']);
                                $cliente->fill($cli)->save();
                                
                                //Se quitan los productos que tenía
                                $instancia->productos()->detach();
                                
                                //Actualizar productos
                                $productos = $datos['productos'];
                                $instancia->productos()->saveMany(array_map(function($prod) use($cli){
                                    //Se proceden a guardar todos los productos.
                                    $prod['id'] = (isset($prod['id'])) ? $prod['id'] : null;
                                    //Si no existe el producto, lo crea:
                                    $producto = Producto::firstOrNew(['id' => $prod['id']]);
                                    //Si existe el producto lo actualiza.
                                    $producto->establecimiento_id = $cli['establecimiento_id'];
                                    $producto->fill($prod)->save();
                                    return $producto;
                                }, $productos));
                                
                                $instancia->load(['cliente', 'productos']);
                                
                                $respuesta['mensaje'] = "Actualizado correctamente.";
                                $respuesta['result'] = $instancia;
                                if(isset($segundos) && $datos['tipo_pedido'] == 'domicilio') {
                                    //Si segundos tiene valor quiere decir que va a despachar.
                                    //Si es domicilio, se envía el mensaje.
                                    $establec = $datos['establecimiento'];
                                    $establecimiento = Establecimiento::find($establec['id']);
                                    if($establecimiento->sms_restantes > 0) {
                                        //Si tiene mensajes...
                                        if($cliente['celular']) {
                                            //Construcción del mensaje personalizado
                                            $nombre = explode(' ', $cliente['nombre_completo']);
                                            $mensaje = $nombre[0].', '.$establec['mensaje'];
                                            $destinatarios = Utilities::concatenarDestinatarios([$cliente]);
                                            $respuesta['notificacion'] = MensajesController::enviarMensaje($destinatarios, $mensaje);
                                            $respuesta['sms_restantes'] = Establecimiento::restarSMS($establec['id']);
                                        } else {
                                            $respuesta['notificacion'] = 'El cliente no tiene celular registrado para enviar un mensaje.';
                                        }
                                    } else {
                                        $respuesta['sms_restantes'] = 0;
                                    }
                                }
                            } else {
                                $respuesta['mensaje'] = "No se pudo actualizar.";
                            }
                        }
                    } catch (Exception $e) {
                        $respuesta['mensaje'] = "Error: $e->getMessage()";
                    }
                } else {
                    $respuesta['mensaje'] = 'No se encuentra registrado.';
                }
            }
        });
        return $respuesta;
    }
    
}