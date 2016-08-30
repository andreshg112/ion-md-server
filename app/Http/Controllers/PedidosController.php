<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Cliente;
use App\Models\Pedido;
use \DB;

class PedidosController extends Controller
{
    
    public function index(Request $request)
    {
        $enviado = $request->get('enviado');
        return Pedido::with('cliente')->where('enviado', 'like', "%$enviado%")->get();
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
                $cli['id'] = (isset($cli['id'])) ? $cli['id'] : null ;
                $rules = [
                'cliente.celular' => 'required|numeric',
                'cliente.nombres'  => 'required|string',
                'cliente.apellidos'  => 'required|string',
                //'cliente.email' => 'unique:clientes,email,'.$cli['celular'].'celular',
                'cliente.email' => 'email',
                'detalles'  => 'required|string',
                'establecimiento_id' => 'required|exists:establecimientos,id'
                ];
                try {
                    $validator = \Validator::make($datos, $rules);
                    if ($validator->fails()) {
                        $respuesta['result'] = false;
                        $respuesta['validator'] = $validator->errors()->all();
                        $respuesta['mensaje'] = '¡Error!';
                    } else {
                        unset($datos['cliente']);
                        $cliente = Cliente::firstOrNew(['celular' => $cli['celular']]);
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
                    'enviado'  => 'required|boolean'
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
                            $instancia->fill($datos);
                            $respuesta['result'] = $instancia->save();
                            if ($respuesta['result']) {
                                $respuesta['mensaje'] = "Actualizado correctamente.";
                                $respuesta['result'] = $instancia;
                                $respuesta['notificacion'] = MensajesController::enviarMensaje(intval($cliente['celular']), $cliente['nombres'].", su pedido ha sido enviado.");
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